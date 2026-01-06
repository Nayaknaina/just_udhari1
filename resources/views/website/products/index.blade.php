@extends('layouts.website.app')

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> Jewellery Software </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> Jewellery Software </li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    {{-- <img src="{{ asset('assets/images/bg/banner.jpg') }}" alt="Images"> --}}
    </div>
</div>

<section class="prototype_banner_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 d-flex align-item s-center">
                <div class="prototype_content">
                    <div class="section-title">
                        <h1 class="f_size_40 f_700 t_color3 l_height50 pr_70" data-wow-delay="0.3s"> Manage your jewellery billing and accounts with our GST billing software </h1>
                    </div>
                    <p class="f_400 l_height28 wow fadeInLeft" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft;"> From jewellery wholesalers to retailers, everyone gets benefits from our Billing &amp; Accounting software. We have integrated the best features into our software. From jewellery wholesalers to retailers, everyone gets benefits from our Billing &amp; Accounting software. We have integrated the best features into our software. </p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-hand-o-right"></i>Manage your Hallmark Gold Jewellery </li>
                        <li><i class="fa fa-hand-o-right"></i> Complete Solution ( Accounts, Stock, CRM, Loyalty Schemes, Mobile App)</li>
                        <li><i class="fa fa-hand-o-right"></i> Extensive Service Network &amp; Fully Customisable Package</li>
                        <li><i class="fa fa-hand-o-right"></i>Uninterrupted GST Billing &amp; Return Filing</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="clients-slider-two owl-carousel owl-theme">
                    <div class="clients-content">
                    <img src="{{ asset('assets/images/slider/gst-bill1.webp') }}" alt="Images">
                    </div>
                    <div class="clients-content">
                    <img src="{{ asset('assets/images/slider/gst-inv.webp') }}" alt="Images">
                    </div>
                    </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-4 pb-5 sft-features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2 class="f_size_40 f_700 t_color3 l_height50 wow fadeInLeft" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInLeft;"> Jewellery ERP Software<span> Powerful Features </span>
                    </h2>
                    <div class="bottomLine"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                <div class="featureOptions">
                    <div class="featurleft">
                        <div class="FeturleftImg text-center"> <img class="img-fluid" src="{{ asset('assets/images/software/product_banner.png') }}" alt="Just Udhari Barcode &amp; Rfid tags Img"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/account.webp')}}" alt="Just Udhari Account Img"> </div>
                            <div class="featureCont">
                                <h4>Accounting Report</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/stock.webp')}}" alt="Just Udhari Stock Img"> </div>
                            <div class="featureCont">
                                <h4>Stock Managment</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/hallmark.webp')}}" alt="Just Udhari hallmark/HUID Img"> </div>
                            <div class="featureCont">
                                <h4>Hallmark/HUID</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/gst.webp')}}" alt="Just Udhari GST Img"> </div>
                            <div class="featureCont">
                                <h4>GST Report</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/crm.webp')}}" alt="Just Udhari CRM Img"> </div>
                            <div class="featureCont">
                                <h4>CRM</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/gold-melting.webp')}}" alt="Just Udhari Karigar Img"> </div>
                            <div class="featureCont">
                                <h4>Karigar</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/money-lending.webp')}}" alt="Just Udhari Money Lending Img"> </div>
                            <div class="featureCont">
                                <h4>Money Lending</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/repair.webp')}}" alt="Just Udhari Order Repair Img"> </div>
                            <div class="featureCont">
                                <h4>Order-Repairs</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/mobile-app.webp')}}" alt="Just Udhari Mobile App Img"> </div>
                            <div class="featureCont">
                                <h4>Mobile App</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="featurSec mb-3 d-flex align-items-center justify-content-start">
                            <div class="FeturImg"> <img class="img-fluid" src="{{ asset('assets/images/icon/website.webp')}}" alt="Just Udhari E-commerce Img"> </div>
                            <div class="featureCont">
                                <h4>E-commerce</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class = "manageSec gryBg">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2 class="f_size_40 f_700 t_color3 l_height50 wow fadeInLeft" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInLeft;">       Accessible, Convenient &amp; Manageable
                        <div class="bottomLine"></div>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/barcode-rfid.webp')}}" alt="Just Udhari Barcode &amp; Rfid tags Img"> </div>
                        <div class="Scontent">
                            <h4>Barcode &amp; RFID Tags</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV1')"> <i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV1">
                        <p>Your software and data will remain safe as we use different barcodes containing an alphanumeric barcode generation system.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/insurance.webp')}}" alt="Just Udhari jewellery insurance Img"> </div>
                        <div class="Scontent">
                            <h4>Jewellery Insurance</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV2')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV2" >
                        <p>You can provide insured jewelleries and ornaments to your customers. Our software lets you deal with an insurance company offering jewellery insurance. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/stock.webp')}}" alt="Just Udhari Stock Management Img"> </div>
                        <div class="Scontent">
                            <h4>Stock Managment</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV3')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV3">
                        <p>Manage your silver, gold, and other jewellery stock. Our software lets you deal with different categories and subcategories and add VA% and other details. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/order.webp')}}" alt="Just Udhari order-Management Img"> </div>
                        <div class="Scontent">
                            <h4>Order Managment</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV4')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV4">
                        <p>Automate the order process with our jewellery software. You can reallocate items and save time. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/sms.webp')}}" alt="SMS/WA/ Message Img"> </div>
                        <div class="Scontent">
                            <h4>SMS/WA Messages</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV5')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV5">
                        <p>Send messages instantly to your customers using our software dashboard. Send important information to your customers. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/visitors.webp')}}" alt="Customer management Img"> </div>
                        <div class="Scontent">
                            <h4>Customer/Visitor Management</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV6')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV6">
                        <p>You will get detailed information about your customers and visitors. Everything is manageable from one dashboard. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/gold-melting.webp')}}" alt="Karigar Managment Img"> </div>
                        <div class="Scontent">
                            <h4>Karigar Managment</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV7')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV7">
                        <p>Have you employed goldsmiths for your jewellery business? Then, manage the work details and personal information of each of the karigars.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/gold-bar.webp')}}" alt="Bhav Cut/ NO Cut Img"> </div>
                        <div class="Scontent">
                            <h4>Bhav Cut/ No Cut</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV8')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV8">
                        <p>Enjoy effortless payment through metal. The Ledger accounts are manageable with your metal balance. Based on the received payments and cash balance, you can do it. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/stock-report.webp')}}" alt="Stock Report Img"> </div>
                        <div class="Scontent">
                            <h4>Stock Report</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV9')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV9">
                        <p>Get the product stock summary in a comprehensive report. You can learn about the product carat, purity, item code, and other details. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/fingerprint.webp')}}" alt="Finger Scan Img"> </div>
                        <div class="Scontent">
                            <h4>Finger Scan / Bio Metric</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV01')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV01">
                        <p>We have tried to make our jewellery software more secure with Biometric technology. Using the finger scanning system, you can start using our software. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/loyalty-point.webp')}}" alt="Loyalty Point Img"> </div>
                        <div class="Scontent">
                            <h4>Loyalty Point</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV02')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV02">
                        <p>Provide your loyal customers with loyalty points. The software will automatically offer these points to your customers.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/e-com-iter.webp')}}" alt="Ecommerce Integration Img"> </div>
                        <div class="Scontent">
                            <h4>E-commerce Integration</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV03')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV03">
                        <p>Do you like to sell your jewellery through eCommerce platform? Integrate a platform into this custom software. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/rupee.webp')}}" alt="Monthly Saving Scheme Img"> </div>
                        <div class="Scontent">
                            <h4>Monthly Saving Scheme</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV04')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV04">
                        <p>How much have you saved and earned profit from your jewellery business? Our software lets you create a saving scheme.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/loan.webp')}}" alt="Money Lending Girvi Img"> </div>
                        <div class="Scontent">
                            <h4>Money Lending Girvi</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV05')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV05">
                        <p>Using this feature, you can make jewellery-amount transactions, attach photos with your transaction, and manage Girvi transfers.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="accBoxs">
                    <div class="accImg d-flex align-items-center mb-2">
                        <div class="Simg mr-3"> <img class="img-fluid" src="{{ asset('assets/images/icon/gold-metal-bar.webp')}}" alt="Money Lending Girvi Img"> </div>
                        <div class="Scontent">
                            <h4>Bullion</h4>
                        </div>
                    </div>
                    <div class="buttonarrow text-right"><button class="btn arrowBtn" onclick="myFunction('myDIV06')"><i class="fa fa-angle-double-down"></i></button></div>
                    <div class="Accpara moneyPara"  style = "display : none " id="myDIV06">
                        <p>You can find separate cash-cheque rates and connect live metal rates. Moreover, stock management also becomes easy.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <section class="h_action_area_three">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2> Just Udhari Software Video </h2>
                    <div class="bottomLine"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/-5j3MgNv6dk" target="_blank"><img src="https://img.youtube.com/vi/-5j3MgNv6dk/default.jpg" alt="Just Udhari jewellery video Img" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/_vNTsbHgS-M" target="_blank"><img src="https://img.youtube.com/vi/_vNTsbHgS-M/default.jpg" alt="Just Udhari jewellery video Img" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/_vNTsbHgS-M" target="_blank"><img src="https://img.youtube.com/vi/_vNTsbHgS-M/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/q_gY1IBsokg" target="_blank"><img src="https://img.youtube.com/vi/q_gY1IBsokg/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/uSfc7iaFirw" target="_blank"><img src="https://img.youtube.com/vi/uSfc7iaFirw/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/V1umVhn-VGk" target="_blank"><img src="https://img.youtube.com/vi/V1umVhn-VGk/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/_vNTsbHgS-M" target="_blank"><img src="https://img.youtube.com/vi/_vNTsbHgS-M/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="demoImg mb-3"><a href="https://youtu.be/_vNTsbHgS-M" target="_blank"><img src="https://img.youtube.com/vi/_vNTsbHgS-M/default.jpg" alt="Jewellery-software Video" class="img-fluid"></a></div>
            </div>
        </div>
    </div>
</section> --}}

<section class = "gryBg product-demo-section">
    <div class="container">
        <div class="row justify-content-center ">
            <div class = "col-lg-6">
                <div class="form demoFrm">
                    <form class="pos_subscribe" id="productPage" name="productPage" action="#" method="POST" onsubmit="return validatecaptcha()"><input type="hidden" name="productPage" id="productPage" value="productPage">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section-title text-center">
                                    <h2>Get Free Demo</h2>
                                    <div class="bottomLine"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group border mb-2"><input type="text" id="name" name="name" class="form-control" placeholder="Name" onkeydown="if (event.keyCode == 13) { document.getElementById('email').focus(); return false; }"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group border mb-2"><input type="email" id="email" name="email" class="form-control" placeholder="Email" onkeydown="if (event.keyCode == 13) { document.getElementById('countryCodeDemo').focus(); return false; }"></div>
                            </div>

                            <div class="col-lg-12">
                                    <div class="form-group border mb-2"><input type="text" id="mob_no" name="mob_no" class="form-control" placeholder="Mobile No."></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 g-recaptcha" data-sitekey="6LfhKv0mAAAAANaG8jMrpCdgXQ6Z9ii9c7wxxKtg">
                                    <div style="width: 304px; height: 78px;">
                                        <div><iframe title="reCAPTCHA" width="304" height="78" role="presentation" name="a-5ztnosxk7nua" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation"
                                                src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LfhKv0mAAAAANaG8jMrpCdgXQ6Z9ii9c7wxxKtg&amp;co=aHR0cHM6Ly9vbXVuaW0uY29tOjQ0Mw..&amp;hl=en&amp;v=9pvHvq7kSOTqqZusUzJ6ewaF&amp;size=normal&amp;cb=dgsesby9ryq"></iframe></div>
                                        <textarea
                                            id="g-recaptcha-response-1" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea>
                                    </div><iframe style="display: none;"></iframe></div>
                                <div id="captcha-error1" class="clrred"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mt-2"><button class="btn btn_pos w-100 contact_btn" type="submit">Get a Demo</button></div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

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
