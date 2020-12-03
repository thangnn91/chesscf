@extends('layouts._layout')

@section('title', 'Liên hệ')

@section('content')
<!-- Start Contact Part -->
<section class="default-section contact-part home-icon">
    <div class="container">
        <div class="title text-center">
            <h2 class="text-coffee">Liên hệ chúng tôi</h2>
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
                <h5 class="text-coffee">Để lại lời nhắn cho chúng tôi</h5>
                <form class="form" method="post" name="contact-form">
                    <div class="row">
                        <div class="alert-container"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Họ tên *</label>
                            <input name="first_name" type="text" required>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>Email *</label>
                            <input name="email" type="email" required>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>Tiêu đề *</label>
                            <input name="subject" type="text" required>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>Nội dung tin nhắn *</label>
                            <textarea name="message" required></textarea>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn-black pull-right send_message" onclick="alert('Chức năng đang được xây dựng!')">GỬI TIN NHẮN</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <h5 class="text-coffee">Thời gian mở cửa</h5>
                <ul class="time-list">
                    <li><span class="week-name">Thứ 2</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Thứ 4</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Thứ 5</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Thứ 6</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Thứ 3</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Thứ 7</span> <span>08h-23h</span></li>
                    <li><span class="week-name">Chủ nhật</span> <span>08h-23h</span></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Part -->
@stop