<style>
.rec_num_label{
    border: 1px solid #4d7aff;
    padding: 0 6px;
    line-height: normal;
    border-radius: 50%;
    cursor:pointer;
}
.rec_num_label.active{
    /* border: 1px solid #4d7aff;
    padding: 0 6px;
    line-height: normal;
    border-radius: 50%; */
    background: #fec6b5;
    color: blue;
}
</style>
<div class="table-responsive-mobile">
    <table class="table table_theme">
        <thead>
            <tr>
                <th>&#9745;</th>
                <th>Receipt No</th>
                <th>Date &amp; Time</th>
                <th>Interest</th>
                <th>Loan Amount</th>
                <th>Interest Amount</th>
                <th>Total Payable</th>
                <th>Return Date &amp; Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="record_data_area">
            <tr><td colspan="9" class="text-center text-danger">No Record !</td></tr>
        </tbody>
        <tfoot class="bg-header-primary-pd1">
            <tr class="tfoot-tr">
                <th colspan="3" class="text-center">Total</th>
                <th> - </th>
                <th id="total_principal">0 ₹</th>
                <th id="total_interest">0 ₹</th>
                <th id="total_amount_sum">0 ₹</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</div>
<div id="paging">
</div>