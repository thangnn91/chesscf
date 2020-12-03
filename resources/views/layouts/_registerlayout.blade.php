<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Đăng nhập/Đăng ký</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
        <link rel="stylesheet" href="{{ asset('css/jquery.toast.css')}}">
        <link rel="stylesheet" href="{{ asset('css/login.css')}}">
    </head>

    <body>

        <div id="loading" class="overlay">
            <div class="overlay__inner">
                <div class="overlay__content">
                    <div class="container">
                        <div class="dot dot-1"></div>
                        <div class="dot dot-2"></div>
                        <div class="dot dot-3"></div>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <defs>
                    <filter id="goo">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"/>
                    </filter>
                    </defs>
                    </svg>
                </div>
            </div>
        </div>



        <div id="container_demo" class="form">
            <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <a class="hiddenanchor" id="toforgetpass"></a>
            <div id="wrapper">
                <div id="login" class="animate form">
                    <form id="authen-form" action="none" autocomplete="off"> 
                        <h1>Đăng nhập</h1> 
                        <p> 
                            <label for="login_user" class="uname" data-icon="u" >Tài khoản</label>
                            <input id="login_user" autocomplete="off" maxlength="100" name="login_user" required="required" type="email" placeholder="example@gmail.com"/>
                        </p>
                        <p> 
                            <label for="login_pass" class="youpasswd" data-icon="p"> Your password </label>
                            <input id="login_pass" autocomplete="off" name="login_pass" required="required" type="password" placeholder="eg. X8df!90EO" /> 
                        </p>
                        <p> 
                            {!! app('captcha')->display() !!}
                        </p>
                        <p class="login button"> 
                            <input id="bt_Login" type="button" onclick="login();" value="Đăng nhập" />                            
                        </p>
                        <p style="text-align: right;">
                            <a href="#toforgetpass" class="to_forgetpass">Quên mật khẩu</a>
                        </p>
                        <p class="change_link">
                            Bạn chưa có tài khoản?
                            <a href="#toregister" class="to_register">Đăng ký</a>
                        </p>                       
                    </form>
                    <form id="verify-form" style="display: none;" action="/" method="post">
                        <p><i style="color: #1ab188; font-size: 13px;">Vui lòng mở ứng dụng Google Authenticator để lấy mã
                                xác thực</i></p>
                        <div class="field-wrap">
                            <label>
                                Mã xác thực<span class="req">*</span>
                            </label>
                            <input id="secure_code" autocomplete="off" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '');" maxlength="6" type="text" required
                                   autocomplete="off" />
                        </div>
                        <p class="signin button"> 
                            <input id="bt_Verify" onclick="loginVerify();" type="button" value="Đăng nhập" /> 
                        </p>
                    </form>
                </div>

                <div id="register" class="animate form">
                    <form id="reg_form" action="none" autocomplete="off"> 
                        <h1>Đăng ký</h1> 
                        <p> 
                            <label for="reg_email" class="uname" data-icon="u">Tài khoản</label>
                            <input id="reg_email" autocomplete="off" name="reg_email" maxlength="100" type="email" placeholder="example@gmail.com" />
                        </p>
                        <p> 
                            <label for="reg_pass" class="youpasswd" data-icon="p">Mật khẩu</label>
                            <input id="reg_pass" autocomplete="off" name="reg_pass" required="required" type="password" placeholder="eg. X8df!90EO"/>
                        </p>
                        <p> 
                            <label for="reg_repass" class="youpasswd" data-icon="p">Nhập lại mật khẩu</label>
                            <input id="reg_repass" autocomplete="off" name="reg_repass" required="required" type="password" placeholder="eg. X8df!90EO"/>
                        </p>
                        <p class="signin button"> 
                            <input id="bt_Register" onclick="registerAccount();" type="button" value="Đăng ký"/> 
                        </p>
                        <p class="change_link">  
                            Bạn đã có tài khoản?
                            <a href="#tologin" class="to_register">Đăng nhập</a>
                        </p>
                    </form>
                    <form id="reg-verify-form" style="display: none;" action="/" method="post">
                        <p><i style="color: #1ab188; font-size: 13px;">Một mã xác thực đã được gửi tới email: </i><b id="reg_email_text"></b></p>
                        <div class="field-wrap">
                            <label>
                                Mã xác thực<span class="req">*</span>
                            </label>
                            <input id="verify_code" autocomplete="off" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '');" maxlength="6" type="text" required
                                   autocomplete="off" />
                        </div>
                        <p class="signin button"> 
                            <input id="bt_Reg_Verify" onclick="registerVerify();" type="button" value="Hoàn tất"/> 
                        </p>
                    </form>
                </div>

                <div id="forgetpass" class="animate form">
                    <form action="none" autocomplete="off"> 
                        <h1> Quên mật khẩu </h1> 
                        <input type="hidden" id="forgetpass_user" />
                        <input type="hidden" id="forgetpass_token" />
                        <div id="step1">
                            <p> 
                                <label for="userforgetpass" class="uname" data-icon="u">Tài khoản</label>
                                <input id="userforgetpass" autocomplete="off" maxlength="100" required="required" name="userforgetpass" type="text" placeholder="example@gmail.com" />
                            </p>
                            <p class="signin button"> 
                                <input id="bt_forgetpass1" onclick="forgetPassStep1();" type="button" value="Tiếp tục"/> 
                            </p>
                        </div>
                        <div id="step2" style="display:none;">
                            <p> 
                                <label class="uname">Một mã xác thực đã được gửi tới email:&nbsp;<b></b></label>
                                <input id="forgetpass_otp" autocomplete="off" maxlength="6" onkeyup="if (/\D/g.test(this.value))
                                            this.value = this.value.replace(/\D/g, '');" required="required" name="forgetpass_otp" type="text" placeholder="012345" />
                            </p>
                            <p class="signin button"> 
                                <input id="bt_forgetpass2" onclick="forgetPassStep2();" type="button" value="Tiếp tục"/> 
                            </p>
                        </div>
                        <div id="step3" style="display:none;">
                            <p> 
                                <label class="uname">Mật khẩu mới</label>
                                <input id="f_pass" autocomplete="off" name="f_pass" required="required" type="password" placeholder="eg. X8df!90EO"/>
                            </p>
                            <p> 
                                <label class="uname">Nhập lại mật khẩu</label>
                                <input id="f_re_pass" autocomplete="off" name="f_re_pass" required="required" type="password" placeholder="eg. X8df!90EO"/>
                            </p>
                            <p class="signin button"> 
                                <input id="bt_forgetpass3" onclick="confirmChangePass();" type="button" value="Tiếp tục"/> 
                            </p>
                        </div>
                        <p class="change_link">
                            Trở về trang đăng nhập?
                            <a href="#tologin" class="to_register">Đăng nhập</a>
                        </p>
                    </form>
                </div>

            </div>
        </div> <!-- /form -->
        <script src="{{ asset('vendors/jquery/dist/jquery.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/utils.js')}}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.toast.js') }}"></script>
        <script src="{{ asset('js/login.js') }}"></script>
        <script type="text/javascript">
                                    $(function () {
                                        $(document).ajaxSend(function (event, request, settings) {
                                            if (!settings.headers['NO-LOADING']) {
                                                $('#loading').show();
                                            }
                                        });
                                        $(document).ajaxComplete(function () {
                                            $('#loading').hide();
                                        });
                                        $(document).ajaxError(function () {
                                            $('#loading').hide();
                                            $.toast({
                                                heading: 'Error',
                                                text: 'Lỗi hệ thống, vui lòng thử lại sau',
                                                showHideTransition: 'fade',
                                                position: 'top-right',
                                                icon: 'error'
                                            });
                                        });
                                    });
                                    $('#register form input').on('keypress', function (e) {
                                        var code = e.keyCode || e.which;
                                        if (code === 13) {
                                            registerAccount();
                                        }
                                    });
                                    $('#login #authen-form input').on('keypress', function (e) {
                                        var code = e.keyCode || e.which;
                                        if (code === 13) {
                                            login();
                                        }
                                    });
                                    $('#login #verify-form input').keypress(function (e) {
                                        var keyCode = e.keyCode || e.which;
                                        if (keyCode === 13) {
                                            loginVerify();
                                            return false;
                                        }
                                    });
                                    $('#forgetpass #userforgetpass').keypress(function (e) {
                                        var keyCode = e.keyCode || e.which;
                                        if (keyCode === 13) {
                                            getSmsCode();
                                            return false;
                                        }
                                    });

                                    $('#forgetpass').on('keypress', '#forgetpass_i1, #forgetpass_i2', function (e) {
                                        var keyCode = e.keyCode || e.which;
                                        if (keyCode === 13) {
                                            confirmChangePass();
                                            return false;
                                        }
                                    });

                                    function registerAccount() {
                                        var userName = $('#reg_email').val();
                                        if (!userName) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập email',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }

                                        userName = userName.toLowerCase();
                                        if (!checkEmailFormat(userName)) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Email không hợp lệ',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }

                                        var password = $('#reg_pass').val();
                                        if (!password) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mật khẩu',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        var repassword = $('#reg_repass').val();
                                        if (!repassword) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập lại mật khẩu',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        if (password !== repassword) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Mật khẩu nhập lại không đúng',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        $('#bt_Register').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.register')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                action: 1,
                                                username: userName,
                                                password: password
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode > 0) {
                                                    $('#register #reg_form input').val('');
                                                    $('#register #reg_form').hide();
                                                    $('#register #reg-verify-form #reg_email_text').text(userName);
                                                    $('#register #reg-verify-form').show();
                                                } else {
                                                    $('#bt_Register').val('Đăng ký').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }

                                    function registerVerify() {
                                        var userName = $('#reg_email_text').text();
                                        var code = $('#verify_code').val();
                                        if (!code) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mã xác thực',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }

                                        $('#bt_Reg_Verify').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.register')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                action: 2,
                                                username: userName,
                                                code: code,
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode > 0) {
                                                    window.location.href = "{{route('index.user')}}";
                                                } else {
                                                    $('#bt_Reg_Verify').val('Hoàn tất').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }

                                    function login() {
                                        var userName = $('#login_user').val();
                                        if (!userName) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập tài khoản',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        userName = userName.toLowerCase();
                                        if (!checkEmailFormat(userName)) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập đúng định dạng email cho tài khoản',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }

                                        var password = $('#login_pass').val();
                                        if (!password) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mật khẩu',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        var gCaptcha = $('#g-recaptcha-response').val();
                                        if (!gCaptcha) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng kiểm tra lại captcha',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        $('#bt_Login').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.postlogin')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                username: userName,
                                                password: password,
                                                captcha: gCaptcha
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode === 1) {
                                                    window.location.href = "{{route('index.user')}}";
                                                }
                                                //Yeu cau otp
                                                else if (data.ResponseCode === 2) {
                                                    $('#login #authen-form .finally').val('');
                                                    $('#login #authen-form').hide();
                                                    $('#login #verify-form').show();
                                                } else {
                                                    $('#bt_Login').val('Đăng nhập').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                    grecaptcha.reset();
                                                }
                                            }
                                        });
                                    }

                                    function loginVerify() {
                                        var code = $('#secure_code').val();
                                        if (!code) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mã xác thực',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        $('#bt_Verify').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.verifylogin')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                otp: code
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode === 1) {
                                                    window.location.href = "{{route('index.user')}}";
                                                } else {
                                                    $('#bt_Verify').val('Đăng nhập').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }

                                    function forgetPassStep1() {
                                        var userName = $('#userforgetpass').val();
                                        if (!userName) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập tài khoản',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        $('#bt_forgetpass1').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.forgetpass1')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                username: userName
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode === 1) {
                                                    $('#forgetpass_user').val(userName);
                                                    $('#forgetpass #step1').hide();
                                                    $('#forgetpass #step2').show();
                                                } else {
                                                    $('#bt_forgetpass1').val('Tiếp tục').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    function forgetPassStep2() {
                                        var otp = $('#forgetpass_otp').val();
                                        if (!otp) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mã xác thực',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        $('#bt_forgetpass2').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.forgetpass2')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                'NO-LOADING': 1
                                            },
                                            data: {
                                                user_name: $('#forgetpass_user').val(),
                                                code: otp
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode === 1) {
                                                    $('#forgetpass_token').val(data.Token);
                                                    $('#forgetpass #step2').hide();
                                                    $('#forgetpass #step3').show();
                                                } else {
                                                    $('#bt_forgetpass2').val('Tiếp tục').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }

                                    function confirmChangePass() {
                                        var token = $('#forgetpass_token').val();
                                        if (!token) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Dữ liệu không hợp lệ, vui lòng thử lại',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        var password = $('#f_pass').val();
                                        if (!password) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập mật khẩu',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        var repassword = $('#f_re_pass').val();
                                        if (!repassword) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Vui lòng nhập lại mật khẩu',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        if (password !== repassword) {
                                            $.toast({
                                                heading: 'Warning',
                                                text: 'Mật khẩu nhập lại không đúng',
                                                showHideTransition: 'slide',
                                                position: 'top-right',
                                                icon: 'warning'
                                            });
                                            return;
                                        }
                                        //post ve server
                                        $('#bt_forgetpass3').val('Đang xử lý...').prop('disabled', true);
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('home.confirmResetPassword')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                token: token,
                                                user_name: $('#forgetpass_user').val(),
                                                password: password
                                            },
                                            success: function (data) {
                                                if (data.ResponseCode === 1) {
                                                    $('#forgetpass #step1').show();
                                                    $('#forgetpass #step2').hide();
                                                    $('#forgetpass #step3').hide();
                                                    $('#forgetpass_token').val('');
                                                    $('#forgetpass_user').val('');
                                                    $('#forgetpass input:not([button])').val('');
                                                    $('#bt_forgetpass3').val('Tiếp tục').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Success',
                                                        text: 'Cập nhật mật khẩu thành công',
                                                        showHideTransition: 'slide',
                                                        position: 'top-right',
                                                        icon: 'success'
                                                    });
                                                    setTimeout(function(){ window.location.hash = '#tologin'; }, 3000);
                                                    
                                                } else {
                                                    $('#bt_forgetpass3').val('Cập nhật').prop('disabled', false);
                                                    $.toast({
                                                        heading: 'Error',
                                                        text: data.Description,
                                                        showHideTransition: 'fade',
                                                        position: 'top-right',
                                                        icon: 'error'
                                                    });
                                                }
                                            }
                                        });
                                    }
        </script>
    </body>

</html>