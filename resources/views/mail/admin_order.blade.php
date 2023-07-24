<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Email Send From HydraK Order</h1>
    <h1>{{ $role == 'admin' ? 'Order Admin Email' : 'Order Email'}}</h1>
    <tr>
        <th>Customer Name : {{$order->user->name}} </th> <br>
        <th>Customer Email : {{$order->user->email}}</th> <br>
        <th>Customer Phone : {{$order->user->phone}}</th> <br>
        <th>Customer Note : {{$order->note}}</th>
    </tr>
    <table>
        <tr>
            <th>STT</th>
            <th>Product Name </th>
            <th>Product Price</th>
            <th>Product Quantity</th>
            <th>Product Total</th>
        </tr>
        @php $total = 0; @endphp
        @foreach ( $order->order_items as $item)
            <tr border="1">
                <td>{{$loop->iteration}}</td>
                <td>{{ $item->name}}</td>
                <td>{{ $item->price}}</td>
                <td>{{ $item->qty}}</td>
                <td>{{ number_format($item->price*$item->qty,2)}}</td>
            </tr>
            @php $total +=  $item->price*$item->qty  @endphp
        @endforeach

        <td>Total : </td>
        <td colspan="4">{{number_format($total,2)}}</td>

    </table>
</body>
</html>
