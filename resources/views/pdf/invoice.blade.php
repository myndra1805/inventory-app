@php
$total = 0;
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title>Detail Transaction</title>
</head>

<body>
  <table class="table table-bordered">
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
      <td colspan="3" class="border-0"></td>
      <th class="bg-primary text-white">Total</th>
      <th class="bg-primary text-white">{{'Rp. ' . number_format($total, 2, ',', '.')}}</th>
    </tr>
  </table>

  <table>
    <tr>
      <th class="pe-2 ps-0 py-1">Transaction Code</th>
      <td class="px-2 py-1">: {{$transaction->transaction_code}}</td>
    </tr>
    <tr>
      <th class="pe-2 ps-0 py-1">Created At</th>
      <td class="px-2 py-1">: {{$transaction->created_at->format('d M Y')}}</td>
    </tr>
    @if ($transaction->supplier)
    <tr>
      <th class="pe-2 ps-0 py-1">Supplier</th>
      <td class="px-2 py-1">: {{$transaction->supplier->name}}</td>
    </tr>
    @endif
    <tr>
      <th class="pe-2 ps-0 py-1">Status</th>
      <td class="px-2 py-1">: {{$transaction->status ? 'Incoming Transaction' : 'Outgoing Transaction'}}</td>
    </tr>
    <tr>
      <th class="pe-2 ps-0 py-1">Note</th>
      <td class="px-2 py-1">: {{$transaction->note}}</td>
    </tr>
  </table>
</body>

</html>