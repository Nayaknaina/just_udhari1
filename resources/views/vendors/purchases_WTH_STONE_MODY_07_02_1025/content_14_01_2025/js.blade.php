
<script type="text/javascript">

    $(document).ready(function() {

        $('#rate').on('keyup', function() {

            var newRate = parseFloat($(this).val()) || 0 ;

            $('tr').each(function() {

                var row = $(this) ;
                var quantity = parseFloat(row.find('input[id^="quantity_"]').val()) || 0 ;
                var netWeight = parseFloat(row.find('input[id^="netWeight_"]').val()) || 0 ;
                var purity = parseFloat(row.find('input[id^="purity_"]').val()) || 0 ;
                var wastage = parseFloat(row.find('input[id^="wastage_"]').val()) || 0 ;
                var labourCharge = parseFloat(row.find('input[id^="labourCharge_"]').val()) || 0 ;

                purity = nmFixed(purity) ;
                wastage = nmFixed(wastage) ;

                var finePurity = +purity + +wastage ;
                var fineWeight = netWeight * (finePurity / 100) ;
                var newLabourCharge = parseFloat((labourCharge * netWeight).toFixed(3)) ;
                var wamount = fineWeight * newRate ;
                var amount = +wamount + +newLabourCharge ;

                row.find('input[id^="fineWeight_"]').val(fineWeight.toFixed(3)) ;
                row.find('input[id^="finePurity_"]').val(finePurity.toFixed(2)) ;
                row.find('input[id^="amount_"]').val(amount.toFixed(0)) ;

            });

            calculateTotals() ;

        });
    });

    function calculate(element) {

        id = $(element).attr('id') ;
        var row = $(element).closest('tr') ;

        var quantity = parseFloat(row.find('input[id^="quantity_"]').val()) || 0;
        var netWeight = parseFloat(row.find('input[id^="netWeight"]').val()) || 0;
        var purity = parseFloat(row.find('input[id^="purity_"]').val()) || 0;
        var wastage = parseFloat(row.find('input[id^="wastage_"]').val()) || 0;
        var labourCharge = parseFloat(row.find('input[id^="labourCharge_"]').val()) || 0;
        var rate = parseFloat($('#rate').val()) || 0 ;

        if (isNaN(rate) || $('#rate').val().trim() === "") {
            rate = 0 ;
        }

        purity = nmFixed(purity) ;
        wastage = nmFixed(wastage) ;

        var finePurity = +purity + +wastage ;

        var fineWeight = netWeight * (finePurity / 100) ;
        var new_labourCharge = parseFloat((labourCharge * netWeight).toFixed(3)) ;
        var wamount = fineWeight * rate ;
        var amount =  +wamount + +new_labourCharge ;

        row.find('input[id^="fineWeight_"]').val(fineWeight.toFixed(3)) ;
        row.find('input[id^="finePurity_"]').val(finePurity.toFixed(2)) ;
        row.find('input[id^="amount_"]').val(amount.toFixed(0)) ;

        calculateTotals() ;

    }

    function calculateTotals() {

        var totalQuantity = 0;
        var totalNetWeight = 0;
        var totalFineWeight = 0;
        var totalAmount = 0;

        $('#tableBody tr').each(function() {

            var quantity = parseFloat($(this).find('input[id^="quantity_"]').val()) || 0;
            var netWeight = parseFloat($(this).find('input[id^="netWeight_"]').val()) || 0;
            var fineWeight = parseFloat($(this).find('input[id^="fineWeight_"]').val()) || 0;
            var amount = parseFloat($(this).find('input[id^="amount_"]').val()) || 0;

            totalQuantity += quantity;
            totalNetWeight += netWeight;
            totalFineWeight += fineWeight;
            totalAmount += amount;

        });

        $('#totalQuantity').val(totalQuantity.toFixed(0)) ;
        $('#totalWeight').val(totalNetWeight.toFixed(3)) ;
        $('#totalFineWeight').val(totalFineWeight.toFixed(3)) ;
        $('#totalAmount').val(totalAmount.toFixed(0)) ;

    }

    function tr_delete_show(){

        if ($('#tableBody tr').length > 1) {
                $('#tableBody tr:first').find('.btn-delete').show();
            } else {
                $('#tableBody tr:first').find('.btn-delete').hide();
            }

    }

    $(document).ready(function() {

        $('#tableBody tr:first').find('.btn-delete').hide();

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
                    <button  type = "button"  class="btn btn-danger btn-delete">X</button>
                </td>
               <td> <input type="text" class="tb_input" name="product_name[]" id="productName_` + rowCount + `" placeholder="Product Name"> </td>
               <td> <input type="number" class="tb_input" name="quantity[]" id="quantity_` + rowCount + `" placeholder="Quantity" oninput="calculate(this)"   min = "0" step = "any" > </td>
                <td> <input type="number" class="tb_input" name="carat[]" id="carat_` + rowCount + `" placeholder="Carat"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="gross_weight[]" id="grossWeight_` + rowCount + `" placeholder="Gross Weight" oninput="calculate(this)"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="net_weight[]" id="netWeight_` + rowCount + `" placeholder="Net Weight"  oninput="calculate(this)"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="purity[]" id="purity_` + rowCount + `" placeholder="Purity" oninput="calculate(this)"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="wastage[]" id="wastage_` + rowCount + `" placeholder="Wastage" oninput="calculate(this)"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="fine_purity[]" id="finePurity_` + rowCount + `" placeholder="Fine Purity" readonly   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="fine_weight[]" id="fineWeight_` + rowCount + `" placeholder="Fine Weight" readonly   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="labour_charge[]" id="labourCharge_` + rowCount + `" placeholder="Labour Charge" oninput="calculate(this)"   min = "0" step = "any" > </td>
               <td> <input type="number" class="tb_input" name="amount[]" id="amount_` + rowCount + `" placeholder="Amount" readonly   min = "0" step = "any" > </td>

            </tr>`;

            $('#tableBody').append(newRow) ;
            $('.select2').select2() ;

            updateRowNumbers() ;
            tr_delete_show() ;

        }) ;

        $(document).on('click', '.btn-delete', function() {

            const row = event.target.closest('tr');
            const stockIdInput = row.querySelector('input[name="stock_id[]"]') ;

            if (stockIdInput) {

                const deletedStocks = document.getElementById('deletedStocks');
                const deletedStocksArray = deletedStocks.value ? JSON.parse(deletedStocks.value) : [];
                deletedStocksArray.push(stockIdInput.value);
                deletedStocks.value = JSON.stringify(deletedStocksArray);

            }

            $(this).closest('tr').remove() ;
            updateRowNumbers() ;
            calculateTotals() ;
            tr_delete_show() ;

        });

    }) ;

</script>
