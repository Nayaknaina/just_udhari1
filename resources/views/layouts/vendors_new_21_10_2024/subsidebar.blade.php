
<div id="compact_submenuSidebar" class="submenu-sidebar">

        <div class="submenu" id="ecommerce-admin">
            <div class="menu-title">
                <h3>Ecommerce Admin</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#ecommerce-admin">

                <li>
                    <a href = "{{ route('ecomdashboard') }}" class="">
                    <i class="nav-icon fas fa-tachometer-alt"></i> Ecommerce Dashborad
                    </a>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#Products" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Products </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="Products" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('ecomproducts.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                              Listed
                            </a>
                        </li>

                        <li >
                            <a href="{{ route('ecomstocks.index') }}"  >
                            <i class="far fa-circle nav-icon"></i>
                              Not Listed
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href = "{{ route('catalogues.index') }}" class="nav-link ">
                        <i class="far fa-circle nav-icon "></i>
                          Catalogues
                    </a>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#ecom-cat" aria-expanded="false">
                        <div>  <i class="far fa-circle nav-icon "></i> Ecom Categories </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="ecom-cat" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('ecomcategories.show',1) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(1) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('ecomcategories.show',2) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(2) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('ecomcategories.show',3) }}"  >
                            <i class="far fa-circle nav-icon"></i>
                            {{ category_label(3) }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#ecom-miscellaneous" aria-expanded="false">
                        <div>  <i class="far fa-circle nav-icon "></i> Miscellaneous </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="ecom-miscellaneous" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href = "{{ route('ecomslider') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Slider
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('aboutcontent') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           About Us
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('ecommwebinformations.index') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Web Information
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('sociallink') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Social Links
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('termsandconditions') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Terms & Conditions
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('privacypolicy') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Privacy Policy
                            </a>
                        </li>
                        <li >
                            <a href = "{{ route('desclaimer') }}" >
                            <i class = "far fa-circle nav-icon"></i>
                           Desclaimer
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

        <div class="submenu" id="Inventory">
            <div class="menu-title">
                <h3>Inventory</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#Inventory">
                <li >
                    <a href="{{ route('purchases.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Purchase
                    </a>
                </li>

                <li >
                    <a href="{{ route('stocks.index') }}" >
                    <i class="far fa-circle nav-icon"></i> Jewellery Stock
                    </a>
                </li>
            </ul>
        </div>

        <div class="submenu" id="schemes">
            <div class="menu-title">
                <h3>Schemes</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#schemes">
                <li>
                    <a href="{{ route('shopschemes.enquiries') }}" style="font-weight:bold;">
                    &#x26A0;&nbsp;ENQUIRIES&nbsp;&#x27A4; &nbsp;<span class="badge badge-info">{{ enquirycount() }}</span>
                    </a>
                </li>
                <li >
                    <a href="{{ route('shopscheme.index') }}" >
                        <i class="far fa-circle nav-icon"></i> All Scheme
                    </a>
                </li>

                @php
                    $user = Auth::user();
                    $scheme_menu = schememenu($user->shop_id);
                @endphp

                @if(count($scheme_menu)>0)
                    @foreach($scheme_menu as $sk=>$scheme)
                        <li >
                            <a href="{{ route('shopscheme.show',$scheme->id) }}" >
                                <i class="far fa-circle nav-icon"></i> {{  $scheme->schemes->scheme_head }}
                            </a>
                        </li>
                    @endforeach

                @endif
                <li >
                    <a href="{{ route('group.index') }}" >
                        <i class="far fa-circle nav-icon"></i> Scheme Groups
                    </a>
                </li>

                <li >
                    <a href="{{ route('enrollcustomer.index') }}" >
                        <i class="far fa-circle nav-icon"></i> Advance Enroll Customer
                    </a>
                </li>

                <li>
                    <a href="{{ route('shopscheme.pay') }}" >
                        <i class="far fa-circle nav-icon"></i> Add Money
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('shopscheme.due') }}" >
                        <i class="far fa-circle nav-icon"></i> Due Amount
                    </a>
                </li>

                
                <li>
                    <a href="#" >
                        <i class="far fa-circle nav-icon"></i> Day Book
                    </a>
                </li>
            </ul>
        </div>

        <div class="submenu" id = "miscellaneous">
            <div class="menu-title">
                <h3>Miscellaneous</h3>
            </div>
            <ul class="submenu-list" data-parent-element="#miscellaneous">
                    <li >
                        <a href = "{{ route('suppliers.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Suppliers
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('customers.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Customers
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('shopbranches.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Shop Branches
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('counters.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Shop Counters
                        </a>
                    </li>
                    <li>
                        <a href = "{{ route('designations.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Employee Designations/Roles
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('employees.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Employees
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('settings.index') }}" >
                        <i class = "far fa-circle nav-icon"></i> Settings
                        </a>
                    </li>
                    <li >
                        <a href = "{{ route('subscriptions') }}" >
                        <i class = "far fa-circle nav-icon"></i> Subscriptions
                        </a>
                    </li>

                <li class="sub-submenu">
                    <a role="menu" class="collapse collapsed" data-toggle="collapse" data-target="#Products" aria-expanded="false">
                        <div> <i class="far fa-circle nav-icon "></i> Product Categories </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>

                    <ul id="Products" class="collapse " data-parent="#compact_submenuSidebar">
                        <li >
                            <a href="{{ route('categories.show',1) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(1) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('categories.show',2) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(2) }}
                            </a>
                        </li>
                        <li >
                            <a href="{{ route('categories.show',3) }}"  >
                            <i class="far fa-circle nav-icon"></i> {{ category_label(3) }}
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

{{-- ================================================================================================== --}}

</div>
