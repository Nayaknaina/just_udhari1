@extends('layouts.website.app')

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
      <h3> Delete Reuest </h3>
      <ul>
        <li> <a href="{{ url('/') }}">Home</a> </li>
        <li> <i class="bx bx-chevrons-right"></i> </li>
        <li> Delete Request </li>
      </ul>
    </div>
    </div>
</div>
<section class="policy_area p-5">
  <div class="row">
    <div class="col-12">
      <h2 class="text-center">Data Retention and Deletion Policy !</h2>
      <div class="main_block">
        <h3><u>1</u>. Data Storage During Active Subscription</h3>
        <p>While a vendor's subscription is active, the following data will be securely stored:</p>
        <ul>
          <li>Vendor profile details (name, contact, business information)</li>
          <li>Transaction records (gold girvi, sales, purchases, expenses, etc.)</li>
          <li>Customer details and transaction history</li>
          <li>Inventory records</li>
          <li>Subscription details and payment history</li>
        </ul>
      </div>
      <div class="main_block">
        <h3><u>2</u>. Data Handling After Subscription Expiry or Account Deactivation</h3>
        <p>If a vendor's subscription expires or their account is deactivated:</p>
        <ul>
          <li>The account will be marked as *inactive, but data will be retained for a grace period of **30 to 90 days*.</li>
          <li>During this period, vendors can renew their subscription to regain full access.</li>
          <li>After the grace period, data may be *archived or deleted* as per the policy below.</li>
        </ul>
      </div>
      <div class="main_block">
        <h3><u>3</u>. Data Deletion Policy</h3>
        <div class="sub_block">
          <h6><u>3.1</u>. Account Expiry Without Renewal</h6>
          <p>If the vendor does not renew the subscription within the grace period:</p>
          <ul>
            <li>Transaction and customer records will be *archived* for up to *1 year* for compliance purposes.</li>
            <li>After *1 year, data may be **permanently deleted* unless required by law.</li>
            <li>Vendors may request a *data backup* before deletion.</li>
          </ul>
        </div>
        <div class="sub_block">
          <h6><u>3.2</u>. Account Deletion/Deactivation by Vendor Request</h6>
          <p>If a vendor voluntarily deactivates their account:</p>
          <ul>
            <li>Data will be retained for *30 days* in case of reactivation.</li>
            <li>After 30 days, all data will be *permanently deleted* unless legally required.</li>
          </ul>
        </div>
        <div class="sub_block">
          <h6><u>3.3</u>. Account Deletion by Violation of Terms</h6>
          <p>If an account is deleted due to a violation of terms or fraudulent activity:</p>
          <ul>
            <li>Data may be retained for *legal and compliance* purposes for up to *2 years* before deletion.</li>
            <li>Legal authorities may be granted access if required.</li>
          </ul>
        </div>
      </div>
      <div class="main_block">
        <h3><u>4</u>. Backup & Data Export</h3>
        <p>If an account is deleted due to a violation of terms or fraudulent activity:</p>
        <ul>
          <li>Vendors can request a data export *before deletion</li>
          <li>Regular backups will be maintained for security, but once data is permanently deleted, it *cannot be restored*.
          *5. Policy Updates</li>
          <li>This policy is subject to change, and vendors will be notified of any updates.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<style>
  .main_block,.sub_block{
    padding:20px 5px;
  }
  .main_block > h3,.sub_block > h6{
    margin:10px 0px!important;
  }
</style>
@endsection

@section('javascript')

<script language="JavaScript" type="text/javascript">
    $(document).ready(function(){
      $('.carousel').carousel({
        interval: 2000
      })
    });
</script>

@endsection
