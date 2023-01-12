@php
$total = 0;
@endphp

<x-app-layout>
  @section('title', 'Transaction Details')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item">
          <a href="/transactions">Transactions</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Detail Transaction</li>
      </ol>
    </nav>
  </x-slot>

  {{-- flash message --}}
  @if (session('message'))
  <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="mdi mdi-check-circle me-2" style="font-size: 20px"></i>
    <div>
      {{session('message')}}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  {{-- end flash message --}}

  {{-- content --}}
  <div class="card w-100 shadow border-0">
    <div class="card-body">
      <h3 class="text-primary">Transaction Details</h3>
      <table class="table mt-4">
        <tr>
          <th class="bg-primary text-white ">No</th>
          <th class="bg-primary text-white ">Name</th>
          <th class="bg-primary text-white ">Amount</th>
          <th class="bg-primary text-white ">Unit Price</th>
          <th class="bg-primary text-white ">Total Price</th>
        </tr>
        @foreach ($transaction->products as $i => $product)
        <tr>
          <th>{{$i + 1}}</th>
          <td>{{$product->name}}</td>
          <td>{{$product->pivot->amount}}</td>
          <td>{{'Rp. ' . number_format($product->price, 2, ',', '.')}}</td>
          <td>{{'Rp. ' . number_format($product->price * $product->pivot->amount, 2, ',', '.')}}</td>
        </tr>
        @php
        $total += ($product->price * $product->pivot->amount);
        @endphp
        @endforeach
        <tr>
          <td colspan="3" class="border-bottom-0"></td>
          <th class="bg-primary text-white">Total</th>
          <th class="bg-primary text-white">{{'Rp. ' . number_format($total, 2, ',', '.')}}</th>
        </tr>
      </table>

      <table>
        <tr>
          <th class="px-2 py-1">Transaction Code</th>
          <td class="px-2 py-1">: {{$transaction->transaction_code}}</td>
        </tr>
        <tr>
          <th class="px-2 py-1">Created At</th>
          <td class="px-2 py-1">: {{$transaction->created_at->format('d M Y')}}</td>
        </tr>
        @if ($transaction->supplier)
        <tr>
          <th class="px-2 py-1">Supplier</th>
          <td class="px-2 py-1">: {{$transaction->supplier->name}}</td>
        </tr>
        @endif
        <tr>
          <th class="px-2 py-1">Status</th>
          <td class="px-2 py-1">: {{$transaction->status ? 'Incoming Transaction' : 'Outgoing Transaction'}}</td>
        </tr>
        <tr>
          <th class="px-2 py-1">Note</th>
          <td class="px-2 py-1">: {{$transaction->note}}</td>
        </tr>
      </table>
      <div class="text-end">
        @role(['super-admin', 'admin'])
        <a href="/transactions/update/{{$transaction->id}}" class="btn btn-success">
          <i class="mdi mdi-pencil"></i>
          Update
        </a>
        @endrole
        <a href="/transactions/download/{{$transaction->id}}" class="btn btn-primary">
          <i class="mdi mdi-file-pdf-box"></i>
          Print
        </a>
      </div>
    </div>
  </div>
  {{-- end content --}}
</x-app-layout>