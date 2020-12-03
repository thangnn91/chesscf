@extends('layouts._layout')

@section('title', $news_detail->title)

@section('content')
<!-- Start Breadcrumb Part -->
<section class="breadcrumb-part" data-stellar-offset-parent="true" data-stellar-background-ratio="0.5" style="background-image: url('{{asset("newtheme/images/breadbg1.jpg")}}');">
    <div class="container">
        <div class="breadcrumb-inner">
            <h2>Tin tức</h2>
            <a href="#">Trang chủ</a>
            <span>Tin tức</span>
        </div>
    </div>
</section>
<!-- End Breadcrumb Part -->
<section class="home-icon blog-main-section blog-single">
    <div class="icon-default">
        <img src="{{asset('newtheme/images/scroll-arrow.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="blog-right-section">
                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        @if(!($news_detail->banner == '' || $news_detail->banner == null))
                        <div class="feature-img">
                            <img src="{{asset('userfiles') .'/'. json_decode($news_detail->banner)[0]->key}}" />
                        </div>
                        @endif
                        <div class="feature-info">
                            <span><i class="icon-user-1"></i>Chess art coffee</span>
                            <br />
                            <br />
                            <div class="detail_content">
                                {!! $news_detail->content !!}
                            </div>
                            <div class="share-tag">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="social-wrap">
                                            <h5>SHARE</h5>
                                            <ul class="social">
                                                <li class="social-facebook"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                                <li class="social-tweeter"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                                <li class="social-instagram"><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                                <li class="social-dribble"><a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
                                                <li class="social-google"><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.detail_content p').has('img').css('text-align', 'center');
    });
</script>
@stop