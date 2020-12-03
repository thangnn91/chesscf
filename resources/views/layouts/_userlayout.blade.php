<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="./favicon.ico">

        <title>Xổ số Online</title>

        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendors/toastr/build/toastr.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" />
        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendors/vendors/line-awesome/css/line-awesome.min.css') }}" rel="stylesheet" />

        <link href="{{ asset('css/site.css') }}" rel="stylesheet" />

        <!-- Just for debugging purposes. Don't actually copy this line! -->
        <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <![endif]-->
        <!-- JQuery -->
        <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('vendors/jquery/dist/jquery.js') }}" type="text/javascript"></script>

        @yield('style')
    </head>

    <body style="background-color:{{url()->current() == route('evenodd.user') ? '#010101' : ''}}">
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
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark" style="background-color: #13232f">
            <div class="container">
                <a class="navbar-brand pt-0" href="{{route('index.user')}}">
                    <img src="{{ asset('img/logo-dark-bg.png')}}" width="85" alt="Brand">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
                        aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav">
                        <li class="nav-item {{url()->current() == route('cashin.user') ? 'active' :''}}">
                            <a class="nav-link" href="{{ route('cashin.user') }}">Nạp tiền</a>
                        </li>
                        <li class="nav-item {{url()->current() == route('cashout.user') ? 'active' :''}}">
                            <a href="{{ route('cashout.user') }}"
                               class="nav-link" href="#">Rút tiền</a>
                        </li>
                        <li class="nav-item position-relative {{url()->current() == route('lottery.user') || 
                        url()->current() == route('xsmb.user') ||
                        url()->current() == route('loto.user') ? 'active' :''}}">
                            <a class="nav-link dropdown-toggle" href="#" id="buyLottDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Mua xổ số
                            </a>
                            <div class="dropdown-menu" aria-labelledby="buyLottDropdown">
                                <a href="{{ route('lottery.user') }}?code=dmw2NDU=" class="dropdown-item">Mega 6/45</a>
                                <a href="{{ route('xsmb.user') }}?code=eHNtYg==" class="dropdown-item">Xổ số miền Bắc</a>
                                <a href="{{ route('loto.user') }}" class="dropdown-item">Lô tô</a>
                                <!--<a href="{{ route('evenodd.user') }}" class="dropdown-item">Chẵn lẻ</a>-->
                                <!--<a href="{{ route('xsmn.user') }}" class="dropdown-item">Xổ số miền Nam</a>-->
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown mr-3">
                            <a class="nav-link" style="color: deepskyblue;" onclick='window.prompt("Copy to clipboard: Ctrl+C, Enter", "{{route('home.identify')}}?fid={{$user->ref_id}}#toregister");' href="javascript:;" data-href="{{route('home.identify')}}?fid={{$user->ref_id}}#toregister" id="balanceDropdown" role="button">
                                <i class="far fa-address-card mr-2"></i>
                                Link ref
                            </a>                           
                        </li>
                        <li class="nav-item dropdown mr-3">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="balanceDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Số dư: <span
                                    id="realBalance">{{$userBalance->realbalance%1000==0?number_format($userBalance->realbalance/1000, 0, '.', ','):number_format((double)$userBalance->realbalance/1000, 1, '.', ',')}}</span>
                                LTR
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="balanceDropdown">
                                <span class="dropdown-item">Số dư chính: <span
                                        id="realBalanceDropdown">{{$userBalance->realbalance%1000==0?number_format($userBalance->realbalance/1000, 0, '.', ','):number_format((double)$userBalance->realbalance/1000, 1, '.', ',')}}</span>
                                    LTR</span>
                                <span class="dropdown-item">Số dư thanh toán: <span
                                        id="payBalance">{{$userBalance->paybalance%1000==0?number_format($userBalance->paybalance/1000, 0, '.', ','):number_format((double)$userBalance->paybalance/1000, 1, '.', ',')}}</span>
                                    LTR</span>
                            </div>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link dropdown-toggle p-0" href="#" id="navUserInfo" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(file_exists(public_path(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)))
                                <img width="40px"
                                     src="{{asset(\Config::get('constants.avatar_path').'/'.$user->username.'/'.$user->avatar)}}"
                                     class="m--img-rounded m--marginless" alt="" />
                                @else
                                <img width="40px" src="{{asset(\Config::get('constants.avatar_default'))}}"
                                     class="m--img-rounded m--marginless" alt="" />
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserInfo">
                                <a class="dropdown-item" href="{{ route('update.profile.user')}}">Thông tin tài khoản</a>
                                @if($user->status == 0 || $user->status == 2)
                                <a class="dropdown-item" href="{{ route('verify.user')}}">Chứng thực</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('secure.user') }}">Bảo mật</a>
                                <a class="dropdown-item" href="{{ route('transactionHistory.user') }}">Lịch sử giao dịch</a>
                                <a class="dropdown-item" href="{{ route('tradeHistory.user') }}">Lịch sử mua hàng</a>
                                <a class="dropdown-item" href="{{ route('bonusHistory.user') }}">Lịch sử thưởng giới thiệu</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:;"
                                   onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">Đăng
                                    xuất</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <!-- <div class="nav-item dropdown ml-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown link
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div> -->
                </div>
            </div>
        </nav>
        @php
        $redis = \RedisL4::connection();
        $arrrayWon = array();
        $wonCache = $redis->get('won_people_cache');
        if ($wonCache) {
        $arrrayWon = json_decode($wonCache);
        } else {
        $arrayRandomLtr = [10, 10, 10, 20, 20, 20, 30, 40, 50, 60, 100, 300, 1000, 10000, 70, 70, 70, 70, 70, 70, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 140, 140, 140, 140, 140, 140, 160, 210, 240, 280, 320, 400, 500, 600];
        $listUserWon = \DB::table('dbo_lottery')->leftJoin('dbo_user', 'dbo_user.id', '=', 'dbo_lottery.userid')->where([['out_money', '>', 0], ['dbo_lottery.updated_at', '<', date('Y-m-d') . ' 00:00:00'], ['dbo_lottery.updated_at', '>', date('Y-m-d', strtotime('-500 days')) . ' 00:00:00']])->get();
        if (!$listUserWon || count($listUserWon) < 20) {
        $listFakePeople = $redis->get('system_fake_people');
        $arrrayFakePeople = array();
        if (!$listFakePeople) {
        $file = fopen(storage_path("vn_people.txt"), "r");
        while (!feof($file)) {
        $fullName = fgets($file);
        $fullName = preg_replace('~[\r\n]+~', '', $fullName);
        $arrrayFakePeople[] = $fullName;
        }
        $redis->set('system_fake_people', json_encode($arrrayFakePeople));
        } else {
        $arrrayFakePeople = json_decode($listFakePeople, true);
        }


        $randomLength = rand(5, 30);
        for ($i = 0; $i < $randomLength; $i++) {
        $index = array_rand($arrrayFakePeople);
        $person = $arrrayFakePeople[$index];
        $currentObject = new \stdClass();
        $currentObject->name = $person;
        $currentObject->price = $arrayRandomLtr[array_rand($arrayRandomLtr)];
        $arrrayWon[] = $currentObject;
        unset($arrrayFakePeople[$index]);
        }
        if ($listUserWon) {
        foreach ($listUserWon as $userWon) {
        $currentObject = new \stdClass();
        $currentObject->name = substr($userWon->username, 0, -3) . '***';
        $currentObject->price = $userWon->out_money / 1000;
        $arrrayWon[] = $currentObject;
        }
        $redis->set('won_people_cache', json_encode($arrrayWon), 'EX', 86400);
        }
        } else {

        }
        }
        @endphp

        @if(url()->current() == route('evenodd.user'))
    <marquee behavior="scroll" direction="left" scrollamount="5" class="text-white">
        @foreach($arrrayWon as $wonData)

        <span class="mr-5">Chúc mừng <strong>{{$wonData->name}}</strong> trúng {{$wonData->price}}<sup>LTR</sup></span>

        @endforeach
    </marquee>
    <div class="container">

        @yield('content')

    </div><!-- /.container -->

    <footer class="text-white" style="border-color: #505050">
        Copyright © 2019. All Rights Reserved
    </footer>
    @else
    <marquee behavior="scroll" direction="left" scrollamount="5">
        @foreach($arrrayWon as $wonData)

        <span class="mr-5">Chúc mừng <strong>{{$wonData->name}}</strong> trúng {{$wonData->price}}<sup>LTR</sup></span>

        @endforeach
    </marquee>
    <div class="container py-2">

        @yield('content')

    </div><!-- /.container -->

    <footer>
        Copyright © 2019. All Rights Reserved
    </footer>
    @endif
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

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendors/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('vendors/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('js/utils.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
                                       var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                                       (function () {
                                           var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                                           s1.async = true;
                                           s1.src = 'https://embed.tawk.to/5e44b852a89cda5a1885ad1b/default';
                                           s1.charset = 'UTF-8';
                                           s1.setAttribute('crossorigin', '*');
                                           s0.parentNode.insertBefore(s1, s0);
                                       })();
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ajaxSend(function () {
                $("input").attr('disabled', true);
                $("button").attr('disabled', true);
                $("a").addClass('disabled');
            });
            $(document).ajaxComplete(function () {
                $("input").attr('disabled', false);
                $("button").attr('disabled', false);
                $("a").removeClass('disabled');
            });
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
</body>

</html>