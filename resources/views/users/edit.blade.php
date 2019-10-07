@extends('layouts.master')
@section('title')
    <title>Edit User</title>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit User</h3>
                        </div>
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                            <form role="form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                             <div class="card-body">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" 
                                    name="name"
                                    required 
                                    value="{{ $user->name }}"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" 
                                    name="email"
                                    readonly
                                    required 
                                    value="{{ $user->email }}"
                                    class="form-control {{ $errors->has('email') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" 
                                    name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                    <p class="text-warning">Biarkan kosong, jika tidak ingin mengganti password</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="card-footer">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-send"></i>Update
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