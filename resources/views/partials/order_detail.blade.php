@foreach($products as $index => $itm)
<tr>
    <td>{{$index+1}}</td>
    <td>{{$itm->name}}</td>
    <td>{{$itm->quantity}}</td>
    <td>{{$itm->note}}</td>
    <td>{{number_format($itm->total_amount)}}<sup>đ</sup></td>
</tr>
@endforeach