@extends('layouts._layout')

@section('title', 'Đăng ký thành công')

@section('content')
<!-- End Breadcrumb Part -->
<section class="home-icon shop-cart bg-skeen wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="icon-default icon-skeen">
        <img src="{{asset('newtheme/images/scroll-arrow.png')}}" alt="">
    </div>
    <div class="container">
        <div class="checkout-wrap">
            <ul class="checkout-bar">
                <li class="done-proceed">Liên hệ với chúng tôi</li>
                <li class="done-proceed">Đặt chỗ</li>
                <li class="active done-proceed">Hoàn thành</li>
            </ul>
        </div>
        <div class="order-complete-box">
            <img src="{{asset('newtheme/images/complete-sign.png')}}" alt="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    Học sinh: {{$student->child_name}}
                </div>
                @if(isset($student->class))
                <div class="col-md-12 col-sm-12 col-xs-12">
                    Lớp: {{$student->class}}
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{$student->class_desc}}
                </div>
                @else
                <div class="col-md-12 col-sm-12 col-xs-12">
                    Vui lòng liên hệ với chúng tôi để được tư vấn lớp cho bé
                </div>
                @endif
            </div>
            <div class="row">
                <p>
                    <a href='{{ route("home") }}' class="btn-medium btn-primary-gold btn-large">Về trang chủ</a>
                </p>

            </div>

        </div>
    </div>
</section>

@stop