  @extends('layouts.vendors.app')

  @section('css')

<style>

    .sn-box {

        display: flex;
        justify-content: space-between;
        align-items: center;

    }

    .sn-number {

        margin-right: 10px;

    }

    .table th {

        border-bottom-width: 2px;
        font-size: 14px;
        padding: 5px;
        text-align: center;

    }

    .table .select2-container .select2-selection--single {

        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        border-radius: 5px !important;
        user-select: none;
        -webkit-user-select: none;
        /* border-radius: 0 !important; */
        padding: 2px;

    }

    .table .select2-container--default .select2-selection--single {

        background-color: #fff;
        border: 1px solid #aaaaaa8f;
        border-radius: 4px;

    }

</style>

@endsection

  @section('content')

  <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Product</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
            </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
  </section>

  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">

            <div class="card-header">
            <h3 class="card-title">Create Product</h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('products.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-3">
                    <label for=""> Supplier </label>
                    <select name="" class = "form-control select2">
                        <option value="">Select</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill No </label>
                    <input type="text" name="Product_name" class="form-control form-group" placeholder="Enter Product Name">
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill Date </label>
                    <input type="date" name="Product_name" class="form-control form-group" placeholder="Enter Product Name">
                </div>

                <div class="col-lg-3">
                    <label for=""> Batch No </label>
                    <input type="text" name="Product_name" class="form-control form-group" placeholder="Enter Product Name">
                </div>

            </div>

            <hr>

            <div class="row dss ">

                <div class="col-lg-12">
                    <div class="form-group">
                       <label for=""> Stock Details - </label> 
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Product Name</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Batch No</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Counter Name</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Box No</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">MFG Date</label>
                        <input type="date" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Metals</label>
                        <select name="" class = "form-control select2">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Collections</label>
                        <select name="" class = "form-control select2">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="" class = "form-control select2">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Quantity</label>
                        <input type="number" class="form-control" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Box No</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">
                    <table class = "table table-responsive table-bordered">
                        <thead class = "bg-info">
                            <tr>
                                <th> SN </th>
                                <th> Product Name </th>
                                <th> Batch No </th>
                                <th> Counter Name </th>
                                <th> Box No </th>
                                <th> Mfg date </th>
                                <th> Metals </th>
                                <th> Collection </th>
                                <th> Category </th>
                                <th> Quantity </th>
                                <th> Gross Weight </th>
                                <th> Packet Weight </th>
                                <th> Net Weight </th>
                                <th> Tag Weight </th>
                                <th> Purity </th>
                                <th> Watages </th>
                                <th> Fine Purity </th>
                                <th> Fine Weight </th>
                                <th> Labour Charge </th>
                                <th> Amount </th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" >
                            <td class="sn-box">
                                <span class="sn-number">1</span>
                            </td>
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td>
                                 <select name="" class = "form-control select2" style = "width: 100px;">
                                 <option value="">Select</option>
                                 </select>
                             </td>
                            <td>
                                 <select name="" class = "form-control select2" style = "width: 100px;">
                                 <option value="">Select</option>
                                 </select>
                             </td>
                            <td>
                                 <select name="" class = "form-control select2" style = "width: 100px;">
                                 <option value="">Select</option>
                                 </select>
                             </td>
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                            <td> <input type="text" class = "tb_input" > </td> 
                        </tbody>
                    </table>

                    <button type = "button" id = "addMoreBtn" class = "btn btn-primary" > Add More </button>

                </div>

                <div class = "col-lg-6"></div>

                <div class = "col-lg-6">
                    <div class = "row">
                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label> Total Quantity </label>
                                <input class = "form-control">
                            </div>
                        </div>

                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label> Total Weight </label>
                                <input class = "form-control">
                            </div>
                        </div>

                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label> Total Fine Weight </label>
                                <input class = "form-control">
                            </div>
                        </div>

                        <div class = "col-lg-6">
                            <div class = "form-group">
                                <label> Total Amount </label>
                                <input class = "form-control">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger"> Submit </button> 
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
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

  <script type="text/javascript">

    $(document).ready(function() { 

        function updateRowNumbers() {
            $('#tableBody tr').each(function(index, tr) {
                $(tr).find('.sn-number').text(index + 1);
            });
        }

        $('#addMoreBtn').click(function() {

            var rowCount = $('#tableBody tr').length + 1;
            var newRow = `<tr>
                <td class="sn-box">
                    <span class="sn-number">` + rowCount + `</span>
                    <button class="btn btn-danger btn-delete"> X </button>
                </td>
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td>
                    <select class="form-control select2" style="width: 100px;">
                        <option value="">Select</option>
                    </select>
                </td>
                <td>
                    <select class="form-control select2" style="width: 100px;">
                        <option value="">Select</option>
                    </select>
                </td>
                <td>
                    <select class="form-control select2" style="width: 100px;">
                        <option value="">Select</option>
                    </select>
                </td>
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
                <td> <input type="text" class="tb_input"> </td> 
            </tr>`;
            $('#tableBody').append(newRow);
            $('.select2').select2(); 
            updateRowNumbers();

        }) ;

        $('#tableBody').on('click', '.btn-delete', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

    }) ;

</script>

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
        toastr.success(response.success);
        window.open("{{route('products.index')}}", '_self');
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

  </script>

  @endsection

