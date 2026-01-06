<style>
    #pay_data_area > tr >td{
        font-size:90%;
    }
    tr.selected{
        background-color:#fff6ed;
    }
    tr.selected > td{
        border-top:1px dashed #fd5f00;
        border-bottom:1px dashed #fd5f00;
    }
</style>
<h5 class="col-12 text-center" style="text-shadow: 1px 2px 3px gray;">Girvi Options</h5>
<div class="col-12 p-0">
    <table class="table text-dark pb-2" style="border-bottom:1px solid gray;" id="operation_table">
        <thead id="item_table_head">
            <tr class="bg-light">
                <th class="text-dark">Sn</th>
                <th class="text-dark">DATE</th>
                <th class="text-dark">RECEIPT</th>
                <th class="text-dark">ITEMS</th>
                <th class="text-dark">VALUATION</th>
                <th class="text-dark">INTEREST</th>
                <th class="text-dark">TENURE</th>
                <th class="text-dark">PAYABLE</th>
                <th class="text-dark">Pay</th>
            </tr>
        </thead>
        <tbody id="option_data_area">
        </tbody>
    </table>
</div>