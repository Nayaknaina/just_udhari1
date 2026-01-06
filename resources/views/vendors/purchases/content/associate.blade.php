<div class="container">
    <form action="#" method="post" class="form-inline">
        <input type="hidden" name="row" value="{{ $request->row_num }}">
        <div class="row ele_block" id="element_block_default">
            <input type="text" name="assoc[metal][]" value="" class="form-control col-md-5 p-2" placeholder="Element Name">
            <input type="text" name="assoc[caret][]" value="" class="form-control  col-md-2 p-2" placeholder="Caret">
            <input type="text" name="assoc[quant][]" value="" class="form-control  col-md-2 p-2" placeholder="Quantity">
            <input type="text" name="assoc[cost][]" value="" class="form-control  col-md-3 p-2" placeholder="Total Cost">
            <a href="javascript:void(null)"  class="ele_plus over_button" ></a>
        </div>
        <div class="col-12 text-center mt-3 pt-3" style="border-top:1px solid lightgray;">
            <button type="submit" name="add" value="assoc" class="btn btn-sm btn-primary">Done ?</button>
        </div>
    </form>
</div>