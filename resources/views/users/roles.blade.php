@extends('layouts.master')
@section('title')
    <title>Set Role</title>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Set Role</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active">Set Role</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <form action="{{ route('users.set_role', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="card-header with-border">
                                    <h3 class="card-title">Set Role</h3>
                                </div>
                                
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button> 
                                        <strong>{{ $message }}</strong>
                                </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Nama</td>
                                                <td>:</td>
                                                <td>{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>:</td>
                                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td>:</td>
                                                <td>
                                                    @foreach ($roles as $row)
                                                    <input type="radio" name="role"
                                                        {{ $user->hasRole($row) ? 'checked':'' }}
                                                        value="{{ $row }}"> {{ $row }} <br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-sm float-right">
                                        Set Role
                                    </button>
                                </div>
                            </form>
                        </div>
                     </div>          
                </div>
            </div>
        </section>
    </div>
@endsection

 