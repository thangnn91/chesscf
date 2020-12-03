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
        <meta charset="utf-8"/>
        <title>Metronic | Dashboard</title>
        <meta name="description" content="Latest updates and statistic charts">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
WebFont.load({
    google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
    active: function () {
        sessionStorage.fonts = true;
    }
});
        </script>

        <!--end::Web font -->

        <link href="{{ asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css"/>

        <!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
        <!--begin::Page Vendors Styles -->
        <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/site.css')}}" rel="stylesheet" type="text/css"/>

        <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />
        <script src="{{ asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
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
                                    <a href="{{route('index.user')}}" class="m-brand__logo-wrapper">
                                        <img alt="" src="{{ asset('assets/demo/media/img/logo/logo_default_dark.png')}}"/>
                                    </a>
                                </div>
                                <div class="m-stack__item m-stack__item--middle m-brand__tools">

                                    <!-- BEGIN: Left Aside Minimize Toggle -->
                                    <a href="javascript:;" id="m_aside_left_minimize_toggle"
                                       class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                                        <span></span>
                                    </a>

                                    <!-- END -->

                                    <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                                    <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                                       class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>

                                    <!-- END -->

                                    <!-- BEGIN: Responsive Header Menu Toggler -->
                                    <!--                                    <a id="m_aside_header_menu_mobile_toggle" href="javascript:;"
                                                                           class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                                                            <span></span>
                                                                        </a>-->

                                    <!-- END -->

                                    <!-- BEGIN: Topbar Toggler -->
                                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;"
                                       class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                        <i class="flaticon-more"></i>
                                    </a>

                                    <!-- BEGIN: Topbar Toggler -->
                                </div>
                            </div>
                        </div>

                        <!-- END: Brand -->
                        <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                            @php
                            $user = Auth::guard('user')->user();
                            $userBalance = \DB::table('dbo_balance')->where('userid', Auth::guard('user')->user()->id)->first();                       
                            @endphp

                            @if(!$user->secretkey)
                            <script type="text/javascript">
                                $(function () {
                                    if ($("#this_is_home_user").length && !getCookie('302fd98880d1b61b8d87b800fe3f8c70')) {
                                        swal({
                                            title: "Đăng ký bảo mật?",
                                            html: "Tài khoản của bạn chưa đăng ký bảo mật<br/>Bạn có muốn đăng ký ngay?",
                                            type: "warning",
                                            closeOnConfirm: true,
                                            showCancelButton: true,
                                            confirmButtonText: "Đồng ý",
                                            cancelButtonText: "Quay lại",
                                            reverseButtons: !0
                                        }).then(function (e) {
                                            setCookie('302fd98880d1b61b8d87b800fe3f8c70', '_', 0.5);
                                            e.value && (window.location.href = "{{route('secure.user')}}");
                                        });
                                    }
                                });
                            </script>
                            @endif
                            <!-- BEGIN: Horizontal Menu -->
                            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark"
                                    id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                            <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                                <ul class="m-menu__nav  m-menu__nav--submenu-arrow">
                                    <li class="m-menu__item m-menu__item--submenu m-menu__item--rel m-menu__item--open-dropdown m-menu__item--hover" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-network"></i>
                                            <span class="m-menu__link-title"> 
                                                <span class="m-menu__link-wrap">
                                                    <span class="m-menu__link-text" style="cursor:help;">Mời bạn bè tham gia để nhận được ưu đãi</span>
                                                    <span class="m-menu__link-badge">
                                                        <span onclick="copyRef(this);" data-linkref="{{route('home.identify')}}?fid={{$user->ref_id}}" class="m-badge m-badge--brand m-badge--wide">Copy link</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- BEGIN: Topbar -->
                            <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                                <div class="m-stack__item m-topbar__nav-wrapper">
                                    <ul class="m-topbar__nav m-nav m-nav--inline">
                                        <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"
                                            m-dropdown-toggle="click">
                                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                                <span class="m-nav__link-icon m-nav__link-icon-custome">Số dư:&nbsp;{{$userBalance->realbalance%1000==0?number_format($userBalance->realbalance/1000, 0, '.', ','):number_format((double)$userBalance->realbalance/1000, 1, '.', ',')}} LTR <i style="font-size:1rem;" class="m-menu__hor-arrow la la-angle-down"></i></span>

                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__header" style="background: url({{asset('assets/app/media/img/misc/quick_actions_bg.jpg')}}); background-size: cover;">
                                                        <span class="m-dropdown__header-subtitle">Số dư thanh toán:&nbsp;{{$userBalance->paybalance%1000==0?number_format($userBalance->paybalance/1000, 0, '.', ','):number_format((double)$userBalance->paybalance/1000, 1, '.', ',')}} LTR</span>
                                                        <span class="m-dropdown__header-subtitle">Số dư game:&nbsp;{{$userBalance->gamebalance%1000==0?number_format($userBalance->gamebalance/1000, 0, '.', ','):number_format((double)$userBalance->gamebalance/1000, 1, '.', ',')}} LTR</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                            m-dropdown-toggle="click">
                                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-topbar__userpic">
                                                    @if($user->avatar &&
                                                    file_exists(public_path(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)))
                                                    <img src="{{asset(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)}}"
                                                         class="m--img-rounded m--marginless" alt=""/>
                                                    @else
                                                    <img src="{{asset(\Config::get('constants.avatar_default'))}}"
                                                         class="m--img-rounded m--marginless" alt=""/>
                                                    @endif
                                                </span>
                                                <span class="m-topbar__username m--hide">Nick</span>
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__header m--align-center"
                                                         style="background: url({{ asset('assets/app/media/img/misc/user_profile_bg.jpg')}}); background-size: cover;">
                                                        <div class="m-card-user m-card-user--skin-dark">
                                                            <div class="m-card-user__pic">
                                                                @if($user &&
                                                                file_exists(public_path(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)))
                                                                <img src="{{asset(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)}}"
                                                                     class="m--img-rounded m--marginless" alt=""/>
                                                                @else
                                                                <img src="{{asset(\Config::get('constants.avatar_default'))}}"
                                                                     class="m--img-rounded m--marginless" alt=""/>
                                                                @endif
                                                            </div>
                                                            <div class="m-card-user__details">
                                                                <span class="m-card-user__name m--font-weight-500">{{{$user->fullname}}}
                                                                    @if($user->status == 3)
                                                                    <i class="fa fa-check-circle text-success" data-toggle="m-tooltip" data-placement="bottom" title="" data-original-title="Đã chứng thực"></i>
                                                                    @elseif($user->status == 1)
                                                                    <i class="la la-warning text-warning" data-toggle="m-tooltip" data-placement="bottom" title="" data-original-title="Chờ duyệt yêu cầu chứng thực"></i>
                                                                    @endif
                                                                </span>
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
                                                                    <a href="{{ route('update.profile.user')}}"
                                                                       class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                        <span class="m-nav__link-title">
                                                                            <span class="m-nav__link-wrap">
                                                                                <span class="m-nav__link-text">Thông tin tài khoản</span>
                                                                            </span>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                @if($user->status == 0 || $user->status == 2)
                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('verify.user')}}" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-photo-camera"></i>
                                                                        <span class="m-nav__link-text">Chứng thực</span>
                                                                    </a>
                                                                </li>
                                                                @endif

                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('secure.user') }}" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-lock"></i>
                                                                        <span class="m-nav__link-text">Bảo mật</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('transactionHistory.user') }}" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-statistics"></i>
                                                                        <span class="m-nav__link-text">Lịch sử giao dịch</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('tradeHistory.user') }}" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-statistics"></i>
                                                                        <span class="m-nav__link-text">Lịch sử mua hàng</span>
                                                                    </a>
                                                                </li>
                                                                <li class="m-nav__separator m-nav__separator--fit">
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="javascript:;" onclick="event.preventDefault();
                                                                            document.getElementById('logout-form').submit();"
                                                                       class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">{{ __('Logout') }}</a>
                                                                    <form id="logout-form" action="{{ route('logout') }}"
                                                                          method="POST" style="display: none;">
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
                <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i
                        class="la la-close"></i></button>
                <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

                    <!-- BEGIN: Aside Menu -->
                    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
                         m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{route('index.user')}}" data-path="/" class="m-menu__link ">
                                    <i class="m-menu__link-icon flaticon-buildings"></i><span class="m-menu__link-title">
                                        <span class="m-menu__link-wrap">
                                            <span class="m-menu__link-text">Trang chủ</span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{ route('cashin.user')}}" class="m-menu__link">
                                    <i class="m-menu__link-icon la la-money"></i><span class="m-menu__link-text">Nạp tiền</span>
                                </a>
                            </li>
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{ route('cashout.user')}}" class="m-menu__link">
                                    <i class="m-menu__link-icon la la-money"></i><span class="m-menu__link-text">Rút tiền</span>
                                </a>
                            </li>
                            <!--@php
                            $listProduct = \DB::table('dbo_bank')->where('currency', '<>', 'VND')->where('active', 1)->get();
                            @endphp
                            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                                        class="m-menu__link-icon flaticon-coins"></i><span
                                        class="m-menu__link-text">Mua vào</span><i
                                        class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu"><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        @foreach ($listProduct as $item)
                                        @if($item->buyactive == 1)
                                        <li class="m-menu__item" aria-haspopup="true"><a href="{{ route('buy.user')}}?product={{$item->bankcode}}"
                                                                                         class="m-menu__link "><i
                                                    class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                                    class="m-menu__link-text">{{$item->alias}}</span></a></li>
                                        @endif
                                        @endforeach                                       
                                    </ul>
                                </div>
                            </li>

                            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                                        class="m-menu__link-icon fa fa-donate"></i><span
                                        class="m-menu__link-text">Bán ra</span><i
                                        class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu"><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        @foreach ($listProduct as $item)
                                        @if($item->saleactive == 1)
                                        <li class="m-menu__item" aria-haspopup="true"><a href="{{ route('buy.user')}}?product={{$item->bankcode}}"
                                                                                         class="m-menu__link "><i
                                                    class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                                    class="m-menu__link-text">{{$item->alias}}</span></a></li>
                                        @endif
                                        @endforeach                                       
                                    </ul>
                                </div>
                            </li>-->

                            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                                        class="m-menu__link-icon flaticon-gift"></i><span
                                        class="m-menu__link-text">Tiện ích</span><i
                                        class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu"><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item" aria-haspopup="true"><a href="{{ route('lottery.user')}}"
                                                                                         class="m-menu__link "><i
                                                    class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                                    class="m-menu__link-text">Vietlott</span></a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                                        class="m-menu__link-icon la la-gamepad"></i><span
                                        class="m-menu__link-text">Trò chơi</span><i
                                        class="m-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="m-menu__submenu"><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item" aria-haspopup="true"><a href="{{ route('wheel.game.user')}}"
                                                                                         class="m-menu__link "><i
                                                    class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                                    class="m-menu__link-text">Vòng quay may mắn</span></a></li>
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

            <!-- begin::Footer -->
            <footer class="m-grid__item m-footer ">
                <div class="m-container m-container--fluid m-container--full-height m-page__container">
                    <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                        </div>
                        <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
                            <ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
                                <li class="m-nav__item">
                                    <a href="#" class="m-nav__link">
                                        <span class="m-nav__link-text">Về chúng tôi</span>
                                    </a>
                                </li>
                                <li class="m-nav__item">
                                    <a href="#" class="m-nav__link">
                                        <span class="m-nav__link-text">Điều khoản</span>
                                    </a>
                                </li>
                                <li class="m-nav__item m-nav__item">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- end::Footer -->
        </div>

        <!-- end:: Page -->

        <!-- begin::Scroll Top -->
        <div id="m_scroll_top" class="m-scroll-top">
            <i class="la la-arrow-up"></i>
        </div>
        <!-- Common Modal -->
        <div class="modal fade" id="common_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            html, body, .btn {
                font-family: sans-serif !important;
            }
        </style>
        {{--<script src="{{ asset('js/app.js') }}"></script>--}}
        <!-- begin::Global Theme Bundle -->
        <script src="{{ asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
        <script src="{{ asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
        <!--end::Global Theme Bundle -->
        <!--<script src="{{ asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>-->
        <!--begin::Page Scripts -->
        <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
        <script src="{{ asset('assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}"
        type="text/javascript"></script>
        <!--end::Page Scripts -->
        <script src="{{ asset('js/utils.js')}}" type="text/javascript"></script>

        <!--end::Page Scripts -->
        <script type="text/javascript">
                                                                        toastr.options = {
                                                                            "closeButton": false,
                                                                            "debug": false,
                                                                            "newestOnTop": false,
                                                                            "progressBar": false,
                                                                            "positionClass": "toast-bottom-right",
                                                                            "preventDuplicates": false,
                                                                            "onclick": null,
                                                                            "showDuration": "300",
                                                                            "hideDuration": "1000",
                                                                            "timeOut": "10000",
                                                                            "extendedTimeOut": "1000",
                                                                            "showEasing": "swing",
                                                                            "hideEasing": "linear",
                                                                            "showMethod": "fadeIn",
                                                                            "hideMethod": "fadeOut"
                                                                        };
        </script>
    </body>
    <!-- end::Body -->
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Change active for menu
            $('#m_ver_menu li a[href="' + location.href + '"]').parent().addClass('m-menu__item--active');
            $('#m_ver_menu a[href="' + location.href + '"]').parents('li.m-menu__item--submenu').addClass('m-menu__item--open');
            //Calculate session timeout
            calSessionTimeout("{{config('session.lifetime')}}");
        });

        $(document).ajaxComplete(function (event, xhr, settings) {
            //Calculate session timeout
            calSessionTimeout("{{config('session.lifetime')}}");
        });
        function copyRef(t) {
            var linkRef = $(t).data('linkref');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(linkRef).select();
            document.execCommand("copy");
            $temp.remove();
        }
        </script>
    </html>