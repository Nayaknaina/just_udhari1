
<div class="row">

    @if($catalogues->count() > 0)
    <style>
        .val{
            float:inline-end;
        }
    </style>
        @foreach($catalogues as $key=>$row)

            <div class="col-lg-3 col-6 p-1">
                <div class="card product-card">
                <div class="dropdown sub_drop_over">
                    <button class="btn btn-outline-secondary dropdown-toggle m-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-caret-square-down"></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class = "dropdown-item text-info" href="{{ route('catalogues.show',$row->id) }}">  
                            <i class="fa fa-object-group" ></i> Gallery {{ $row->catalogeimages->count() }}
                        </a> 
                        <a href="{{route('catalogues.edit', $row->id) }}" class="editButton dropdown-item text-warning"><i class = "fa fa-edit"></i> Edit</a>
                        <a type="button" class="dropdown-item  text-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('catalogues.destroy',$row->id) }}">
                            <li class="fa fa-times"></li> Delete
                        </a>
                    </div>
                </div> 
                <img src="{{ asset("ecom/cataloge/{$row->images}") }}" class ="img-fluid">
                <div class="card-footer p-2">
                    <p class="text-center">{{ $row->name }}</p>
                    <hr class="m-1 p-0">
                    <h6 class="text-center">{{  $row->weight }}grms</h6>
                    @php 
                        $catalogs_cats = $row->categories;
                        $metal = $catalogs_cats->where('category_level',1)->first()->name;
                        $cats = $catalogs_cats->where('category_level',3)->first()->name;
                        $colls = $catalogs_cats->where('category_level',2)->first()->name;
                    @endphp
                    <ul class="row p-0 prop m-0" style="list-style:none;">
                        <li class="col-12 p-0">Metal<b class="val">{{ $metal }}</b></li>
                        <li class="col-12 p-0">Category<b class="val">{{ $cats }}</b></li>
                        <li class="col-12 p-0">Collection<b  class="val">{{ $colls }}</b></li>
                    </ul>
                </div>
                </div>
            </div>

        @endforeach

    @else

    <div class="col-lg-12 mt-5">
        <h3 class = "text-center"> No Items available </h3>
    </div>

    @endif

</div>

  @include('layouts.theme.datatable.pagination', ['paginator' => $catalogues,'type'=>1])

