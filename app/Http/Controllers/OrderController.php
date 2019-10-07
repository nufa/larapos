<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Customer;
use App\Product;
use App\Order;
use App\User;
use App\Exports\OrderInvoice;
use Cookie;
use DB;
use PDF;

class OrderController extends Controller
{
    public function addOrder()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('orders.add', compact('products'));
    }
    public function getProduct($id)
    {
        $products = Product::findOrFail($id);
        return response()->json($products, 200);
    }
    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $getCart = json_decode($request->cookie('cart'), true);
        if ($getCart) {
            if (array_key_exists($request->product_id, $getCart)) {
                $getCart[$request->product_id]['qty'] += $request->qty;
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
            } 
        }
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $request->qty
        ];
        return response()->json($getCart, 200)
            ->cookie('cart', json_encode($getCart), 120);
    }
    public function getCart()
    {
        $cart = json_decode(request()->cookie('cart'), true);
        return response()->json($cart, 200);
    }
    public function removeCart($id)
    {
        $cart = json_decode(request()->cookie('cart'), true);
        unset($cart[$id]);
        return response()->json($cart, 200)->cookie('cart', json_encode($cart), 120);
    }
    public function checkout()
    {
        return view('orders.checkout');
    }
    public function buy(Request $request)
    {
        $cart = json_decode($request->cookie('cart'), true);
        return response()->json($cart, 200);
    }
    public function storeOrder(Request $request)
    {
    	//validasi
    	$this->validate($request, [
    		'email' => 'required|email',
    		'name' => 'required|string|max:100',
    		'address' => 'required',
    		'phone' => 'required|numeric'
    	]);

    	//mengambil list cart dari cookie
    	$cart = json_decode($request->cookie('cart'), true);
    	//memanipulasi array untuk menciptakan key baru yakni result dari hasil perkalian price * qty
    	$result = collect($cart)->map(function($value) {
    		return [
    			'code' => $value['code'],
    			'name' => $value['name'],
    			'qty' => $value['qty'],
    			'price' => $value['price'],
    			'result' => $value['price'] * $value['qty']
    		];
    	})->all();

    	//database transaction
    	DB::beginTransaction();
    	try {
    		//menyimpan data ke table customers
    		$customer = Customer::firstOrCreate([
    			'email' => $request->email
    		], [
    			'name' => $request->name,
    			'address' => $request->address,
    			'phone' => $request->phone
    		]);

    		//menyimpan data ke table orders
    		$order = Order::create([
    			'invoice' => $this->generateInvoice(),
    			'customer_id' => $customer->id,
    			'user_id' => auth()->user()->id,
    			'total' => array_sum(array_column($result, 'result'))
    			//array_sum untuk menjumlahkan value dari result
    		]);

    		//looping cart untuk disimpan ke table order_details
    		foreach ($result as $key => $row) {
    			$order->order_detail()->create([
    				'product_id' => $key,
    				'qty' => $row['qty'],
    				'price' => $row['price']
    			]);
    		}
    		//apabila tidak terjadi error, penyimpanan diverifikasi
    		DB::commit();

    		//me-return status dan message berupa code invoice, dan menghapus cookie
    		return response()->json([
    			'status' => 'success',
    			'message' => $order->invoice,
    		], 200)->cookie(Cookie::forget('cart'));
    	} catch (\Exception $e) {
    		//jika ada error, maka akan dirollback sehingga tidak ada data yang tersimpan
    		DB::rollback();
    		//pesan gagal akan di-return
    		return response()->json([
    			'status' => 'failed',
    			'message' => $e->getMessage()
    		], 400);
    	}
    }
    public function generateInvoice()
    {
    	//mengambil data dari table order
    	$order = Order::orderBy('created_at', 'DESC');
    	//jika sudah terdapat records
    	if ($order->count() > 0) {
    		//mengambil data pertama yang sudah dishort DESC
    		$order = $order->first();
    		//explode invoice dari hasil angkanya
    		$explode = explode('-', $order->invoice);
    		$count = $explode[1] + 1;
    		//angka dari hasil explode di +1
    		return 'INV-' . $count;
    	}
    	//jika belum terdapat record maka akan me-return INV-1
    	return 'INV-1';
    }
    public function index(Request $request)
    {
    	//Mengambil data customer
    	$customers = Customer::orderBy('name', 'ASC')->get();
    	//Mengambil data user yang memiliki role kasir
    	$users = User::role('kasir')->orderBy('name', 'ASC')->get();
    	//Mengambil data transaksi
    	$orders = Order::orderBy('created_at', 'DESC')->with('order_detail', 'customer');

    	//Jika Pelanggan Dipilih Pada Combobox
    	if (!empty($request->customer_id)) {
    		//Maka ditambahkan where condition
    		$orders = $orders->where('customer_id', $request->customer_id);
    	}

    	//Jika User / Kasir Dipilih Pada Combobox
    	if (!empty($request->user_id)) {
    		//Maka ditambahkan where condition
    		$orders = $orders->where('user_id', $request->user_id);
    	}

    	if (!empty($request->start_date) && !empty($request->end_date)) {
    		//Maka di validasi dimana formatnya harus date
    		$this->validate($request, [
    			'start_date' => 'nullable|date',
    			'end_date' => 'nullable|date'
    		]);

    		//start & end date di re-format menjadi Y-m-d H:i:s
    		$start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
    		$end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

    		//Ditambahkan wherebetween condition untuk mengambil data dengan range
    		$orders = $orders->whereBetween('created_at', [$start_date, $end_date])->get();
    	} else {
    		//Jika Start date & end date kosong, maka di load 10 data terbaru
    		$orders = $orders->take(10)->skip(0)->get();
    	}

    	//Menampilkan ke view
    	return view('orders.index', [
    		'orders' => $orders,
    		'sold' => $this->countItem($orders),
    		'total' => $this->countTotal($orders),
    		'total_customer' => $this->countCustomer($orders),
    		'customers' => $customers,
    		'users' => $users
    	]);
    }
    private function countCustomer($orders)
    {
    	//Array Kosong Didefinisikan
    	$customer = [];
    	//Jika Terdapat data yang akan ditampilkan
    	if ($orders->count() > 0) {
    		//di looping untuk menyimpan email ke dalam array
    		foreach ($orders as $row) {
    			$customer[] = $row->customer->email;
    		}
    	}
    	//Menghitung total data yang ada di dalam array
    	//Dimana data yang duplicate akan dihapus menggunakan array_unique
    	return count(array_unique($customer));
    }
    private function countTotal($orders)
    {
    	//Default total bernilai 0
    	$total = 0;
    	//Jika data ada
    	if ($orders->count() > 0) {
    		//Mengambil value dari total -> pluck() akan mengubahnya menjadi array
    		$sub_total = $orders->pluck('total')->all();
    		//Kemudian data yang ada didalam array dijumlahkan
    		$total = array_sum($sub_total);
    	}
    	return $total;
    }
    private function countItem($orders)
    {
    	//Default data 0
    	$data = 0;
    	//Jika data tersedia
    	if ($orders->count() > 0) {
    		//Di Looping
    		foreach ($orders as $row) {
    			//Untuk mengambil Qty
    			$qty = $row->order_detail->pluck('qty')->all();
    			//Kemudian Qty Dijumlahkan
    			$val = array_sum($qty);
    			$data += $val;
    		}
    	}
    	return $data;
    }
    public function invoicePdf($invoice)
    {
    	//Mengambil data transaksi berdasarkan invoice
    	$order = Order::where('invoice', $invoice)
    		->with('customer', 'order_detail', 'order_detail.product')->first();
    	//Set Config PDF menggunakan font sans-serif
    	//Dengan meload view invoice.blade.php
    	$pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
    		->loadView('orders.report.invoice', compact('order'));
    	return $pdf->stream();
    }
    public function invoiceExcel($invoice)
    {
    	return (new OrderInvoice($invoice))->download('invoice-' . $invoice . '.xlsx');
    }
}