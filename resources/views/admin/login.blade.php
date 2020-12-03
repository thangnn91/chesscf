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
    <title>Metronic | Login Page - 3</title>
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

    <!--end::Web font -->

    <!--begin::Global Theme Styles -->
    <link href="{{ asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="../../../assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="../../../assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

    <!--end::Global Theme Styles -->
    <link rel="shortcut icon" href="{{ asset('assets/demo/default/media/img/logo/favicon.ico')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url({{{ asset('assets/app/media/img/bg/bg-3.jpg')}}});">
            <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
                <div class="m-login__container">
                    <div class="m-login__logo">
                        <a href="#">
                            <img style="width: 200px;" src="{{asset('newtheme/images/'). '/' . Session::get('dbo_system_config_data')['admin_logo']->value}}">
                        </a>
                    </div>
                    <div class="m-login__signin">
                        <div class="m-login__head">
                            <h3 class="m-login__title">Sign In To Admin</h3>
                        </div>
                        <form id="loginForm" class="m-login__form m-form" method="POST" action="{{ asset('/doPostLogin') }}">
                            @if($errors->any())
                            <div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                </button>
                                <span>{{$errors->first()}}</span>
                            </div>
                            @endif
                            @csrf
                            <div class="form-group m-form__group {{ $errors->has('username') ? ' has-danger' : '' }}">
                                <input class="form-control m-input" type="text" autofocus value="{{ old('username') }}" placeholder="Username" name="username" autocomplete="off">
                                @if ($errors->has('username'))
                                <div id="username-error" class="form-control-feedback">Vui lòng nhập tài khoản</div>
                                @endif
                            </div>
                            <div class="form-group m-form__group {{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="m-input-icon m-input-icon--right">
                                    <input class="form-control m-input m-login__form-input--last" value="" type="password" placeholder="Password" name="password">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span>
                                            <i style="cursor: pointer" class="fa fa-eye eye-pw"></i>
                                        </span>
                                    </span>
                                </div>

                                @if ($errors->has('password'))
                                <div id="username-error" class="form-control-feedback">Vui lòng nhập mật khẩu</div>
                                @endif
                            </div>
                            <div class="form-group col-xl-5 m-form__group {{ $errors->has('otp') ? ' has-danger' : '' }}" style="margin: 0;">
                                <div class="m-input-icon m-input-icon--right">
                                    <input class="form-control m-input m-login__form-input--last" value="" id="otp" maxlength="6" type="text" placeholder="Mã xác thực" name="otp">
                                    <span id="btnSendOTP" title="Send OTP" class="m-input-icon__icon m-input-icon__icon--right" onclick="sendOTP();">
                                        <span>
                                            <i style="cursor: pointer" class="fab fa-telegram-plane"></i>
                                        </span>
                                    </span>
                                </div>

                                @if ($errors->has('otp'))
                                <div id="username-error" class="form-control-feedback">Vui lòng nhập mã xác thực</div>
                                @endif
                            </div>
                            <div class="row m-login__form-sub">
                                <div class="col m--align-left m-login__form-left">
                                    <label class="m-checkbox  m-checkbox--focus">
                                        <input type="checkbox" name="remember"> Remember me
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col m--align-right m-login__form-right">
                                    <a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
                                </div>
                            </div>
                            <div class="m-login__form-action">
                                <button id="m_login_signin_submit" type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="m-login__signup">
                        <div class="m-login__head">
                            <h3 class="m-login__title">Sign Up</h3>
                            <div class="m-login__desc">Enter your details to create your account:</div>
                        </div>
                        <form class="m-login__form m-form" action="">
                            <div class="form-group m-form__group">
                                <input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
                            </div>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
                            </div>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input" type="password" placeholder="Password" name="password">
                            </div>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
                            </div>
                            <div class="row form-group m-form__group m-login__form-sub">
                                <div class="col m--align-left">
                                    <label class="m-checkbox m-checkbox--focus">
                                        <input type="checkbox" name="agree">I Agree the <a href="#" class="m-link m-link--focus">terms and conditions</a>.
                                        <span></span>
                                    </label>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="m-login__form-action">
                                <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">Sign Up</button>&nbsp;&nbsp;
                                <button id="m_login_signup_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <div class="m-login__forget-password">
                        <div class="m-login__head">
                            <h3 class="m-login__title">Forgotten Password ?</h3>
                            <div class="m-login__desc">Enter your email to reset your password:</div>
                        </div>
                        <form class="m-login__form m-form" action="">
                            <div class="form-group m-form__group">
                                <input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" autocomplete="off">
                            </div>
                            <div class="m-login__form-action">
                                <button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primaryr">Request</button>&nbsp;&nbsp;
                                <button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <div class="m-login__account">
                        <span class="m-login__account-msg">
                            Don't have an account yet ?
                        </span>&nbsp;&nbsp;
                        <a href="javascript:;" id="m_login_signup" class="m-link m-link--light m-login__account-link">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Page -->

    <!--begin::Global Theme Bundle -->
    <script src="{{ asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/utils.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        function sendOTP() {
            $('#btnSendOTP').addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            mApp.blockPage({
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: "Please wait..."
            });
            $.ajax({
                type: 'POST',
                url: Utils.UrlRoot + "sendOTP",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    mApp.unblockPage();
                    $('#btnSendOTP').removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    if (data.ResponseCode >= 0) {
                        swal("Thành công!", data.Description, "success");
                    } else if (data.ResponseCode == -600) {
                        $("#m_form_1_msg").removeClass("m--hide").show(), mUtil.scrollTop();
                        return;
                    } else {
                        swal("Thất bại!", data.Description, "error");
                        return;
                    }
                },
                always: function() {
                    mApp.unblockPage();
                }
            });
        }
    </script>
    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts -->
    <!--        <script src="{{ asset('assets/snippets/custom/pages/user/login.js')}}" type="text/javascript"></script>-->

    <!--end::Page Scripts -->
</body>

<!-- end::Body -->

</html>