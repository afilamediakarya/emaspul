
<div class="aside-menu aside-menu-custom flex-column-fluid">
    <!--begin::Aside Menu-->
    <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
        <!--begin::Menu-->
        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
            <div class="menu-item">
                <a class="menu-link active_custom" href="{{ route('dashboard.admin') }}">
                    <span class="menu-icon">
               
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="19" viewBox="0 0 17 19" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.88889 6.66678C1.38792 6.66678 0.907478 6.46777 0.553243 6.11353C0.199007 5.7593 0 5.27885 0 4.77789V1.94455C0 1.44359 0.199007 0.963142 0.553243 0.608907C0.907478 0.254671 1.38792 0.0556641 1.88889 0.0556641H5.66667C6.16763 0.0556641 6.64808 0.254671 7.00231 0.608907C7.35655 0.963142 7.55556 1.44359 7.55556 1.94455V4.77789C7.55556 5.27885 7.35655 5.7593 7.00231 6.11353C6.64808 6.46777 6.16763 6.66678 5.66667 6.66678H1.88889ZM1.88889 18.9446C1.38792 18.9446 0.907478 18.7455 0.553243 18.3913C0.199007 18.0371 0 17.5566 0 17.0557V9.50011C0 8.99914 0.199007 8.5187 0.553243 8.16446C0.907478 7.81023 1.38792 7.61122 1.88889 7.61122H5.66667C6.16763 7.61122 6.64808 7.81023 7.00231 8.16446C7.35655 8.5187 7.55556 8.99914 7.55556 9.50011V17.0557C7.55556 17.5566 7.35655 18.0371 7.00231 18.3913C6.64808 18.7455 6.16763 18.9446 5.66667 18.9446H1.88889ZM11.3333 18.9446C10.8324 18.9446 10.3519 18.7455 9.99769 18.3913C9.64345 18.0371 9.44444 17.5566 9.44444 17.0557V15.1668C9.44444 14.6658 9.64345 14.1854 9.99769 13.8311C10.3519 13.4769 10.8324 13.2779 11.3333 13.2779H15.1111C15.6121 13.2779 16.0925 13.4769 16.4468 13.8311C16.801 14.1854 17 14.6658 17 15.1668V17.0557C17 17.5566 16.801 18.0371 16.4468 18.3913C16.0925 18.7455 15.6121 18.9446 15.1111 18.9446H11.3333ZM11.3333 11.389C10.8324 11.389 10.3519 11.19 9.99769 10.8358C9.64345 10.4815 9.44444 10.0011 9.44444 9.50011V1.94455C9.44444 1.44359 9.64345 0.963142 9.99769 0.608907C10.3519 0.254671 10.8324 0.0556641 11.3333 0.0556641H15.1111C15.6121 0.0556641 16.0925 0.254671 16.4468 0.608907C16.801 0.963142 17 1.44359 17 1.94455V9.50011C17 10.0011 16.801 10.4815 16.4468 10.8358C16.0925 11.19 15.6121 11.389 15.1111 11.389H11.3333Z" style="fill:white"/>
                        </svg>
                        </span>
                        
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dashboard</span>
                </a>
            </div>


            @if(Auth::user()->id_role == 1 || Auth::user()->id_role == 4)
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                        <span class="svg-icon svg-icon-2">
                        <svg width="16" height="21" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V21H16V10.5H6.85714V0H0ZM9.14286 0V7.875H16L9.14286 0ZM2.28571 5.25H4.57143V7.875H2.28571V5.25ZM2.28571 10.5H4.57143V13.125H2.28571V10.5ZM2.28571 15.75H11.4286V18.375H2.28571V15.75Z" style="fill:white"/>
                        </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Desa</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    <div class="menu-item">
                        <a class="menu-link" href="{{url('dokumen-desa?type=RPJMDes')}}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen RPJMDES</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('dokumen-desa?type=RKPDes') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen RKPDES</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('dokumen-desa?type=SPGs') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen SPGs Desa</span>
                        </a>
                    </div>
                    <!-- <div class="menu-item">
                        <a class="menu-link" href="">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Daftar Alokasi Desa</span>
                        </a>
                    </div> -->
                </div>
            </div>	

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                        <span class="svg-icon svg-icon-2">
                        <svg width="16" height="21" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V21H16V10.5H6.85714V0H0ZM9.14286 0V7.875H16L9.14286 0ZM2.28571 5.25H4.57143V7.875H2.28571V5.25ZM2.28571 10.5H4.57143V13.125H2.28571V10.5ZM2.28571 15.75H11.4286V18.375H2.28571V15.75Z" style="fill:white"/>
                        </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen SKPD</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    <div class="menu-item">
                        <a class="menu-link" href="{{url('dokumen-skpd?type=Renstra')}}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Renstra</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('dokumen-skpd?type=Renja') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Renja</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('dokumen-skpd?type=Sektoral') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Data Sektoral</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('dokumen-skpd?type=Skpd') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Lainnya</span>
                        </a>
                    </div>
                </div>
            </div>	

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                        <span class="svg-icon svg-icon-2">

                        <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8269 9.8H11.7692C11.2184 9.8 10.6901 9.57875 10.3006 9.18493C9.91113 8.7911 9.69231 8.25696 9.69231 7.7V1.575C9.69231 1.52859 9.67407 1.48408 9.64161 1.45126C9.60916 1.41844 9.56513 1.4 9.51923 1.4H7.21514C7.13434 1.24869 7.04021 1.10504 6.93389 0.970812C6.435 0.34475 5.6938 0 4.84615 0C3.21577 0 2.07692 1.29544 2.07692 3.15V9.45C2.07692 10.5437 2.95702 11.2 3.80769 11.2C4.03567 11.2025 4.26185 11.159 4.47297 11.0719C4.68408 10.9849 4.87587 10.8561 5.03708 10.6931C5.1983 10.53 5.32569 10.3361 5.41179 10.1227C5.49789 9.90921 5.54095 9.68051 5.53846 9.45V2.8C5.53846 2.61435 5.46552 2.4363 5.33569 2.30503C5.20586 2.17375 5.02977 2.1 4.84615 2.1C4.66254 2.1 4.48645 2.17375 4.35662 2.30503C4.22679 2.4363 4.15385 2.61435 4.15385 2.8V9.45C4.15535 9.49637 4.14743 9.54257 4.13056 9.58572C4.1137 9.62887 4.08826 9.66807 4.05581 9.70087C4.02336 9.73368 3.9846 9.75941 3.94192 9.77646C3.89924 9.79351 3.85356 9.80152 3.80769 9.8C3.7112 9.8 3.46154 9.737 3.46154 9.45V3.15C3.46154 2.30388 3.82543 1.4 4.84615 1.4C6.13082 1.4 6.23077 2.71906 6.23077 3.12287V9.19319C6.23077 9.95662 5.99409 10.647 5.56399 11.1383C5.1274 11.6375 4.52034 11.9 3.80769 11.9C3.09505 11.9 2.48798 11.6375 2.05139 11.1383C1.6213 10.647 1.38462 9.95662 1.38462 9.19319V5.6C1.38462 5.41435 1.31168 5.2363 1.18184 5.10503C1.05201 4.97375 0.875919 4.9 0.692308 4.9C0.508696 4.9 0.332605 4.97375 0.202772 5.10503C0.0729393 5.2363 0 5.41435 0 5.6V9.19319C0 11.4468 1.43611 13.1145 3.46154 13.2851V18.2C3.46154 18.9426 3.7533 19.6548 4.27263 20.1799C4.79196 20.705 5.49632 21 6.23077 21H15.2308C15.9652 21 16.6696 20.705 17.1889 20.1799C17.7082 19.6548 18 18.9426 18 18.2V9.975C18 9.92859 17.9818 9.88408 17.9493 9.85126C17.9168 9.81844 17.8728 9.8 17.8269 9.8Z" style="fill:white"/>
                        <path d="M11.7693 8.40004H17.3861C17.4031 8.39996 17.4198 8.39479 17.4339 8.38517C17.4481 8.37555 17.4591 8.36191 17.4657 8.34597C17.4722 8.33003 17.4739 8.31249 17.4706 8.29557C17.4673 8.27864 17.4591 8.26308 17.4471 8.25085L11.2245 1.95916C11.2124 1.947 11.197 1.93872 11.1803 1.93538C11.1635 1.93203 11.1462 1.93376 11.1304 1.94036C11.1147 1.94695 11.1012 1.95811 11.0917 1.97243C11.0822 1.98675 11.077 2.0036 11.077 2.02085V7.70004C11.077 7.88569 11.1499 8.06374 11.2797 8.19501C11.4096 8.32629 11.5857 8.40004 11.7693 8.40004Z" style="fill:white"/>
                        </svg>


                        </span>
                   
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen daerah</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('rpjmd') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">RPJMD</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('rkpd') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">RKPD</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('lainnya') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Lainnya</span>
                        </a>
                    </div>
                </div>
            </div>	

            @endif
          
            @if(Auth::user()->id_role == 1)
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                        <span class="svg-icon svg-icon-2">
                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.3825 0C0.618387 0 0 0.743798 0 1.66288V4.60976V7.12863V16.2219C0 17.1409 0.618387 17.8777 1.3825 17.8777H8.67417V6.28667C8.67417 5.48403 9.18348 4.79571 9.84667 4.715C9.94744 4.70274 9.99017 4.715 9.975 4.715H15.6683H16.1525C16.3111 4.71538 16.4718 4.79032 16.5842 4.92549L19.6642 8.63014C19.7767 8.76662 19.8383 8.95488 19.8392 9.14935V9.73171V17.8567C20.4977 17.7303 21 17.0501 21 16.2219V4.60976C21 3.69067 20.3816 2.95954 19.6175 2.95389H8.5225V1.66288C8.5225 0.743798 7.90995 0 7.14583 0H1.3825ZM9.905 5.43769C9.54914 5.48099 9.275 5.8464 9.275 6.28667V20.151C9.275 20.6206 9.58457 21 9.975 21H18.5383C18.9288 21 19.2383 20.6206 19.2383 20.151V9.73171H16.66C16.1319 9.73171 15.6683 9.27349 15.6683 8.6582V5.43769H9.975C9.9506 5.43769 9.92872 5.4348 9.905 5.43769ZM16.1583 5.43769V8.6582C16.1583 8.91104 16.3619 9.14233 16.66 9.14233H19.2383L16.1583 5.43769Z" style="fill:white"/>
                        </svg>

                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Master</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('admin.perangkat_desa') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Perangkat Desa</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('admin.bidang_verifikator') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Bidang verifikator</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('admin.akun') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Akun</span>
                        </a>
                    </div>
                </div>
            </div>	
            @endif

            @if(Auth::user()->id_role == 2)
            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-opd/dokumen?type=Renstra') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="22" viewBox="0 0 17 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V22H17V11H7.28571V0H0ZM9.71429 0V8.25H17L9.71429 0ZM2.42857 5.5H4.85714V8.25H2.42857V5.5ZM2.42857 11H4.85714V13.75H2.42857V11ZM2.42857 16.5H12.1429V19.25H2.42857V16.5Z" style="fill:white"/>
                        </svg>

                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Renstra</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-opd/dokumen?type=Renja') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8269 9.8H11.7692C11.2184 9.8 10.6901 9.57875 10.3006 9.18493C9.91113 8.7911 9.69231 8.25696 9.69231 7.7V1.575C9.69231 1.52859 9.67407 1.48408 9.64161 1.45126C9.60916 1.41844 9.56513 1.4 9.51923 1.4H7.21514C7.13434 1.24869 7.04021 1.10504 6.93389 0.970812C6.435 0.34475 5.6938 0 4.84615 0C3.21577 0 2.07692 1.29544 2.07692 3.15V9.45C2.07692 10.5437 2.95702 11.2 3.80769 11.2C4.03567 11.2025 4.26185 11.159 4.47297 11.0719C4.68408 10.9849 4.87587 10.8561 5.03708 10.6931C5.1983 10.53 5.32569 10.3361 5.41179 10.1227C5.49789 9.90921 5.54095 9.68051 5.53846 9.45V2.8C5.53846 2.61435 5.46552 2.4363 5.33569 2.30503C5.20586 2.17375 5.02977 2.1 4.84615 2.1C4.66254 2.1 4.48645 2.17375 4.35662 2.30503C4.22679 2.4363 4.15385 2.61435 4.15385 2.8V9.45C4.15535 9.49637 4.14743 9.54257 4.13056 9.58572C4.1137 9.62887 4.08826 9.66807 4.05581 9.70087C4.02336 9.73368 3.9846 9.75941 3.94192 9.77646C3.89924 9.79351 3.85356 9.80152 3.80769 9.8C3.7112 9.8 3.46154 9.737 3.46154 9.45V3.15C3.46154 2.30388 3.82543 1.4 4.84615 1.4C6.13082 1.4 6.23077 2.71906 6.23077 3.12287V9.19319C6.23077 9.95662 5.99409 10.647 5.56399 11.1383C5.1274 11.6375 4.52034 11.9 3.80769 11.9C3.09505 11.9 2.48798 11.6375 2.05139 11.1383C1.6213 10.647 1.38462 9.95662 1.38462 9.19319V5.6C1.38462 5.41435 1.31168 5.2363 1.18184 5.10503C1.05201 4.97375 0.875919 4.9 0.692308 4.9C0.508696 4.9 0.332605 4.97375 0.202772 5.10503C0.0729393 5.2363 0 5.41435 0 5.6V9.19319C0 11.4468 1.43611 13.1145 3.46154 13.2851V18.2C3.46154 18.9426 3.7533 19.6548 4.27263 20.1799C4.79196 20.705 5.49632 21 6.23077 21H15.2308C15.9652 21 16.6696 20.705 17.1889 20.1799C17.7082 19.6548 18 18.9426 18 18.2V9.975C18 9.92859 17.9818 9.88408 17.9493 9.85126C17.9168 9.81844 17.8728 9.8 17.8269 9.8Z" style="fill:white"/>
                        <path d="M11.7693 8.40004H17.3861C17.4031 8.39996 17.4198 8.39479 17.4339 8.38517C17.4481 8.37555 17.4591 8.36191 17.4657 8.34597C17.4722 8.33003 17.4739 8.31249 17.4706 8.29557C17.4673 8.27864 17.4591 8.26308 17.4471 8.25085L11.2245 1.95916C11.2124 1.947 11.197 1.93872 11.1803 1.93538C11.1635 1.93203 11.1462 1.93376 11.1304 1.94036C11.1147 1.94695 11.1012 1.95811 11.0917 1.97243C11.0822 1.98675 11.077 2.0036 11.077 2.02085V7.70004C11.077 7.88569 11.1499 8.06374 11.2797 8.19501C11.4096 8.32629 11.5857 8.40004 11.7693 8.40004Z" style="fill:white"/>
                        </svg>

                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Renja</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-opd/dokumen?type=Data-sektoral') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="21" viewBox="0 0 17 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.7 3.5V19.25H15.3V21H1.7C0.765 21 0 20.2125 0 19.25V3.5H1.7ZM11.05 6.125H15.725L11.05 1.3125V6.125ZM5.1 0H11.9L17 5.25V15.75C17 16.7213 16.2435 17.5 15.3 17.5H5.1C4.1565 17.5 3.4 16.7125 3.4 15.75V1.75C3.4 0.77875 4.1565 0 5.1 0ZM12.75 14V12.25H5.1V14H12.75ZM15.3 10.5V8.75H5.1V10.5H15.3Z" style="fill:white"/>
                        </svg>
                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Data Sektoral SKPD</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-opd/dokumen?type=Data-lainnya') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="21" viewBox="0 0 17 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.2273 0H3.28409C3.17784 0 3.09091 0.084375 3.09091 0.1875V1.5C3.09091 1.60312 3.17784 1.6875 3.28409 1.6875H15.2614V17.8125C15.2614 17.9156 15.3483 18 15.4545 18H16.8068C16.9131 18 17 17.9156 17 17.8125V0.75C17 0.335156 16.6547 0 16.2273 0ZM13.1364 3H0.772727C0.345312 3 0 3.33516 0 3.75V16.1883C0 16.3875 0.0821021 16.5773 0.226988 16.718L4.41179 20.7797C4.46491 20.8312 4.52528 20.8734 4.59048 20.9086V20.9531H4.6919C4.77642 20.9836 4.86577 21 4.95753 21H13.1364C13.5638 21 13.9091 20.6648 13.9091 20.25V3.75C13.9091 3.33516 13.5638 3 13.1364 3ZM5.3608 19.5H5.35597L1.73864 15.9891V15.9844H5.3608V19.5Z" style="fill:white"/>
                        </svg>
                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen Lainnya</span>
                </a>
            </div>
            

            @endif

            @if(Auth::user()->id_role == 3)
            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-desa/dokumen?type=RPJMDes') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="22" viewBox="0 0 17 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V22H17V11H7.28571V0H0ZM9.71429 0V8.25H17L9.71429 0ZM2.42857 5.5H4.85714V8.25H2.42857V5.5ZM2.42857 11H4.85714V13.75H2.42857V11ZM2.42857 16.5H12.1429V19.25H2.42857V16.5Z" style="fill:white"/>
                        </svg>

                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen RPJMDes</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-desa/dokumen?type=RKPDes') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8269 9.8H11.7692C11.2184 9.8 10.6901 9.57875 10.3006 9.18493C9.91113 8.7911 9.69231 8.25696 9.69231 7.7V1.575C9.69231 1.52859 9.67407 1.48408 9.64161 1.45126C9.60916 1.41844 9.56513 1.4 9.51923 1.4H7.21514C7.13434 1.24869 7.04021 1.10504 6.93389 0.970812C6.435 0.34475 5.6938 0 4.84615 0C3.21577 0 2.07692 1.29544 2.07692 3.15V9.45C2.07692 10.5437 2.95702 11.2 3.80769 11.2C4.03567 11.2025 4.26185 11.159 4.47297 11.0719C4.68408 10.9849 4.87587 10.8561 5.03708 10.6931C5.1983 10.53 5.32569 10.3361 5.41179 10.1227C5.49789 9.90921 5.54095 9.68051 5.53846 9.45V2.8C5.53846 2.61435 5.46552 2.4363 5.33569 2.30503C5.20586 2.17375 5.02977 2.1 4.84615 2.1C4.66254 2.1 4.48645 2.17375 4.35662 2.30503C4.22679 2.4363 4.15385 2.61435 4.15385 2.8V9.45C4.15535 9.49637 4.14743 9.54257 4.13056 9.58572C4.1137 9.62887 4.08826 9.66807 4.05581 9.70087C4.02336 9.73368 3.9846 9.75941 3.94192 9.77646C3.89924 9.79351 3.85356 9.80152 3.80769 9.8C3.7112 9.8 3.46154 9.737 3.46154 9.45V3.15C3.46154 2.30388 3.82543 1.4 4.84615 1.4C6.13082 1.4 6.23077 2.71906 6.23077 3.12287V9.19319C6.23077 9.95662 5.99409 10.647 5.56399 11.1383C5.1274 11.6375 4.52034 11.9 3.80769 11.9C3.09505 11.9 2.48798 11.6375 2.05139 11.1383C1.6213 10.647 1.38462 9.95662 1.38462 9.19319V5.6C1.38462 5.41435 1.31168 5.2363 1.18184 5.10503C1.05201 4.97375 0.875919 4.9 0.692308 4.9C0.508696 4.9 0.332605 4.97375 0.202772 5.10503C0.0729393 5.2363 0 5.41435 0 5.6V9.19319C0 11.4468 1.43611 13.1145 3.46154 13.2851V18.2C3.46154 18.9426 3.7533 19.6548 4.27263 20.1799C4.79196 20.705 5.49632 21 6.23077 21H15.2308C15.9652 21 16.6696 20.705 17.1889 20.1799C17.7082 19.6548 18 18.9426 18 18.2V9.975C18 9.92859 17.9818 9.88408 17.9493 9.85126C17.9168 9.81844 17.8728 9.8 17.8269 9.8Z" style="fill:white"/>
                        <path d="M11.7693 8.40004H17.3861C17.4031 8.39996 17.4198 8.39479 17.4339 8.38517C17.4481 8.37555 17.4591 8.36191 17.4657 8.34597C17.4722 8.33003 17.4739 8.31249 17.4706 8.29557C17.4673 8.27864 17.4591 8.26308 17.4471 8.25085L11.2245 1.95916C11.2124 1.947 11.197 1.93872 11.1803 1.93538C11.1635 1.93203 11.1462 1.93376 11.1304 1.94036C11.1147 1.94695 11.1012 1.95811 11.0917 1.97243C11.0822 1.98675 11.077 2.0036 11.077 2.02085V7.70004C11.077 7.88569 11.1499 8.06374 11.2797 8.19501C11.4096 8.32629 11.5857 8.40004 11.7693 8.40004Z" style="fill:white"/>
                        </svg>

                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen RKPDes</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link" href="{{ url('/akun-desa/dokumen?type=SDGS') }}">
                    <span class="menu-icon">          
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="21" viewBox="0 0 17 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.2273 0H3.28409C3.17784 0 3.09091 0.084375 3.09091 0.1875V1.5C3.09091 1.60312 3.17784 1.6875 3.28409 1.6875H15.2614V17.8125C15.2614 17.9156 15.3483 18 15.4545 18H16.8068C16.9131 18 17 17.9156 17 17.8125V0.75C17 0.335156 16.6547 0 16.2273 0ZM13.1364 3H0.772727C0.345312 3 0 3.33516 0 3.75V16.1883C0 16.3875 0.0821021 16.5773 0.226988 16.718L4.41179 20.7797C4.46491 20.8312 4.52528 20.8734 4.59048 20.9086V20.9531H4.6919C4.77642 20.9836 4.86577 21 4.95753 21H13.1364C13.5638 21 13.9091 20.6648 13.9091 20.25V3.75C13.9091 3.33516 13.5638 3 13.1364 3ZM5.3608 19.5H5.35597L1.73864 15.9891V15.9844H5.3608V19.5Z" style="fill:white"/>
                        </svg>

                        </span>
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Dokumen SDGs Desa</span>
                </a>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                        <span class="svg-icon svg-icon-2">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.11917 0C0.500599 0 0 0.602122 0 1.34614V3.73171V5.7708V13.132C0 13.876 0.500599 14.4724 1.11917 14.4724H7.02194V5.08921C7.02194 4.43946 7.43425 3.88224 7.97111 3.81691C8.05269 3.80698 8.08728 3.81691 8.075 3.81691H12.6839H13.0758C13.2042 3.81722 13.3343 3.87788 13.4253 3.9873L15.9186 6.9863C16.0097 7.09679 16.0596 7.24919 16.0603 7.40662V7.87805V14.4554C16.5933 14.3531 17 13.8025 17 13.132V3.73171C17 2.98769 16.4994 2.39582 15.8808 2.39125H6.89917V1.34614C6.89917 0.602122 6.40329 0 5.78472 0H1.11917ZM8.01833 4.40194C7.73026 4.43699 7.50833 4.7328 7.50833 5.08921V16.3127C7.50833 16.6929 7.75893 17 8.075 17H15.0072C15.3233 17 15.5739 16.6929 15.5739 16.3127V7.87805H13.4867C13.0592 7.87805 12.6839 7.50711 12.6839 7.00902V4.40194H8.075C8.05525 4.40194 8.03754 4.3996 8.01833 4.40194ZM13.0806 4.40194V7.00902C13.0806 7.2137 13.2454 7.40094 13.4867 7.40094H15.5739L13.0806 4.40194Z" style="fill:white"/>
                        </svg>


                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Data Referensi</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('/akun-desa/referensi/daftar-alokasi-skpd') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title" style="color:#ffffff;font-family: 'Nunito Sans', sans-serif;font-weight:bold;">Daftar Alokasi SKPD</span>
                        </a>
                    </div>
                 
                </div>
            </div>	

            @endif

          

        </div>
        <!--end::Menu-->
    </div>
</div>

@section('script')
<script>
    $(document).ready(function(){
        $(".menu-link").mousemove(function(){
            $(this).css("background", "#282EAD");
            }, function(){
            $(this).css("background", "none");
        });
});
</script>
@endsection

