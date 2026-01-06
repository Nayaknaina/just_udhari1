
<table id="CsTable" class="table table-bordered table-hover">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Name</th> 
        <th style="border:1px solid;width:10%">Quantity</th>
        <th style="border:1px solid;width:10%">Gross Weight</th>
        <th style="border:1px solid;width:10%">Net Weight</th>
        <th style="border:1px solid;width:10%">Purity</th>
        <th style="border:1px solid;width:10%">Fine Weight</th>
        <th style="border:1px solid;width:10%">Rate</th>
        <th style="border:1px solid;width:10%">Amount</th>
        <th style="border:1px solid;width:10%">Ac tion</th>
        </tr>
    </thead>
    <tbody>

    @foreach($stocks as $key=>$row)
        <tr>
            <td>{{ $stocks->firstItem() + $key }}</td>
            <td>{{$row->name }}</td> 
            <td>{{$row->quantity}}</td> 
            <td>{{$row->gross_weight}}</td> 
            <td>{{$row->net_weight}}</td>
            <td>{{$row->purity}}</td>
            <td>{{$row->fine_weight}}</td>
            <td>{{$row->rate}}</td>
            <td>{{$row->amount}}</td>
            <td>
                <div class="d-flex justify-content-between"> 
                <a class="btn btn-outline-success" href="{{route('ecomstocks.edit', $row->id)}}">List on web</a>
                {{-- <form action="{{ route('ecomstocks.destroy',$row->id) }}" method="POST"> 
                @csrf
                @method('DELETE') 
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button> 
                </form>  --}}
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])

