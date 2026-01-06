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
            <div class="col-md-8">
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

                <div class="col-lg-6 form-group ">
                    <label for=""> Schemes </label>
                    <select name="scheme_id" id = "scheme_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($schemes as $scheme )
                            <option value="{{ $scheme->id }}" {{ ($scheme->ss_status=='0')?'disabled':''; }}>{{ $scheme->scheme_head }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 form-group ">
                    <label for=""> Group </label>
                    <select name="group_id" id = "group_id" class="form-control select2">
                        <option value = "">Select</option>
                    </select>
                </div>

                <div class="col-lg-6 form-group ">
                    <label for=""> Customer </label>
                    <select name="customer_id" id = "customer_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($customers as $customer )
                            <option value="{{ $customer->id }}" data-name = "{{ $customer->custo_full_name }}" >{{ $customer->custo_full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 form-group ">
                    <label for=""> Add on scheme multiple </label>
                    <input type ="number" name="customer_multiple" id = "customer_multiple" class="form-control form-group" placeholder="Customer Name in Scheme" min = "1" value = "1" >
                </div>

                <div class="col-lg-12 form-group ">
                    <div id="customer_names_container" class="row">
                        <div class="col-lg-6 form-group">
                            <label for="">Customer Name</label>
                            <input type="text" name="customer_name[]" class="form-control form-group customer_name" placeholder="Customer Name in Scheme" >
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="">Token Amount</label>
                            <input type="text" name="token_amt[]" class="form-control form-group token_amt" placeholder="Token Amount" >
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="">Assign ID</label>
                            <input type="text" name="assign_id[]" class="form-control form-group assign_id" placeholder="Assign ID" >
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

    document.getElementById('customer_multiple').addEventListener('input', function () {
        var container = document.getElementById('customer_names_container');
        var multiple = parseInt(this.value) || 1;

        // Clear existing customer name fields
        container.innerHTML = '';
        for (var i = 0; i < multiple; i++) {
            // Create Customer Name Field
            var customerDiv = document.createElement('div');
            customerDiv.className = 'col-lg-6 form-group';
            
            var customerLabel = document.createElement('label');
            customerLabel.innerHTML = "Customer Name";
            
            var customerInput = document.createElement('input');
            customerInput.type = 'text';
            customerInput.name = 'customer_name[]';
            customerInput.className = 'form-control form-group customer_name';
            customerInput.placeholder = 'Customer Name in Scheme';
            
            customerDiv.appendChild(customerLabel);
            customerDiv.appendChild(customerInput);
            container.appendChild(customerDiv);
            
            // Create Token Amount Field
            var tokenDiv = document.createElement('div');
            tokenDiv.className = 'col-lg-3 form-group';
            
            var tokenLabel = document.createElement('label');
            tokenLabel.innerHTML = "Token Amount";
            
            var tokenInput = document.createElement('input');
            tokenInput.type = 'text';
            tokenInput.name = 'token_amt[]';
            tokenInput.className = 'form-control form-group token_amt';
            tokenInput.placeholder = 'Token Amount';

            tokenDiv.appendChild(tokenLabel);
            tokenDiv.appendChild(tokenInput);
            container.appendChild(tokenDiv);

            // Create Assign ID Field
            var assignDiv = document.createElement('div');
            assignDiv.className = 'col-lg-3 form-group';
            
            var assignLabel = document.createElement('label');
            assignLabel.innerHTML = "Assign ID";
            
            var assignInput = document.createElement('input');
            assignInput.type = 'text';
            assignInput.name = 'assign_id[]';
            assignInput.className = 'form-control form-group assign_id';
            assignInput.placeholder = 'Assign ID';
            
            assignDiv.appendChild(assignLabel);
            assignDiv.appendChild(assignInput);
            container.appendChild(assignDiv);
        }
    });

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
                    $.each(response, function(index, group) {
                        $('#group_id').append('<option value="' + group.id + '">' + group.group_name + '</option>');
                    });
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

