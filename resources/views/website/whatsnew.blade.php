@extends('layouts.website.app')

@section('css')

<style>

    body {

        background: #f9f9f9;

    }

</style>

@endsection

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> What`s New </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> What`s New </li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    </div>
</div>

<section class = "whats-section" >

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="section-title pb-4 text-center ">
                    <h2> What`s New </h2>
                    <div class="bottomLine"></div>
                </div>
            </div>
        </div>

        <ul class="project-list">

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wnqrcode.jpg')}}" alt="" class="images img-fluid">
                            </figure>
                    </div>
                    <div class="col-lg-7">
                        <h3 class="title">Speed up your Bills/Orders with QR Codes on your Jewellery</h3>
                        <ul>
                            <li class="lis">Print the QR Code on Every Article with the Sr. no., &amp; G.wt., &amp; N.wt.</li>
                            <li class="lis">Scan QR Code anywhere from Mobile (Local or Outstation)</li>
                            <li class="lis">Print Quotation/Order/Bill</li>
                            <li class="lis">Merge data with your Accounting ERP</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wnsmscode.jpg')}}" alt="" class="images img-fluid">
                            </figure>
                    </div>
                    <div class="col-lg-7">
                        <h3 class="title">Send PDF Bill on SMS</h3>
                        <ul>
                            <li class="lis">No need of Customers WhatsApp</li>
                            <li class="lis">Upgrade Post-Purchase Customer Experience</li>
                            <li class="lis">Acquire And Convert Customers </li>
                            <li class="lis">Streamline Notifications And Alerts</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure class="img-container"><img src="{{ asset('assets/images/whats/wnownerdesktop.png')}}" alt="" class="images img-fluid">
                            </figure>
                    </div>
                    <div class="col-lg-7">
                        <h3 class="title">Application for owner</h3>
                        <ul>
                            <li class="lis">Tag Query/Estimation</li>
                            <li class="lis">Create &amp; Print Quotation/Estimation</li>
                            <li class="lis">Product Gallery/Catalogue</li>
                            <li class="lis">Share Designs PDF on Whatsapp</li>
                            <li class="lis">Add Images to Tag Stock</li>
                            <li class="lis">Physical Stock Verification with RFID</li>
                            <li class="lis">Todays Bhaav Display</li>
                            <li class="lis">Visitor Data Records</li>
                            <li class="lis">Outstanding Calling and feedback</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wnecatalogueapp.png')}}" alt="" class="images img-fluid">
                            </figure>
                    </div>
                    <div class="col-lg-7">
                        <h3 class="title">E- Catalogue Application for Showroom &amp; Salesman working :-</h3>
                        <ul class="">
                            <li class="lis">Tag Query/Estimation</li>
                            <li class="lis">Create &amp; Print Quotation/Estimate</li>
                            <li class="lis">Product Gallery/Catalogue</li>
                            <li class="lis">Share Designs on Whatsapp in PDF Form</li>
                            <li class="lis">Add Images to Tag Stock</li>
                            <li class="lis">Physical Stock Verification with RFID</li>
                            <li class="lis">Todays Bhaav Display</li>
                            <li class="lis">Visitor Data Records</li>
                            <li class="lis">Outstanding calling and feedback</li>
                            <li class="lis">Order &amp; Repairs Status</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wncustomerapp.png')}}" alt="" class="images img-fluid">
                            </figure>
                    </div>
                    <div class="col-lg-7">
                        <h3 class="title">Application for your valuable customer</h3>
                        <ul>
                            <li class="lis">Display Live Rates</li>
                            <li class="lis">Display order and its updated status </li>
                            <li class="lis">Get Customer valuable references </li>
                            <li class="lis">Display Offers, Schemes, and Discount</li>
                            <li class="lis">Receive feddback/suggestions from customer</li>
                            <li class="lis">Display customer member points ledger individually</li>
                            <li class="lis">Show new arrivals stock images</li>


                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wnjewellerybusinessdesktop.png')}}" alt="" class="images img-fluid">
                            </figure>
                        </div>
                    <div class="col-lg-7">
                        <h3 class="title">WhatsApp Integration in Jwelly ERP</h3>
                        <ul>
                            <li class="lis">Share PDF of Design on WhatsApp</li>
                            <li class="lis">Send Notifications on WhatsApp. E.g. Orders, Bills, and other Updates.</li>
                            <li class="lis">Make easy communication between you and your customer</li>
                            <li class="lis">This leads to improved customer engagement, efficient operations, and increased sales.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="project-item">
                <div class = "row">
                    <div class="col-lg-5">
                            <figure>
                            <img src="{{ asset('assets/images/whats/wncrmdesktop.png')}}" alt="" class="images img-fluid">
                            </figure>
                        </div>
                    <div class="col-lg-7">
                        <h3 class="title">CRM (Customer Relationship Management)</h3>
                        <ul>
                            <li class="lis">Generate enquiry for different types of products.</li>
                            <li class="lis">Import excel to create enquiry for telecaller (Assign Customers, Follow-up).</li>
                            <li class="lis">Show your all Coupons details, Expiry date, Redemption date,e tc</li>
                            <li class="lis">Display enquiry list as per enquiry status basis.</li>
                            <li class="lis">Manage Schemes, Coupons, feedbacks and Membership Points.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </ul>

    </div>

</section>

@endsection

