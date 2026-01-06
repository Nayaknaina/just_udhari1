@extends('layouts.vendors.app')

@section('content')

<div class="container mt-5">
    <h3>Create Table Structure with Multiple Columns in Each Row</h3>
    <form id="table-form">
        <div id="columns-container">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="column_name[]" placeholder="Column Name">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="column_type[]">
                        <option value="string">String</option>
                        <option value="integer">Integer</option>
                        <option value="float">Float</option>
                        <option value="boolean">Boolean</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="is_amount[]" value="1">
                        Is Amount
                    </label>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-column">Remove</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="add-column">Add Column</button>

        <h4 class="mt-5">Add Row with Multiple Columns</h4>
        <div id="rows-container">
            <!-- Rows will be dynamically added here -->
        </div>
        <button type="button" class="btn btn-primary" id="add-row">Add Row</button>

        <button type="submit" class="btn btn-success mt-4">Save Table</button>
    </form>
</div>

@endsection

@section('javascript')

<script>
    // Add new column definition
    $('#add-column').click(function() {
        $('#columns-container').append(`
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="column_name[]" placeholder="Column Name">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="column_type[]">
                        <option value="string">String</option>
                        <option value="integer">Integer</option>
                        <option value="float">Float</option>
                        <option value="boolean">Boolean</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="is_amount[]" value="1">
                        Is Amount
                    </label>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-column">Remove</button>
                </div>
            </div>
        `);
    });

    // Remove column definition
    $(document).on('click', '.remove-column', function() {
        $(this).closest('.row').remove();
    });

    // Add new row with inputs corresponding to the defined columns
    $('#add-row').click(function() {
        var columns = $('input[name="column_name[]"]').map(function() {
            return $(this).val();
        }).get();

        var rowHtml = '<div class="row mb-3">';
        columns.forEach(function(column, index) {
            rowHtml += `
                <div class="col-md-3">
                    <input type="text" class="form-control" name="row_value_${index}[]" placeholder="${column}">
                </div>
            `;
        });
        rowHtml += `<div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-row">Remove Row</button>
                    </div>
                </div>`;

        $('#rows-container').append(rowHtml);
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('.row').remove();
    });

    // Handle form submission
    $('#table-form').submit(function(e) {
        e.preventDefault();

        // Create an array to hold the table structure
        var tableStructure = [];

        // Get all column names, types, and "Is Amount" checkboxes
        $('input[name="column_name[]"]').each(function(index) {
            var columnName = $(this).val();
            var columnType = $('select[name="column_type[]"]').eq(index).val();
            var isAmount = $('input[name="is_amount[]"]').eq(index).is(':checked');

            // Add column details to the structure array
            tableStructure.push({
                column_name: columnName,
                column_type: columnType,
                is_amount: isAmount // Track if this column is marked as amount
            });
        });

        // Create an array to hold the rows data
        var tableRows = [];
        $('#rows-container .row').each(function() {
            var rowData = [];
            $(this).find('input[type="text"]').each(function() {
                rowData.push($(this).val());
            });
            tableRows.push(rowData);
        });

        // Convert table structure and rows to JSON
        var tableDataJson = JSON.stringify({
            structure: tableStructure,
            rows: tableRows
        });

        // Send the JSON to Laravel via AJAX
        $.ajax({
            url: '{{ route("table.store") }}', // Define your route here
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Laravel CSRF token
                table_data: tableDataJson
            },
            success: function(response) {
                alert('Table structure and data saved successfully!');
            }
        });
    });
</script>

@endsection


