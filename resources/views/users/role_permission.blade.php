@extends('layouts.master')
@section('title')
    <title>Role Permission</title>
@endsection

@section('css')
    <style type="text/css">
        .tab-pane{
            height:150px;
            overflow-y:scroll;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Role Permission</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Role Permission</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header with-border">
                                <h4 class="card-title">Tambah Permission Baru</h4>
                            </div>
                            
                                <form role="form" action="{{ route('users.add_permission') }}" method="POST">
                                {{ csrf_field() }}
                                 <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" 
                                        name="name"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary">Tambah Baru</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header with-border">
                              <h3 class="card-title">Set Permission to Role</h3>
                            </div> 
                            
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                    <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            
                            <form role="form" action="{{ route('users.roles_permission') }}" method="GET">
                                 <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Roles</label>
                                        <div class="input-group">
                                            <select name="role" class="form-control">
                                                @foreach ($roles as $value)
                                                <option value="{{ $value }}" {{ request()->get('role') == $value ? 'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-danger">Check!</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- jika $permission tidak bernilai kosong --}} 
                            @if (!empty($permission))
                            <form role="form" action="{{ route('users.setRolePermission', request()->get('role')) }}" method="POST">
                                {{ csrf_field() }}
                                 <div class="card-body">
                                <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group">
                                        <div class="nav-tabs-costum">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a href="#tab_1" data-toggle="tab">Permissions</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    @php $no = 1; @endphp
                                                    @foreach ($permission as $key => $row)
                                                        <input type="checkbox"
                                                            name="permission[]"
                                                            class="minimal-red"
                                                            value="{{ $row }}"
                                                            {{-- CHECK, JIKA PERMISSION TERSEBUT SUDAH DI SET, MAKA CHECKED --}}
                                                            {{ in_array($row, $hasPermission) ? 'checked':'' }}
                                                            > {{ $row }} <br>
                                                        @if ($no++%4 == 0)
                                                        <br>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-send"></i> Set Permission
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                     </div>          
                </div>
            </div>
        </section>
    </div>
@endsection
