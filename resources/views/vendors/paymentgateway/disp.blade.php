@if($gateways->count()>0)
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr>
            <th class="text-center">S.N.</th>
            <th  class="text-center"> Gateway </th> 
            <th  class="text-center"> URLs</th>
            <th  class="text-center">Parameters</th> 
            <th  class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($gateways as $tk=>$tmplt)
        <tr>
            <td class="text-center">{{ $gateways->firstItem() + $tk }}</td>
            <td  class="text-center">
                <label>
                    <img src="{{ asset("{$tmplt->origin->icon}")}}" class="img-responsive img-thumbnail" style="height:100px;"><br>
                    <span>{{ $tmplt->gateway_name }}</span>
                </label>
            </td> 
            <td class="text-center">
                <ul style="list-style:none;">
                    <li><u><b>Testing</b></u></li>
                    <li>{{ $tmplt->origin->test_url }}</li>
                    <li><u><b>Production</b></u></li>
                    <li>{{ $tmplt->origin->prod_url }}</li>
                </ul>
                
            </td> 
            @php $params_list = json_decode($tmplt->parameter,true);  @endphp 
            <td>
                @if(count($params_list)>0)
                <ul style="list-style:none;padding:0;">
                @foreach($params_list as $pk=>$param)
                    <li><b>{{ $pk }}</b></li>
                    <li class="col-12 p-1 mb-1s" style="border:1px solid lightgray;background:white;">{{ ($param!="")?$param:'NA'; }}</li>
                @endforeach
                <ul>
                @else 
                No Parameters !
                @endif
            </td>
            <td class="text-center">
                <ul style="list-style:none;padding:0" > 
                    <li class="m-2 text-center">
                        <a href="{{route('mygateway.edit',$tmplt->id)}}" class="btn btn-outline-info btn-sm"> <i class="fa fa-wrench"></i>SetUp</a>
                    </li>
                    <li>
                    <a href="{{route('mygateway.create',['id'=>$tmplt->id])}}" class="btn btn-outline-secondary btn-sm status_btn {{ ($tmplt->status=='1')?'active':''; }}" >{{ ($tmplt->status=='1')?'Online':'Offline'; }}</a>
                    </li>
                </ul>
            </td>
        </tr>

    @endforeach

    </tbody>
  </table>
<style>

/**The Switch With Anchor */
.status_btn{
  position: relative;
  display: inline-block;
  background:lightgray;
}

.status_btn:before {
  position: absolute;
  content: "";
  height: 100%;
  width: 10px;
  left: 0px;
  bottom: 0px;
  background-color: white;
  border:1px solid lightgray;
  -webkit-transition: .4s;
  transition: .4s;
}
a.status_btn.active{
    background-color: green!important;
}
a.status_btn.active:before{
  -webkit-transform: translateX(500%);
  -ms-transform: translateX(500%);
  transform: translateX(500%);
  right:0;
}
/**END The Switch With Anchor */
</style>
<div class="col-12">
  @include('layouts.theme.datatable.pagination', ['paginator' => $gateways,'type'=>1])
  </div>
  @else 
    <div class="alert alert-outline-danger text-center text-danger">No Record !</div>
  @endif

