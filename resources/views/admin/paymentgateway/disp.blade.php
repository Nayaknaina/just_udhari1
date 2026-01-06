
<table  class="table table-bordered table-hover">
    <thead class = "bg-info">
        <tr>
            <th class="text-center">S.N.</th>
            <th  class="text-center"> Gateway </th> 
            <th  class="text-center"> URLs</th>
            <th  class="text-center">Parameters</th> 
            <th  class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
    @if($tamplates->count() > 0)
    @foreach($tamplates as $tk=>$tmplt)
        <tr>
            <td class="text-center">{{ $tamplates->firstItem() + $tk }}</td>
            <td  class="text-center">
                <label>
                    <img src="{{ asset("{$tmplt->icon}")}}" class="img-responsive img-thumbnail" style="height:100px;"><br>
                    <span>{{ $tmplt->name }}</span>
                </label>
            </td> 
            <td class="text-center">
                <ul style="list-style:none;">
                    <li><u><b>Testing</b></u></li>
                    <li>{{ $tmplt->test_url }}</li>
                    <li><u><b>Production</b></u></li>
                    <li>{{ $tmplt->prod_url }}</li>
                </ul>
                
            </td> 
            @php $params = explode(",",$tmplt->parameter_list);$num=1 @endphp 
            <td class="text-center">
                @if(count($params) > 0)
                @foreach($params as $pk=>$param)
                    @php 
                    if($num > 3){
                        echo "<hr class='m-2 p-0'>";
                        $num = 0;
                    }else{
                        $num++;
                    }
                    @endphp
                    <div style="display:inline;border:1px solid lightgray;padding:5px;margin:2px;">
                        {{ $param }}
                    </div>
                @endforeach
                @endif
            </td>
            <td class="text-center"> 
                
                <form class="delete_tamplate" action="{{route('paymentgateway.destroy',$tmplt->id)}}" >
                    <a href="{{route('paymentgateway.edit',$tmplt->id)}}" class="btn btn-outline-info btn-sm"> <i class="fa fa-edit"></i></a>
                    @csrf
                    @method('delete')
                    <button type="submit" name="tamplate" value="delete" class="btn btn-outline-danger btn-sm"><i class="fa fa-times"></i></button>
                </form>
            </td>
        </tr>

    @endforeach
    @else
    <tr><td class="text-center text-danger" colspan="5">No Record Found !</td></tr>
    @endif
    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $tamplates,'type'=>1])

<script>
    $('.delete_tamplate').submit(function(e){
        e.preventDefault();
        const tr = $(this).parent('td').parent('tr');
        var formAction = $(this).attr('action') ;
        var formData = new FormData(this) ;
        $.ajax({
            url:formAction,
            type:'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                tr.remove();
                toastr.success(response.success);
            },
            error:function(response){
                toastr.error(response.error) ;
            }
        });
    })
</script>