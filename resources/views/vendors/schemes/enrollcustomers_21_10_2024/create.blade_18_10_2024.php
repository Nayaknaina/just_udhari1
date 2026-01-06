  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Customer Enroll',[['title' => 'Schemes']] ) ;

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
            <h3 class="card-title"><x-back-button /> Create </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('enrollcustomer.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-4 form-group ">
                    <label for="scheme_id"> Schemes </label>
                    <select name="scheme_id" id = "scheme_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($schemes as $scheme )
                            <option value="{{ $scheme->id }}" {{ ($scheme->ss_status=='0')?'disabled':''; }}>{{ $scheme->scheme_head }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 form-group ">
                    <label for="group_id"> Group </label>
                    <select name="group_id" id = "group_id" class="form-control select2">
                        <option value = "">Select</option>
                    </select>
                </div>

                <div class="col-lg-3 form-group ">
                    <label for="customer_id"> Customer </label>
                    <select name="customer_id" id = "customer_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($customers as $customer )
                            <option value="{{ $customer->id }}" data-name = "{{ $customer->custo_full_name }}" >{{ $customer->custo_full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 form-group ">
                    <label for="customer_multiple"> Add on scheme multiple </label>
                    <input type ="number" name="customer_multiple" id = "customer_multiple" class="form-control form-group" placeholder="Customer Name in Scheme" min = "1" value = "1" >
                </div>

                <div class="col-lg-12 form-group " id="customer_choice_container">
                    <div id="customer_names_container" class="row">
                        <div class="col-lg-4 form-group" id="">
                            <label for="">Customer Name</label>
                            <input type="text" name="customer_name[]" class="form-control form-group customer_name" placeholder="Customer Name in Scheme" required>
                        </div>
                        <div class="col-lg-2 form-group">
                            <label for="">Assign ID</label>
                            <input type="text" name="assign_id[]" class="form-control form-group assign_id" placeholder="Assign ID" required>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="">Token Amount</label>
                            <input type="text" name="token_amt[]" class="form-control form-group token_amt" placeholder="Token Amount" required>
                        </div>
                         
                        <div class="col-lg-3 form-group">
                            <label for="emi_amt" id="emi_amt_label" class="emi_amt_label">EMI </label>
                            <input type="text" id="emi_amt" name="emi_amt[]" class="form-control form-group emi_amt" placeholder="Choosed EMI Amount" required>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger"> Submit </button> 
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
                // $('.btn').prop("disabled", true);
                // $('#loader').removeClass('hidden');
                },
                success: function(response) {
                // Handle successful update
                success_sweettoatr(response.success);
                window.open("{{route('enrollcustomer.index')}}", '_self');
                },
                error: function(response) {
                if (response.status === 422) {
                    var errors = response.responseJSON.errors;
                    $('input').removeClass('is-invalid');
                    $('.btn-outline-danger').prop("disabled", false);
                    $('.btn').prop("disabled", false);
                    $('#loader').addClass('hidden');
                    $.each(errors, function(field, messages) {
                    var $field = $('[name="' + field + '"]');
                    toastr.error(messages[0]) ;
                    $field.addClass('is-invalid') ;
                    });
                } else {
                    console.log(response.responseText);
                }
                }
            });
        });
    });

    $(document).ready(function() {
        $('#customer_id').on('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            // Get the data-name attribute value
            var customerName = selectedOption.getAttribute('data-name');

            // Get all elements with the class 'customer_name'
            var customerNameFields = document.getElementsByClassName('customer_name');

            // Loop through all customer_name fields and set the value
            for (var i = 0; i < customerNameFields.length; i++) {
                customerNameFields[i].value = customerName || '';
            }
        });
    });

    $('#customer_multiple').on('input',function(){
        var multiple = $(this).val() || 1;
        $(document).find('.customer_names_container').remove();
        for (var i = 2; i <= multiple; i++) {
            var ele = $('#customer_names_container').clone();
            ele.removeAttr('id').addClass('customer_names_container');
            ele.appendTo("#customer_choice_container");
        }
    })
   

    $(document).ready(function() {
     
    $('#scheme_id').on('change', function() {
        var schemeId = $(this).val(); // Get the selected scheme_id
        
        // Clear previous options from the group_id dropdown
        $('#group_id').empty().append('<option value="">Select</option>');

        if (schemeId) {
            $.ajax({
                url: '{{ route("shopschemes.getgroup") }}',
                type: 'GET',
                data: { scheme_id: schemeId },
                success: function(response) {
                    // Populate the group_id dropdown with the received data
                    $.each(response[0], function(index, group) {
                        $('#group_id').append('<option value="' + group.id + '">' + group.group_name + '</option>');
                    });
                    const scheme = response[1];
                    var emi_sugg = '';
                    if(scheme.emi_range_start == scheme.emi_range_end){
                        $(document).find('.emi_range').remove();
                        $(".emi_amt").attr('readonly',true);
                    }else{
                        emi_sugg = '<small class="emi_range text-danger"><b> ( Range '+scheme.emi_range_start+' to '+scheme.emi_range_end+' )</b></small>';
                        $(".emi_amt_label").append(emi_sugg);
                        $(".emi_amt").attr('readonly',false);
                    }
                    $(".emi_amt").val(scheme.emi_range_start);
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while fetching groups.');
                }
            });
        }
    });
});

</script>

@endsection

