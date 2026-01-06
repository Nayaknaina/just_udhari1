<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>MG Jewelers - Sell Bill (A4)</title>
@php
 //dd(auth()->user()->shopbranch);
@endphp
<style>
  /* A4 page setup */
  @page { size: A4; margin: 12mm; }
  html,body { height: 100%; margin: 0; padding: 0; }
  body {
    font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 12px;
    color: #222;
    background: #fff;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .sheet {
    width: 100%;
    max-width: 210mm;
    margin: 0 auto;
    padding: 10mm 12mm;
    box-sizing: border-box;
  }

  /* ===== Header ===== */
  header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
    border-bottom: 2px solid #b8860b;
    padding-bottom: 6px;
  }
  .brand h1 {
    margin: 0;
    font-size: 24px;
    color: #b8860b; /* Golden accent */
    letter-spacing: 0.6px;
  }
  .brand .meta { font-size: 11px; color: #555; margin-top: 4px; }
  .bill-title { text-align: right; }
  .bill-title h2 { margin: 0; font-size: 15px; color:#222; }
  .bill-title .muted { font-size: 11px; color: #777; margin-top: 4px; }

  /* ===== Meta Section ===== */
  .parties {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin: 10px 0;
  }
  .box {
    border: 1px solid #ddd;
    padding: 8px;
    background: #fffaf0; /* very light gold tint */
  }
  .box label { display:block; font-size:11px; color:#555; margin-bottom:4px; }
  .box div { font-size:13px; color:#111; }

  /* ===== Items Table ===== */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
  }
  thead th {
    border: 1px solid #d9c58d;
    text-align: left;
    padding: 6px 8px;
    font-size: 12px;
    background:#fdf7e3; /* soft gold header */
    color:#111;
  }
  tbody td {
    padding: 6px 8px;
    border: 1px solid #eee;
    font-size: 12px;
  }
  tfoot td { padding: 8px; font-size: 12px; }

  .text-right { text-align: right; }
  .text-center { text-align: center; }

  /* column widths */
  .col-no { width: 6%; }
  .col-desc { width: 38%; }
  .col-purity { width: 10%; }
  .col-gross { width: 10%; }
  .col-net { width: 10%; }
  .col-rate { width: 12%; }
  .col-amt { width: 14%; }

  /* Totals */
  .totals {
    margin-top: 6px;
    display: flex;
    justify-content: flex-end;
  }
  .totals table {
    width: 360px;
    border: 1px solid #d9c58d;
    background:#fffef8;
  }
  .totals td {
    border-bottom: 1px dashed #eee;
    padding: 8px;
  }
  .totals tr:last-child td { border-bottom:none; }

  /* Notes & Signatures */
  .bottom {
    display: flex;
    justify-content: space-between;
    margin-top: 14px;
    gap: 12px;
  }
  .terms {
    width: 60%;
    font-size: 11px;
    color:#444;
    border:1px solid #ddd;
    padding:8px;
    background:#fffef9;
  }
  .terms strong { color:#b8860b; }
  .sigs { width: 38%; display:flex; flex-direction:column; gap:8px; }
  .sig-box {
    border:1px solid #ddd;
    height:72px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    color:#666;
    background:#fafafa;
  }

  /* Footer */
  .footer-note {
    text-align:center;
    font-size:11px;
    color:#444;
    margin-top:10px;
    border-top:1px solid #d9c58d;
    padding-top:4px;
  }

  @media print {
    body { background: #fff; }
    .sheet { padding: 0; margin: 0; }
  }
</style>
</head>
<body>
  <div class="sheet" id="sheet">
    <header>
      <div class="brand">
       <div style="text-align:center; margin-bottom: 15px;">
        @php 
          $img_path = auth()->user()->shopbranch->image;
        @endphp 
      <img src="{{ asset("{$img_path}") }}" 
           alt="MG Jewellers Logo" 
           style="max-height:80px; width:auto; display:block; margin:0 auto;">
      <!-- 👇 Jai Shree Mahakal premium style -->
      <div style="font-size:20px; font-weight:700; color:#b8860b; margin-top:8px; text-align:center; font-family: 'Georgia', serif; letter-spacing:1px;">
        Jai Shree Mahakal
      </div>
      <div style="width:160px; height:2px; background:#b8860b; margin:4px auto 0; border-radius:2px;"></div>
    </div>

          

        <div class="meta">
          <b>{{ auth()->user()->shopbranch->name }}</b><br>
          {{ auth()->user()->shopbranch->address??'------' }}<br>
          Phone:{{ auth()->user()->shopbranch->mobile_no??'-----' }} 
          | 
          GSTIN:  {{ auth()->user()->shopbranch->gst_num??'-----' }}
        </div>
      </div>
      <div class="bill-title">
        <h2>GST / INVOICE</h2>
        <div style="margin-top:8px; font-size:13px;">
          <div><strong>Invoice No:</strong> {{ $bill->bill_number }}</div>
          <div><strong>Date:</strong> {{ date('d/m/Y',strtotime($bill->bill_date)) }}</div>
          @if($bill->due_date && ($bill->bill_date != $bill->due_date))
          <div><strong>Due:</strong> {{ date('d/m/Y',strtotime($bill->due_date)) }}</div>
          @endif
        </div>
      </div>
    </header>

    <!-- Parties -->
    <section class="parties">
      <div class="box">
        @php 
          $party_title = ['c'=>"Customer",'s'=>'Supplier'];
        @endphp
        <label>Bill To ({{ @$party_title["{$bill->party_type}"] }})</label>
        <div><strong>{{ $bill->party_name }}</strong></div>
        <div class="small">{{ ($bill->partydetail->custo_address??$bill->partydetail->address)??'-----' }}</div>
        <div class="small"><strong>Mobile:</strong> {{ $bill->party_mobile }}</div>
      </div>
      <div class="box">
        <label>Bank Details</label>
        <div class="small"><strong>Bank Name:</strong> {{ auth()->user()->banking->name }}</div>
        <div class="small"><strong>Bank Branch:</strong> {{ auth()->user()->banking->branch }}</div>
        <div class="small"><strong>Bank A/c No:</strong> {{ auth()->user()->banking->account }}</div>
        <div class="small"><strong>Bank ifsc code:</strong> {{ auth()->user()->banking->ifsc }}</div>
      </div>
    </section>

    <!-- Items Table -->
    <section>
      <table>
        <thead>
          <tr>
            <th class="col-no text-center">Sno.</th>
            <th class="col-desc">Description</th>
            <th class="col-purity text-center">Purity</th>
            <th class="col-gross text-right">Gross Wt</th>
            <th class="col-rate text-right">Less Wt</th>
            <th class="col-net text-right">Net Wt</th>
            <th class="col-amt text-right">Amount (₹)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bill->billitems as $ik=>$item)
            <tr>
               <td class="text-center">{{ $ik+1 }}</td>
              <td>{{ $item->item_name }}</td>
              <td class="text-center">{{ @$item->caret }}</td>
              <td class="text-right">{{ @$item->gross }}</td>
              <td class="text-right">{{ @$item->less }}</td>
              <td class="text-right">{{ @$item->net }}</td>
              <td class="text-right">{{ @$item->total }}</td>
            </tr>
            @endforeach
        </tbody>
      </table>

      <!-- Totals -->
      <div class="totals">
        <table>
          <tr><td>Sub-Total (₹)</td><td class="text-right">{{ $bill->sub }}</td></tr>
		  @php $gst_val = ($bill->sub * 1.5)/100 @endphp 
          <tr><td>CGST 1.5% (₹)</td><td class="text-right">{{ $gst_val }}</td></tr>
          <tr><td>SGST 1.5% (₹)</td><td class="text-right">{{ $gst_val }}</td></tr>
		  @php $gat_amnt = $gst_val*2 @endphp
		  <tr style="color:#0b57b8;">
            <td>Round Off (₹)</td><td class="text-right">{{ $bill->round }}</td>
          </tr>
          <tr style="font-weight:700;color:#b8860b;">
            <td>Grand Total (₹)</td><td class="text-right">{{ $bill->final }}</td>
          </tr>
          @if($bill->payments->count()>0)
          <tr><td colspan="2"><strong>Payment Detail</strong></td></tr>
            @foreach($bill->payments as $pk=>$pay)
              <tr>
                <td>{{ ucfirst($pay->pay_method) }}(₹)</td>
                <td  class="text-right">{{ $pay->pay_value }}</td>
              </tr>
            @endforeach
          @endif
		  @php 
			$remains = $bill->payment - $bill->final;
			$title = ($remains==0 || $remains < 0)?'REMAINS':'ADVANCE'
		  @endphp
		  <tr >
			<td>{{ $title }}(₹)</td>
			<td  class="text-right" style="color:{{ ($title=='REMAINS')?'red':'green'; }}"><b>{{ $remains }}</b></td>
		  </tr>
          <tr><td>Amount in Words</td><td class="text-right small">
		  {{ ucwords(numberToWords($bill->final)) }}
		  </td></tr>
        </table>
      </div>
    </section>

    <!-- Terms & Signatures -->
    <section class="bottom">
      <div class="terms">
        <strong>Terms & Conditions:</strong>
        <ul>
          <li>HALLMARKING CHARGES RS 65/- PER ARTICLE</li>
          <li>1.All Rate Inclusive of Marking Charges.</li>
          <li>2.Return of product will be par current rate of purity.</li>
          <li>3.10 days replecement policy according to product condition and not used.</li>
        </ul>
      </div>
      <div class="sigs">
        <div class="sig-box">Customer Signature</div>
        <div class="sig-box">For MG Jewelers<br/><small>Authorised Signatory</small></div>
      </div>
    </section>

    <div class="footer-note">Thank you for your purchase!</div>
    <style>
      @media print{
        #print_receipt{
          display:none;
        }
      }
    </style>

</div>
<div style="width:100%;text-align:center;padding:10px;" id="print_receipt">
  <button type="button"  onclick="window.print();" style="margin:auto;">&#128424; Print</button>
</div>
  {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
  function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.html(document.querySelector("#sheet"), {
      callback: function (doc) {
        doc.save("{{ $file_name }}"); // 👈 filename here
      },
      x: 10,
      y: 10
    });
  }
</script>--}}
</body>
</html>