@extends('layouts.master')
@section('title')
    <title>Edit Kategori</title>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit Kategori</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
​
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                    <div class="card">                          
                        <div class="card-header with-border">
                            <h3 class="card-title">Edit</h3>
                        </div>
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                            <form role="form" action="{{ route('kategori.update', $categories->id) }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                             <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Kategori</label>
                                    <input type="text" 
                                    name="name"
                                    value="{{ $categories->name }}" 
                                    class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea name="description" value="{{ $categories->name }}" id="description" cols="5" rows="5" class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}"></textarea>
                                </div>
                            </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>        
                </div>
            </div>
        </section>
    </div>
@endsection