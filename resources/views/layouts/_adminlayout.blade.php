<!DOCTYPE html>

<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>Metronic | Dashboard</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />
    <!--end::Web font -->
    <link href="{{ asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery.loading.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery.toast.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <!--begin::Page Vendors Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-iconpicker.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/jquery.bootstrap.treeselect.css')}}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/site.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />
    <script src="{{ asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>

    <style type="text/css">
        html,
        body,
        .btn {
            font-family: sans-serif !important;
        }
    </style>
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">

        <!-- BEGIN: Header -->
        <header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
            <div class="m-container m-container--fluid m-container--full-height">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- BEGIN: Brand -->
                    <div class="m-stack__item m-brand  m-brand--skin-dark ">
                        <div class="m-stack m-stack--ver m-stack--general">
                            <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                <a href="{{route('index.admin')}}" class="m-brand__logo-wrapper">
                                    <img style="width: 125px;" alt="" src="{{ asset('assets/demo/media/img/logo/logo_default_dark.png')}}" />
                                </a>
                            </div>
                            <div class="m-stack__item m-stack__item--middle m-brand__tools">

                                <!-- BEGIN: Left Aside Minimize Toggle -->
                                <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                                    <span></span>
                                </a>

                                <!-- END -->

                                <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                                <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                    <span></span>
                                </a>

                                <!-- END -->

                                <!-- BEGIN: Responsive Header Menu Toggler -->
                                <!--                                    <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                                <span></span>
                                            </a>-->

                                <!-- END -->

                                <!-- BEGIN: Topbar Toggler -->
                                <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                    <i class="flaticon-more"></i>
                                </a>

                                <!-- BEGIN: Topbar Toggler -->
                            </div>
                        </div>
                    </div>

                    <!-- END: Brand -->
                    <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                        <!-- BEGIN: Horizontal Menu -->
                        <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                        <!-- BEGIN: Topbar -->
                        <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                            <div class="m-stack__item m-topbar__nav-wrapper">
                                <ul class="m-topbar__nav m-nav m-nav--inline">
                                    <li class="m-nav__item" m-dropdown-toggle="click" id="m_locksystem" m-quicksearch-mode="dropdown" m-dropdown-persistent="1">
                                        <a href="javascript:;" title="Khóa hệ thống" class="m-nav__link m-dropdown__toggle">
                                            <span class="m-nav__link-icon"><i class="flaticon-safe-shield-protection"></i></span>
                                        </a>
                                    </li>
                                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
                                        <a href="#" class="m-nav__link m-dropdown__toggle">
                                            <span class="m-topbar__userpic">
                                                <img src="{{ asset('assets/app/media/img/users/user4.jpg')}}" class="m--img-rounded m--marginless" alt="" />
                                            </span>
                                            <span class="m-topbar__username m--hide">Nick</span>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__header m--align-center" style="background: url({{ asset('assets/app/media/img/misc/user_profile_bg.jpg')}}); background-size: cover;">
                                                    <div class="m-card-user m-card-user--skin-dark">
                                                        <div class="m-card-user__pic">
                                                            <img src="{{ asset('assets/app/media/img/users/user4.jpg')}}" class="m--img-rounded m--marginless" alt="" />
                                                        </div>
                                                        <div class="m-card-user__details">
                                                            <span class="m-card-user__name m--font-weight-500">{{{Auth::guard('admin')->user()->username}}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav m-nav--skin-light">
                                                            <li class="m-nav__section m--hide">
                                                                <span class="m-nav__section-text">Section</span>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="{{ route('change_password.admin') }}" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-rotate"></i>
                                                                    <span class="m-nav__link-title">
                                                                        <span class="m-nav__link-wrap">
                                                                            <span class="m-nav__link-text">Đổi mật
                                                                                khẩu</span>
                                                                        </span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__separator m-nav__separator--fit">
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="javascript:;" onclick="event.preventDefault();
                                                                        document.getElementById('logout-form').submit();" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">{{ __('Logout') }}</a>
                                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- END: Topbar -->
                    </div>
                </div>
            </div>
        </header>

        <!-- END: Header -->

        <!-- begin::Body -->
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

            <!-- BEGIN: Left Aside -->
            <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
            <div id="m_aside_left" class="m-grid__item m-aside-left  m-aside-left--skin-dark ">

                <!-- BEGIN: Aside Menu -->
                <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                        <li class="m-menu__item {{url()->current() == route('index.admin')? 'm-menu__item--active' :''}}" aria-haspopup="true">
                            <a href="{{route('index.admin')}}" data-path="/" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-buildings"></i><span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">Trang chủ</span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        @if(\Auth::guard('admin')->user()->is_super_admin)
                        <li class="m-menu__item" aria-haspopup="true">
                            <a href="{{ route('config.admin') }}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-cogwheel"></i><span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">Cấu hình</span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-network"></i><span class="m-menu__link-text">Tài
                                    khoản/ quyền</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('user.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Tài khoản</span></a></li>
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('group.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Quyền</span></a></li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        <li class="m-menu__item" aria-haspopup="true">
                            <a href="{{ route('menu.admin') }}" class="m-menu__link">
                                <i class="m-menu__link-icon flaticon-folder-1"></i><span class="m-menu__link-text">Danh
                                    mục</span>
                            </a>
                        </li>
                        <li class="m-menu__item" aria-haspopup="true">
                            <a href="{{ route('product.admin') }}" class="m-menu__link">
                                <i class="m-menu__link-icon flaticon-suitcase"></i><span class="m-menu__link-text">Sản
                                    phẩm</span>
                            </a>
                        </li>
                        <li class="m-menu__item aria-haspopup=" true">
                            <a href="{{ route('news.admin') }}" class="m-menu__link">
                                <i class="m-menu__link-icon la la-file-text"></i><span class="m-menu__link-text">Bài
                                    viết</span>
                            </a>
                        </li>
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-coins"></i><span class="m-menu__link-text">Quản
                                    lý thu chi</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('income.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Thu</span></a></li>
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('expense.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Chi</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="m-menu__item" aria-haspopup="true">
                            <a href="{{ route('class.admin') }}" class="m-menu__link">
                                <i class="m-menu__link-icon flaticon-presentation-1"></i><span class="m-menu__link-text">Lớp học</span>
                            </a>
                        </li>
                        <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-bell"></i><span class="m-menu__link-text">Đơn hàng</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('create_order.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Tạo đơn</span></a></li>
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{ route('order.admin') }}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Danh sách</span></a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- END: Aside Menu -->
            </div>

            <!-- END: Left Aside -->
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                @yield('content')
            </div>
        </div>

        <!-- end:: Body -->
    </div>

    <!-- end:: Page -->

    <!-- begin::Scroll Top -->
    <div id="m_scroll_top" class="m-scroll-top">
        <i class="la la-arrow-up"></i>
    </div>

    <!-- end::Scroll Top -->

    <script src="{{ asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.toast.js')}}"></script>
    <!--end::Global Theme Bundle -->
    <!--begin::Page Scripts -->
    <script src="{{ asset('js/jquery.loading.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-menu-editor.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-iconpicker.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.bootstrap.treeselect.js')}}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('js/fileinput.js')}}"></script>
    <script src="{{ asset('js/jquery.inputmask.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js" type="text/javascript"></script>
    <!--end::Page Scripts -->
    @yield('scripts')
    <script src="{{ asset('js/utils.js')}}" type="text/javascript"></script>
    <!--end::Page Scripts -->
    <script type="text/javascript">
        $(function() {
            function csrfSafeMethod(method) {
                return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
            }
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
                        xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr(
                            'content'));
                    }
                },
                error: function(x, e) {
                    if (x.status == 500 && x.statusText == "SessionTimeout") {
                        window.location.href = "{{route('home')}}";
                    }
                }
            });

            var current_href = location.protocol + '//' + location.host + location.pathname;
            $('a[href="' + current_href + '"]').parent('.m-menu__item').addClass('m-menu__item--active');
            $('a[href="' + current_href + '"]').parents('.m-menu__item--submenu').addClass('m-menu__item--open');
        });
    </script>
</body>

<!-- end::Body -->

</html>