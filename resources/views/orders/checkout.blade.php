@extends('layouts.master')

@section('title')
	<title>Checkout</title>
@endsection

@section('css')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css">
@endsection

@section('content')
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Checkout</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('order.transaksi') }}">Transaksi</a></li>
							<li class="breadcrumb-item active">Checkout</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	
		<section class="content" id="ord">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="card-header with-border">
	                            <h3 class="card-title">Data Pelanggan</h3>
	                        </div>
	                        <div class="card-body">
	                        	<div v-if="message" class="alert alert-success">
	                        		Transaksi telah disimpan, Invoice: <strong>#@{{ message }}</strong>
	                        	</div>
	                        	<div class="form-group">
                        			<label for="">Email</label>
                        			<input type="text" name="email"
	                        			v-model="customer.email" class="form-control"
	                        			@keyup.enter.prevent="searchCustomer"
	                        			required
	                        			>
	                        		<p>Tekan enter untuk mengecek email.</p>
	                        		<!-- Event Ketika Tombol Enter Ditekan, Maka Akan Memanggil Method searchCustomer dari Vuejs -->
                        		</div>

                        		<!-- Jika formCustomer Bernilai True, Maka Form Akan Ditampilkan -->

                        		<div v-if="formCustomer">
                        			<div class="form-group">
                        				<label for="">Nama Pelanggan</label>
                        				<input type="text" name="name"
                        				v-model="customer.name"
                        				:disabled="resultStatus"
                        				class="form-control" required>
                        			</div>
                        			<div class="form-group">
                        				<label for="">Alamat</label>
                        				<textarea name="address"
                        				class="form-control"
                        				:disabled="resultStatus"
                        				v-model="customer.address"
                        				cols="5" rows="5" required></textarea>
                        			</div>
                        			<div class="form-group">
                        				<label for="">No Telp</label>
                        				<input type="text" name="phone"
                        				v-model="customer.phone"
                        				:disabled="resultStatus"
                        				class="form-control" required>
                        			</div>
                        		</div>
                        		<div class="card-footer text-muted">
                        			<!-- Jika Value Dari errorMessage ada, maka alert danger akan ditampilkan -->
                        			<div v-if="errorMessage" class="alert alert-danger">
                        				@{{ errorMessage }}
                        			</div>
                        			<!-- Jika Tombol Ditekan Maka Akan Memanggil Method sendOrder -->
                        			<button class="btn btn-primary btn-sm float-right"
                        			:disabled="submitForm"
                        			@click.prevent="sendOrder"
                        			>
                        			@{{ submitForm ? 'Loading...':'Order Now' }}
                        			</button>
                        		</div>
		                    </div>
						</div>
					</div>
					@include('orders.cart')
				</div>
			</div>
		</section>
	</div>
@endsection

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
	<script src="{{ asset('js/transaksi.js') }}"></script>
@endsection