@extends('layouts.vendors.app')

@section('content')


@php

$data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

@endphp

<x-page-component :data=$data />

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"><x-back-button />  Edit </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('purchases.update',$purchase->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')

          <div class="row">

              <div class="col-lg-3">
                  <label for=""> Supplier </label>
                  <select name = "supplier" class = "form-control select2">
                      <option value="">Select</option>
                      @foreach (supplier() as $supplier )
                      <option value = "{{ $supplier->id }}" @if($purchase->supplier_id==$supplier->id ) selected @endif >{{ $supplier->supplier_name }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="col-lg-3">
                  <label for=""> Bill No </label>
                  <input type="text" name="bill_no" class="form-control form-group" placeholder="Enter Bill No " value = "{{ $purchase->bill_no }}">
              </div>

              <div class="col-lg-3">
                  <label for=""> Bill Date </label>
                  <input type="date" name="bill_date" class="form-control form-group" placeholder="Enter Bill Date" value = "{{ $purchase->bill_date }}">
              </div>

              <div class="col-lg-3">
                  <label for=""> Batch No </label>
                  <input type="text" name = "batch_no" class="form-control form-group" placeholder="Enter Batch No " value = "{{ $purchase->batch_no }}">
              </div>

          </div>

          <hr>

          <div class="row small_input ">

              <div class="col-lg-12">
                  <div class="form-group">
                     <label for=""> Stock Details - </label>
                  </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                    <label for="rate">Rate</label>
                    <input type="number" class="form-control" id="rate" name="rate" min="1" step="any" placeholder="Enter Rate" value="{{ isset($purchase->stocks[0]) ? $purchase->stocks[0]->rate : '' }}" >
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="counterName">Counter Name</label>
                    <select name="counter_name" class="form-control select2">
                        <option value="">Select</option>
                        @foreach (counters() as $counter)
                            <option value="{{ $counter->id }}" {{ isset($purchase->stocks[0]) && $purchase->stocks[0]->counter_id == $counter->id ? 'selected' : '' }}>{{ $counter->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="boxNo">Box No</label>
                    <input type="text" name="box_no" class="form-control" id="boxNo" placeholder="Enter Box No" value="{{ isset($purchase->stocks[0]) ? $purchase->stocks[0]->box_no : '' }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="mfgDate">MFG Date</label>
                    <input type="date" name="mfg_date" class="form-control" id="mfgDate" placeholder="Select MFG Date" value="{{ isset($purchase->stocks[0]) ? $purchase->stocks[0]->mfg_date : '' }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="metals">Metals</label>
                    <select name="metals" class="form-control select2" id="metals" placeholder="Select Metal">
                        <option value="">Select</option>
                        @foreach (categories(1) as $category)
                            <option value="{{ $category->id }}"  {{ isset($purchase->stocks[0]) &&  $purchase->stocks['0']->categories->contains($category->id) ? 'selected' : ''  }} >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="collections">Collections</label>
                    <select name="collections" class="form-control select2" id="collections" placeholder="Select Collection">
                        <option value="">Select</option>
                        @foreach (categories(2) as $category)
                            <option value="{{ $category->id }}" {{ isset($purchase->stocks[0]) &&  $purchase->stocks['0']->categories->contains($category->id) ? 'selected' : ''  }}
                            >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" class="form-control select2" id="category" placeholder="Select Category">
                        <option value="">Select</option>
                        @foreach (categories(3) as $category)
                            <option value="{{ $category->id }}"  {{ isset($purchase->stocks[0]) &&  $purchase->stocks['0']->categories->contains($category->id) ? 'selected' : ''  }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

          </div>

          <hr>

          <div class="row">

              <div class="col-lg-12">
                  <table class = "table table-responsive table-bordered">
                      <thead class = "bg-info">
                          <tr>
                              <th> SN </th>
                              <th> purchase Name </th>
                              <th> Quantity </th>
                              <th> Carat </th>
                              <th> Gross Weight </th>
                              <th> Net Weight </th>
                              <th> Purity </th>
                              <th> Watages </th>
                              <th> Fine Purity </th>
                              <th> Fine Weight </th>
                              <th> Labour Charge </th>
                              <th> Amount </th>
                          </tr>
                      </thead>
                      <tbody id="tableBody" >
                        @foreach ($purchase->stocks as $key => $stock)
                        <tr>
                            <td class="sn-box">
                                <input type="hidden" name="stock_id[]" value="{{ $stock->id }}">
                                <span class="sn-number">{{ $key+1 }}</span>
                                <button type = "button" class = "btn btn-danger btn-delete">X</button>
                            </td>
                            <td>
                                <input type="text" class="tb_input" name="product_name[]" id="product_name_{{ $loop->index + 1 }}" placeholder="purchase Name" value="{{ $stock->name }}">
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="quantity[]" id="quantity_{{ $loop->index + 1 }}" placeholder="Quantity" oninput="calculate(this)" value="{{ $stock->quantity }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="carat[]" id="carat_{{ $loop->index + 1 }}" placeholder="Carat" oninput="calculate(this)" value="{{ $stock->carat }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="gross_weight[]" id="grossWeight_{{ $loop->index + 1 }}" placeholder="Gross Weight" oninput="calculate(this)" value="{{ $stock->gross_weight }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="net_weight[]" id="netWeight_{{ $loop->index + 1 }}" placeholder="Net Weight" value="{{ $stock->net_weight }}"   oninput="calculate(this)"  min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="purity[]" id="purity_{{ $loop->index + 1 }}" placeholder="Purity" oninput="calculate(this)" value="{{ $stock->purity }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="wastage[]" id="wastage_{{ $loop->index + 1 }}" placeholder="Wastage" oninput="calculate(this)" value="{{ $stock->wastage }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="fine_purity[]" id="finePurity_{{ $loop->index + 1 }}" placeholder="Fine Purity" readonly value="{{ $stock->fine_purity }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="fine_weight[]" id="fineWeight_{{ $loop->index + 1 }}" placeholder="Fine Weight" readonly value="{{ $stock->fine_weight }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="labour_charge[]" id="labourCharge_{{ $loop->index + 1 }}" placeholder="Labour Charge" oninput="calculate(this)" value="{{ $stock->labour_charge }}"   min = "0" step = "any" >
                            </td>
                            <td>
                                <input type="number" class="tb_input" name="amount[]" id="amount_{{ $loop->index + 1 }}" placeholder="Amount" readonly value="{{ $stock->amount }}"   min = "0" step = "any" >
                            </td>
                        </tr>

                    @endforeach

                      </tbody>
                  </table>

                  <button type = "button" id = "addMoreBtn" class = "btn btn-primary" > Add More </button>

              </div>

              <div class = "col-lg-6"></div>

              <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Total Quantity</label>
                            <input class="form-control" name="totalquantity" id="totalQuantity" placeholder="Total Quantity" readonly value="{{ $purchase->total_quantity }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Total Weight</label>
                            <input class="form-control" name="totalweight" id="totalWeight" placeholder="Total Weight" readonly value="{{ $purchase->total_weight }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Total Fine Weight</label>
                            <input class="form-control" name="totalfineweight" id="totalFineWeight" placeholder="Total Fine Weight" readonly value="{{ $purchase->total_fine_weight }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input class="form-control" name="totalamount" id="totalAmount" placeholder="Total Amount" readonly value="{{ $purchase->total_amount }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Pay Amount</label>
                            <input class="form-control" name="payamount" id="payAmount" placeholder="Pay Amount" value="{{ $purchase->pay_amount }}">
                        </div>
                    </div>
                </div>
            </div>

          </div>

          <div class="row">
              <div class="col-12 text-center my-3 ">

                  <button type = "submit" class="btn btn-danger"> Submit </button>
                  <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                  <input type = "hidden" id = "deletedStocks" name = "deleted_stocks" value = "">

              </div>
          </div>

          </form>

          </div>
          </div>

          </div>
          <!-- /.row -->
      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
</section>

@endsection

@section('javascript')

@include('vendors.purchases.content.js')

<script>

  $(document).ready(function() {
    $('#submitForm').submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    var formAction = $(this).attr('action') ;
    var formData = new FormData(this) ;

    // Send AJAX request

    $.ajax({
        url: formAction,
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function() {
        $('.btn').prop("disabled", true);
        $('#loader').removeClass('hidden');
        },
        success: function(response) {
        // Handle successful update
        success_sweettoatr(response.success);
        window.open("{{route('purchases.index')}}", '_self');
        },
        error: function(response) {
            $('.btn-outline-danger').prop("disabled", false);
            $('.btn').prop("disabled", false);
            $('#loader').addClass('hidden');
            var errors = response.responseJSON.errors;
        if (response.status === 422) {
            $('input').removeClass('is-invalid');

            $.each(errors, function(field, messages) {
                // Extract field name and index (e.g., field = "gross_weight.0")
                var matches = field.match(/^([a-zA-Z_]+)\.(\d+)$/);

                if (matches) {

                    var fieldName = matches[1] + '[]';
                    var index = matches[2];
                    var $field = $('[name="' + fieldName + '"]').eq(index);

                } else {

                    var $field = $('[name="' + field + '"]');

                }

                $field.addClass('is-invalid') ;
                toastr.error(messages[0]) ;
                // $field.after('<div class="invalid-feedback">' + messages[0] + '</div>') ;

            }) ;
        } else { toastr.error(errors) ; }
        }
    });
    });
  });

</script>

@endsection

