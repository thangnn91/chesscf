<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{Session::get('dbo_system_config_data')['shop_name']->value}} | @yield('title')</title>
    <meta content='Chess art coffee - Không chỉ là cờ mà còn là nghệ thuật, Cờ và handmade, Đồ uống cực chất, không gian yên tĩnh' name='description' />
    <meta property="og:image" content="{{asset('newtheme/images/seo_img.jpg')}}" />
    <meta property="og:image:secure_url" content="{{asset('newtheme/images/seo_img.jpg')}}" />
    <link href="{{asset('newtheme/plugin/bootstrap/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/bootstrap/datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/font-awesome/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/form-field/jquery.formstyler.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/revolution-plugin/extralayers.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/revolution-plugin/settings.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/owl-carousel/owl.theme.default.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/slick-slider/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/magnific/magnific-popup.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/scroll-bar/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/plugin/animation/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/css/theme.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/css/responsive.css')}}" rel="stylesheet">
    <link href="{{asset('newtheme/css/app.css')}}" rel="stylesheet">
    <link href="{{ asset('css/jquery.toast.css') }}" rel="stylesheet" />
    <script src="{{asset('newtheme/js/jquery.min.js')}}"></script>
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <style>
        .custom-tooltip+.tooltip>.tooltip-inner {
            background-color: #edc878;
            font-size: 14px;
            color: #000;
            font-family: 'Quicksand', sans-serif;
        }

        .custom-tooltip+.tooltip>.tooltip-arrow {
            border-top-color: #edc878;
        }
    </style>
</head>

<body>
    <!-- Page pre loader -->
    <div id="pre-loader">
        <div class="loader-holder">
            <div class="frame">
                <img src="{{asset('newtheme/images/Preloader.gif')}}" alt="Laboom" />
            </div>
        </div>
    </div>
    <div class="wrapper">
        <!-- Start Header -->
        <header>
            <div class="header-part header-reduce sticky">
                <div class="header-top">
                    <div class="container">
                        <div class="header-top-inner">
                            <div class="header-top-left">
                                <a href="#" class="top-cell"><img src="{{asset('newtheme/images/fon.png')}}" alt=""> <span>{{Session::get('dbo_system_config_data')['mobile']->value}}</span></a>
                                <a href="#" class="top-email"><span>{{Session::get('dbo_system_config_data')['email']->value}}</span></a>
                            </div>
                            <div class="header-top-right">
                                <div class="social-top">
                                    <ul>
                                        <li><a target="_blank" href="{{Session::get('dbo_system_config_data')['facebook']->value}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom">
                    <div class="container">
                        <div class="header-info">
                            <div class="header-info-inner">
                                <div class="shop-cart header-collect">
                                    <a href="javascript:;"><img src="{{asset('newtheme/images/icon-basket.png')}}" alt="">0 sản phẩm - 0<sup>đ</sup></a>
                                    <div class="cart-wrap">
                                        <div class="cart-blog">
                                            <div class="picked-item">

                                            </div>

                                            <div class="subtotal">
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <h6>Thành tiền :</h6>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <span id="header_total">0<sup>đ</sup></span>
                                                </div>
                                            </div>
                                            <div class="cart-btn">
                                                <a href="#" class="btn-black view">Xóa tất cả</a>
                                                <a href="#" class="btn-main checkout">Thanh toán</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-part">
                                    <a href="#"></a>
                                    <div class="search-box">
                                        <input type="text" name="txt" placeholder="Search">
                                        <input type="submit" name="submit" value=" ">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-icon">
                            <a href="#" class="hambarger">
                                <span class="bar-1"></span>
                                <span class="bar-2"></span>
                                <span class="bar-3"></span>
                            </a>
                        </div>
                        <div class="menu-main">
                            <ul>
                                <?php

                                function buildTree(array &$elements, $parentId = '')
                                {
                                    $branch = array();

                                    foreach ($elements as $element) {
                                        if ($element['parentid'] == $parentId) {
                                            $children = buildTree($elements, $element['id']);
                                            if ($children) {
                                                $element['children'] = $children;
                                            }
                                            $branch[] = $element;
                                            unset($elements[$element['id']]);
                                        }
                                    }
                                    return $branch;
                                }

                                $menus = DB::table('dbo_menu')->orderBy('index')->get()->toArray();
                                $treeMenu = array();
                                if (count($menus)) {
                                    $menus = json_decode(json_encode($menus), true);
                                    $treeMenu = buildTree($menus);
                                }
                                ?>
                                @foreach($treeMenu as $itm)
                                @if(isset($itm['children']))
                                <li class="has-child">
                                    <a href="{{asset($itm['href'])}}">{{$itm['text']}}</a>
                                    <ul class="drop-nav">
                                        @foreach($itm['children'] as $el)
                                        <li><a href="{{asset($el['href'])}}">{{$el['text']}}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                @else
                                <li>
                                    <a href="{{asset($itm['href'])}}">{{$itm['text']}}</a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="logo">
                            <a href="{{route('home')}}"><img src="{{asset('newtheme/images/'). '/' . Session::get('dbo_system_config_data')['logo']->value}}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->
        <!-- Start Main -->
        <main>
            <div class="main-part">
                @yield('content')

            </div>
        </main>
        <!-- End Main -->
        <!-- Start Footer -->
        <footer>
            <div class="footer-part wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="icon-default icon-dark">
                    <img style="max-width: 75%" src="{{asset('newtheme/images') . '/' . Session::get('dbo_system_config_data')['footer_logo']->value}}" alt="">
                </div>
                <div class="container">
                    <div class="footer-inner">
                        <div class="footer-info">
                            <h3>{{Session::get('dbo_system_config_data')['shop_name']->value}}</h3>
                            <p>{{Session::get('dbo_system_config_data')['address']->value}}</p>
                            <p><a href="#">{{Session::get('dbo_system_config_data')['mobile']->value}}</a></p>
                            <p><a href="#">{{Session::get('dbo_system_config_data')['email']->value}}</a></p>
                        </div>
                    </div>
                    <div class="copy-right">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 copyright-before">
                                <span>Copyright © 2020 Tiện ích bán hàng. All rights reserved.</span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 copyright-after">
                                <div class="social-round">
                                    <ul>
                                        <li><a target="_blank" href="{{Session::get('dbo_system_config_data')['facebook']->value}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="icon-find">
                        <a href="#">
                            <img src="{{asset('newtheme/images/location.png')}}" alt="">
                            <span>Find us on Map</span>
                        </a>
                    </div>
                    <div class="location-footer-map">
                        <div class="icon-find-location">
                            <a href="#">
                                <img src="{{asset('newtheme/images/location.png')}}" alt="">
                                <span>Find us on Map</span>
                            </a>
                        </div>
                        <div class="footer-map-outer">
                            <div id="footer-map"></div>
                        </div>
                    </div> -->
            </div>
        </footer>
        <!-- End Footer -->

    </div>
    <!-- Back To Top Arrow -->
    <a href="#" class="top-arrow"></a>
    <script type="text/javascript" src="{{ asset('js/jquery.toast.js')}}"></script>
    <script src="{{asset('newtheme/plugin/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAf6My1Jfdi1Fmj-DUmX_CcNOZ6FLkQ4Os"></script>
    <script src="{{asset('newtheme/plugin/form-field/jquery.formstyler.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/revolution-plugin/jquery.themepunch.plugins.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/revolution-plugin/jquery.themepunch.revolution.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/owl-carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/slick-slider/slick.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/isotop/isotop.js')}}"></script>
    <script src="{{asset('newtheme/plugin/isotop/packery-mode.pkgd.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/magnific/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/scroll-bar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/animation/wow.min.js')}}"></script>
    <script src="{{asset('newtheme/plugin/parallax/jquery.stellar.js')}}"></script>
    <script src="{{asset('newtheme/js/app.js')}}"></script>
    <script src="{{asset('newtheme/js/script.js')}}"></script>
    @yield('scripts')
    <script>
        $(document).ready(function() {
            $('#videoIntro').attr('height', $(window).width() / 2);
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/5ef2ffea4a7c6258179b3aba/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
</body>

</html>