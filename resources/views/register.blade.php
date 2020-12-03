@extends('layouts._layout')

@section('title', 'Đăng ký học')

@section('content')
<!-- Start Contact Part -->
<section class="default-section contact-part home-icon">
    <div class="container">
        <div class="title text-center">
            <h2 class="text-coffee">Đăng ký học</h2>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="contact-blog-row">
                    <div class="contact-icon">
                        <img src="{{asset('newtheme/images/location-icon.png')}}" alt="">
                    </div>
                    <p>Số 152 Vạn Hạnh, Long Biên, Hà Nội</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="contact-blog-row">
                    <div class="contact-icon">
                        <img src="{{asset('newtheme/images/cell-icon.png')}}" alt="">
                    </div>
                    <p><a href="tel:0775446855">096 321 1591</a></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="contact-blog-row">
                    <div class="contact-icon">
                        <img src="{{asset('newtheme/images/mail-icon.png')}}" alt="">
                    </div>
                    <p><a href="mailto:manager@seastarlogs.com">chesscoffeeart@gmail.com</a></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <h5 class="text-coffee">Đăng ký học</h5>
                <form id="main_form" class="form">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="alert-container"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Họ tên phụ huynh *</label>
                            <input id="parent_name" name="parent_name" type="text">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Số điện thoại *</label>
                            <input onkeyup="if (/[^\d]|\.(?=.*\.)/g.test(this.value))
                                this.value = this.value.replace(/[^\d]|\.(?=.*\.)/g, '')" id="parent_phone" name="parent_phone" type="text">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Họ và tên bé *</label>
                            <input id="child_name" name="child_name" type="text">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Tuổi</label>
                            <input onkeyup="if (/[^\d]|\.(?=.*\.)/g.test(this.value))
                                this.value = this.value.replace(/[^\d]|\.(?=.*\.)/g, '')" name="child_age" type="text">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>Chọn lớp học</label>
                            <select class="form-control custom-select" id="class_code" name="class_code">
                                <option value="">Vui lòng chọn lớp học</option>
                                @foreach($available_class as $itm)
                                <option data-total="{{$itm->total}}" data-percent="{{$itm->percent}}" value="{{$itm->code}}">{{$itm->name}}&nbsp;-&nbsp;{{$itm->time_range}}</option>
                                @endforeach
                            </select>
                            <br>
                            <div class="progress active hidden">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>Ghi chú</label>
                            <textarea name="message"></textarea>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <a style="cursor:pointer;" type="button" onclick="submit();" class="btn-black pull-right send_message">Đăng ký</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <h5 class="text-coffee">Lịch học hiện tại</h5>
                <ul class="time-list">
                    <li><span class="week-name">Thứ 3</span><span>18h-19h30</span></li>
                    <li><span class="week-name">Thứ 4</span><span>18h-19h30</span></li>
                    <li><span class="week-name">Thứ 5</span><span>18h-19h30</span></li>
                    <li><span class="week-name">Thứ 6</span><span>18h-19h30</span></li>
                    <li><span class="week-name">Thứ 7</span><span>8h-9h30</span>&nbsp;&&nbsp;<span>16h-17h30</span></li>
                    <li><span class="week-name">Chủ nhật</span><span>8h-9h30</span>&nbsp;&&nbsp;<span>16h-17h30</span></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<style>
    .custom-select {
        height: 50px;
        width: 100%;
        border: 2px solid #e5e5ee;
    }

    .progress.active .progress-bar {
        -webkit-transition: none !important;
        transition: none !important;
    }

    .progress-bar-red {
        background-color: red !important;
    }

    .progress-bar-orange {
        background-color: orange !important;
    }
</style>
<script>
    function submit() {
        let parentName = $('#parent_name').val();
        if (!parentName) {
            $.toast({
                heading: 'Error',
                text: 'Vui lòng nhập tên phụ huynh',
                showHideTransition: 'slide',
                icon: 'error'
            });
            $('#parent_name').focus();
            return;
        }
        let parentPhone = $('#parent_phone').val();
        if (!parentPhone) {
            $.toast({
                heading: 'Error',
                text: 'Vui lòng nhập số điện thoại phụ huynh',
                showHideTransition: 'slide',
                icon: 'error'
            });
            $('#parent_phone').focus();
            return;
        }
        let childName = $('#child_name').val();
        if (!childName) {
            $.toast({
                heading: 'Error',
                text: 'Vui lòng nhập tên học sinh',
                showHideTransition: 'slide',
                icon: 'error'
            });
            $('#child_name').focus();
            return;
        }

        $.ajax({
            type: "POST",
            url: '{{ route("register") }}',
            data: $('#main_form').serialize(), // serializes the form's elements.
            success: function(data) {
                if (data.statusCode > 0) {
                    $('#main_form input').val('');
                    window.location.href = '{{ route("success") }}' + '?code=' + data.registerCode;
                } else
                    $.toast({
                        heading: 'Error',
                        text: data.message,
                        showHideTransition: 'slide',
                        icon: 'error'
                    });
                //alert(data); // show response from the php script.
            }
        });

    }
    $('#class_code').on('change', function() {
        if ($(this).val()) {
            var percent = $(this).find(':selected').data("percent");
            var total = $(this).find(':selected').data("total");
            $('.progress.active').removeClass('hidden');
            var classCss = '';
            if (percent < 50) {
                classCss = ''
            } else if (percent < 90) {
                classCss = 'progress-bar-orange';
            } else {
                classCss = 'progress-bar-red';
            }
            if (!classCss) {
                $(".progress-bar").removeClass($(".progress-bar").data('css'));
            }
            $(".progress-bar").addClass(classCss).data('css', classCss).attr("aria-valuenow", percent);
            $(".progress-bar").animate({
                width: percent + '%'
            }, 1500);
            $(".progress-bar").text('Số lượng học sinh: ' + total + '/12');
            return;
        }
        $(".progress-bar").removeClass($(".progress-bar").data('css'))
        $('.progress.active').addClass('hidden');
        $(".progress-bar").attr("aria-valuenow", 0);
        $(".progress-bar").animate({
            width: '0%'
        }, 100);
        return;
    });
</script>
<!-- End Contact Part -->
@stop