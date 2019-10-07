<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Order;


class OrderInvoice implements FromView, WithEvents, ShouldAutoSize
{
	use Exportable;

	public function __construct($invoice)
	{
		$this->invoice = $invoice;
	}

	public function registerEvents(): array
	{
		//Memanipulasi cell
		return [
			AfterSheet::class => function(AfterSheet $event) {
				//Cell Terkait Akan Di Merge
				$event->sheet->mergeCells('A1:C1');
				$event->sheet->mergeCells('A2:B2');
				$event->sheet->mergeCells('A3:C3');


				//Definisikan Style Untuk Cell
				$styleArray = [
					'font' => [
						'bold' => true,
					],
					'aligment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							'argb' => 'FFA0A0A0',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				];
				//Cell Terkait Akan Menggunakan Style Dari $styleArray
				$event->sheet->getStyle('A9:E9')->applyFromArray($styleArray);

				//Formatting Style Untuk Cell Terkait
				$headCustomer = [
					'font' => [
						'bold' => true,
					]
				];
				$event->sheet->getStyle('A5:A7')->applyFromArray($headCustomer);
			},

		];
	}

	public function view(): View
	{
		//Mengambil Data Transaksi Berdasarkan Invoice Yang Diterima Dari Controller
		$order = Order::where('invoice', $this->invoice)
			->with('customer', 'order_detail', 'order_detail.product')->first();
		//Data Tersebut Dipassing Ke File Invoice_Excel
		return view('orders.report.invoice_excel', [
			'order' => $order
		]);
	}
}