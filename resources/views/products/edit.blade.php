@extends('layouts.master')
@section('title')
    <title>Edit Data Produk</title>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                    <div class="card">                          
                        <div class="card-header with-border">
                            <h3 class="card-title">Edit Data Produk</h3>
                        </div>
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                            <form role="form" action="{{ route('produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                             <div class="card-body">
                                <div class="form-group">
                                    <label for="">Kode Produk</label>
                                    <input type="text" 
                                    name="code"
                                    required
                                    readonly
                                    value="{{ $product->code }}" 
                                    maxlength="10" 
                                    class="form-control {{ $errors->has('code') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('code') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Produk</label>
                                    <input type="text" 
                                    name="name"
                                    required
                                    value="{{ $product->name }}" 
                                    class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="description" id="description"
                                    cols="5" rows="5" 
                                    class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}"></textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Stok</label>
                                    <input type="number" 
                                    name="stock"
                                    required
                                    value="{{ $product->stock }}" 
                                    class="form-control {{ $errors->has('stock') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga</label>
                                    <input type="number" 
                                    name="price"
                                    required 
                                    value="{{ $product->price }}" 
                                    class="form-control {{ $errors->has('price') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="category_id" id="category_id"
                                    required 
                                    class="form-control {{ $errors->has('category_id') ? 'is-invalid':'' }}">
                                    <option value="">Pilih</option>
                                    @foreach ($categories as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == $product->category_id ? 'selected':'' }}>{{ ucfirst($row->name) }}</option>
                                    @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type="file" name="photo" class="form-control">
                                    <p class="text-danger">{{ $errors->first('photo') }}</p>
                                    @if (!empty($product->photo))
                                    <hr>
                                    <img src="{{ asset('uploads/product/' . $product->photo)}}"
                                    alt="{{ $product->name }}" width="150px" height="150px">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="card-footer">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-refresh"></i>Update
                                    </button>
                                </div>                                
                            </div>
                            </form>
                        </div>
                    </div>        
                </div>
            </div>
        </section>
    </div>
@endsection