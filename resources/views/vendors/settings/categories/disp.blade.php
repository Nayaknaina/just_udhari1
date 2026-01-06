
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr> <th style="">S.N.</th>
        <th style="width:10%">Name</th>
        </tr>
    </thead>
    <tbody>

    @foreach($categories as $key=>$row)

        <tr>
            <td>{{ $categories->firstItem() + $key }}</td>
            <td>{{$row->name }}</td>
        </tr>

    @endforeach

    </tbody>
  </table>
<div class="col-12">
  @include('layouts.theme.datatable.pagination', ['paginator' => $categories,'type'=>1])
</div>

