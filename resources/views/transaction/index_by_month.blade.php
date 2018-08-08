@extends('layouts.layouts')

@section('title') Transactions in {{ \Carbon\Carbon::parse($month)->format('F-Y') }} @endsection

@section('content')

<div class="container">
	<a href="{{ route('transaction.showAddSpend') }}" class="btn btn-warning mb-2" title="Create New Spend Transaction">+ Create New Spend Transaction</a>
	<br>
	<a href="{{ route('transaction.showAddReceive') }}" class="btn btn-primary mb-2" title="Create New Receive Transaction">+ Create New Receive Transaction</a>
	<form method="POST" action="{{ route('transaction.indexByMonth') }}" aria-label="{{ __('Add New Receive Transaction') }}">
        @csrf
        <div class="form-group row">
            <label for="month" class="col-md-2 col-form-label text-md-right">{{ __('Search by Month') }}</label>

            <div class="col-md-2">
                <input id="month" type="month" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="month" value="">

                @if ($errors->has('month'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('month') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">
                {{ __('Search') }}
            </button>
        </div>
	</form>
	@if(session('success'))
	<div class="alert alert-success">
		{{ session('success') }}
	</div>
	@endif
    @if($transactions->isEmpty())
        <p class="text-center">You don't have any transactions.</p>
    @endif
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-table"></i> Spend Transactions in {{ \Carbon\Carbon::parse($month)->format('F-Y') }}</div>
    	<div class="card-body">
         	<div class="table-responsive">
	            <table class="table table-bordered" id="spendTransactionTable" width="100%" cellspacing="0">
	              	<thead>
	                	<tr>
	                		<td>No.</td>
	                		<td>Category</td>
	                		<td>From Wallet</td>
	                		<td>Amount</td>
	                		<td>Note</td>
	                		<td>Time</td>
	                		<td></td>
	                		<td></td>
	                	</tr>
	              	</thead>
	              	<tfoot>
	                
	              	</tfoot>
	              	<tbody>
						@php $count = 0 @endphp
	            		@foreach($transactions as $key)
	            		@if($key->category->type == config('const.spendType'))
						<tr>
							<td>{{ $count += 1 }}</td>
							<td>{{ $key->category->name }}</td>
							<td>{{ $key->wallet->name }}</td>
							<td>- {{ number_format($key['amount']) }} đ</td>
							<td>{{ $key['note'] }}</td>
							<td>{{ \Carbon\Carbon::parse($key->date)->format('d-m-Y') }}</td>
							<td class="text-center"><a class="btn btn-primary" href="{{ route('transaction.showEditSpend',$key['id']) }}" title="Edit">Edit</a></td>
							<td class="text-center"><a class="btn btn-danger" href="{{ route('transaction.deleteSpend',$key['id']) }}" title="Delete" onclick="return confirm('Do you want to delete this Transaction?');">Delete</a></td>
						</tr>
						@endif
	            		@endforeach
	              	</tbody>
	            </table>
          	</div>
        </div>
	</div>

	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-table"></i> Receive Transactions in {{ \Carbon\Carbon::parse($month)->format('F-Y') }}</div>
    	<div class="card-body">
         	<div class="table-responsive">
	            <table class="table table-bordered" id="receiveTransactionTable" width="100%" cellspacing="0">
	              	<thead>
	                	<tr>
	                		<td>No.</td>
	                		<td>Category</td>
	                		<td>From Wallet</td>
	                		<td>Amount</td>
	                		<td>Note</td>
	                		<td>Time</td>
	                		<td></td>
	                		<td></td>
	                	</tr>
	              	</thead>
	              	<tfoot>
	                
	              	</tfoot>
	              	<tbody>
						@php $count = 0 @endphp
	            		@foreach($transactions as $key)
	            		@if($key->category->type == config('const.receiveType'))
						<tr>
							<td>{{ $count += 1 }}</td>
							<td>{{ $key->category->name }}</td>
							<td>{{ $key->wallet->name }}</td>
							<td>+ {{ number_format($key['amount']) }} đ</td>
							<td>{{ $key['note'] }}</td>
							<td>{{ \Carbon\Carbon::parse($key->date)->format('d-m-Y') }}</td>
							<td class="text-center"><a class="btn btn-primary" href="{{ route('transaction.showEditReceive',$key['id']) }}" title="Edit">Edit</a></td>
							<td class="text-center"><a class="btn btn-danger" href="{{ route('transaction.deleteReceive',$key['id']) }}" title="Delete" onclick="return confirm('Do you want to delete this Transaction?');">Delete</a></td>
						</tr>
						@endif
	            		@endforeach
	              	</tbody>
	            </table>
          	</div>
        </div>
	</div>

	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-table"></i> Money Transfer Between Wallets in {{ \Carbon\Carbon::parse($month)->format('F-Y') }}</div>
    	<div class="card-body">
    		@if($walletTransactions->isEmpty())
                <p class="text-center">You don't have any transfer.</p>
            @endif
         	<div class="table-responsive">
	            <table class="table table-bordered" id="walletTransactionTable" width="100%" cellspacing="0">
	              	<thead>
	                	<tr>
	                		<td>No.</td>
	                		<td>From</td>
	                		<td>To</td>
	                		<td>Exchange</td>
	                		<td>Note</td>
	                		<td>Time</td>
	                		<td></td>
	                		<td></td>
	                	</tr>
	              	</thead>
	              	<tfoot>
	                
	              	</tfoot>
	              	<tbody>
						@php $count = 0 @endphp
	            		@foreach($walletTransactions as $key)
						<tr>
							<td>{{ $count += 1 }}</td>
							<td>{{ $key->wallet->name }}</td>
							<td>{{ $key->receiveWallet->name }}</td>
							<td>{{ number_format($key['exchange']) }} đ</td>
							<td>{{ $key['note'] }}</td>
							<td>{{ \Carbon\Carbon::parse($key->date)->format('d-m-Y') }}</td>
							<td class="text-center"><a class="btn btn-primary" href="{{ route('wallet.showEditTransfer',$key['id']) }}" title="Edit">Edit</a></td>
							<td class="text-center"><a class="btn btn-danger" href="{{ route('wallet.deleteTransfer',$key['id']) }}" title="Delete" onclick="return confirm('Do you want to delete this Transfer?');">Delete</a></td>
						</tr>
	            		@endforeach
	              	</tbody>
	            </table>
          	</div>
        </div>
	</div>
</div>

@endsection