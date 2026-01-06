@extends('layouts.website.app')

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> Faq </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> Faq </li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    {{-- <img src="{{ asset('assets/images/bg/banner.jpg') }}" alt="Images"> --}}
    </div>
</div>

<section class = "faq-section" >

    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"> General Questions </button>
              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"> Membership </button>
              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false"> Other Option </button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
              <div class="faq-content">
                <div class="faq-accordion">
                    <ul class="accordion">
                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <i class="bx bx-minus"></i>
                                What is a Managed Security Services?
                            </a>
                            <div class="accordion-content">
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                </p>
                            </div>
                        </li>
                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <i class="bx bx-plus"></i> What is a Data Analysis? </a>
                            <div class="accordion-content">
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                </p>
                            </div>
                        </li>
                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                 <i class="bx bx-plus"></i> How Can Make Secure My Website? </a>
                            <div class="accordion-content">
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                </p>
                            </div>
                        </li>
                        <li class="accordion-item">
                            <a class="accordion-title active" href="javascript:void(0)"> <i class="bx bx-plus"></i> What is a Infrastructure? </a>
                            <div class="accordion-content show">
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="faq-content">
                    <div class="faq-accordion">
                        <ul class="accordion">
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                    <i class="bx bx-minus"></i>
                                    What is a Managed Security Services?
                                </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                    <i class="bx bx-plus"></i> What is a Data Analysis? </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                     <i class="bx bx-plus"></i> How Can Make Secure My Website? </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title active" href="javascript:void(0)"> <i class="bx bx-plus"></i> What is a Infrastructure? </a>
                                <div class="accordion-content show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

                <div class="faq-content">
                    <div class="faq-accordion">
                        <ul class="accordion">
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                    <i class="bx bx-minus"></i>
                                    What is a Managed Security Services?
                                </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                    <i class="bx bx-plus"></i> What is a Data Analysis? </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                     <i class="bx bx-plus"></i> How Can Make Secure My Website? </a>
                                <div class="accordion-content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                            <li class="accordion-item">
                                <a class="accordion-title active" href="javascript:void(0)"> <i class="bx bx-plus"></i> What is a Infrastructure? </a>
                                <div class="accordion-content show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam leo. Mauris a ante placerat, dignissim orci eget, viverra ante. Mauris ornare pellentesque augue.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
          </div>
    </div>

</section>

@endsection

