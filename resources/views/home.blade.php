@extends('layouts._layout')

@section('title', 'Trang chủ')

@section('content')
<!-- Start Breadcrumb Part -->
<section class="home-slider">
    <div class="tp-banner-container">
        <div class="tp-banner">
            <ul>
                <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                    <img src="{{asset('newtheme/images/nen1.jpg')}}" alt="slidebg1" data-lazyload="{{asset('newtheme/images/nen1.jpg')}}" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                </li>
                <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                    <img src="{{asset('newtheme/images/nen2.jpg')}}" alt="slidebg1" data-lazyload="{{asset('newtheme/images/nen2.jpg')}}" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                </li>
                <li data-transition="zoomout" data-slotamount="2" data-masterspeed="1000" data-thumb="" data-saveperformance="on" data-title="Slide">
                    <img src="{{asset('newtheme/images/nen3.jpg')}}" alt="slidebg1" data-lazyload="{{asset('newtheme/images/nen3.jpg')}}" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                </li>
            </ul>
        </div>
    </div>
</section>
<!-- End Breadcrumb Part -->
<!-- Start Menu Part -->
@if(count($productsNew))
<section class="special-menu bg-skeen home-icon">
    <div class="container">
        <div class="build-title">
            <h2>Thực đơn mới</h2>
            <h6>Món mới đã lên kệ, sẵn sàng phục vụ quý khách.</h6>
        </div>
        <div class="menu-wrapper">
            <div class="portfolioContainer row">
                @foreach($productsNew as $itm)
                <div class="col-md-4 col-sm-4 col-xs-12 isotope-item breakfast dinner wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <a href="{{route('product').'/'.$itm->alias.'-'.$itm->id}}" class="menu-round">
                        <div class="menu-round-img">
                            @if($itm->images !== null && $itm->images !== '')
                            <img src="{{asset('userfiles/'.json_decode($itm->images)[0]->key)}}" alt="Ảnh sản phẩm">
                            @else
                            <img src="abc.png" alt="Ảnh sản phẩm">
                            @endif
                        </div>
                        <div class="menu-round-info">
                            <h6 data-toggle="tooltip" title="{{$itm->name}}" class="custom-tooltip">{{$itm->name}}</h6>
                            @if(Config::get('constants.hideProductPrice'))
                            <h6><span>Giá: Liên hệ</span></h6>
                            @else
                            @if($itm->discount_price !== 0 && $itm->discount_price !== '' && $itm->discount_price !== null)
                            <h6><span><small><del>{{number_format($itm->price,0,",",".")}} đ</del></small>{{number_format($itm->discount_price,0,",",".")}} đ</span></h6>
                            @else
                            <h6><span>{{number_format($itm->price,0,",",".")}} đ</span></h6>
                            @endif
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="float-main">
        <div class="icon-top-left">
            <img src="{{asset('newtheme/images/icon7.png')}}" alt="">
        </div>
        <div class="icon-bottom-left">
            <img src="{{asset('newtheme/images/icon8.png')}}" alt="">
        </div>
        <div class="icon-top-right">
            <img src="{{asset('newtheme/images/icon9.png')}}" alt="">
        </div>
        <div class="icon-bottom-right">
            <img src="{{asset('newtheme/images/icon10.png')}}" alt="">
        </div>
    </div>
</section>
@endif
<!-- End Menu Part -->
<!-- Start Dishes Part -->
@if(count($productsDiscount))
<section class="dishes banner-bg invert invert-black home-icon wow fadeInDown" data-background="./newtheme/images/banner1.jpg" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="icon-default icon-black">
        <img src="{{asset('newtheme/images/icon5.png')}}" alt="">
    </div>
    <div class="container">
        <div class="build-title">
            <h2>Giảm giá đặc biệt</h2>
            <h6>Những sản phẩm đang được giảm giá đặc biệt tại cửa hàng của chúng tôi.</h6>
        </div>
        <div class="slider multiple-items">
            @foreach($productsDiscount as $itm)
            <a href="{{route('product').'/'.$itm->alias.'-'.$itm->id}}" class="product-blog">
                @if($itm->images !== null && $itm->images !== '')
                <img src="{{asset('userfiles/'.json_decode($itm->images)[0]->key)}}" alt="Ảnh sản phẩm">
                @else
                <img src="abc.png" alt="Ảnh sản phẩm">
                @endif
                <h3 data-toggle="tooltip" title="{{$itm->name}}" class="custom-tooltip">{{$itm->name}}</h3>
                @if(Config::get('constants.hideProductPrice'))
                <strong class="txt-default">Giá: Liên hệ</strong>
                @else
                <del class="text-white">{{number_format($itm->price,0,",",".")}} đ</del><strong class="txt-default">{{number_format($itm->discount_price,0,",",".")}} đ</strong>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
<!-- End Dishes Part -->
<section class="special-menu bg-skeen home-icon">
    <div class="container">
        @if(count($productsHot))
        <div class="build-title">
            <h2>Bán chạy nhất</h2>
            <h6>Những sản phẩm đang được bán chạy nhất tại cửa hàng chúng tôi.</h6>
        </div>
        <div class="menu-wrapper">
            <div class="portfolioContainer row">
                @foreach($productsHot as $itm)
                <div class="col-md-4 col-sm-4 col-xs-12 isotope-item breakfast dinner wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <a href="{{route('product').'/'.$itm->alias.'-'.$itm->id}}" class="menu-round">
                        <div class="menu-round-img">
                            @if($itm->images !== null && $itm->images !== '')
                            <img src="{{asset('userfiles/'.json_decode($itm->images)[0]->key)}}" alt="Ảnh sản phẩm">
                            @else
                            <img src="abc.png" alt="Ảnh sản phẩm">
                            @endif
                        </div>
                        <div class="menu-round-info">
                            <h6 data-toggle="tooltip" title="{{$itm->name}}" class="custom-tooltip">{{$itm->name}} </h6>
                            @if(Config::get('constants.hideProductPrice'))
                            <h6><span>Giá: Liên hệ</span></h6>
                            @else
                            @if($itm->discount_price !== 0 && $itm->discount_price !== '' && $itm->discount_price !== null)
                            <h6><span><small><del>{{number_format($itm->price,0,",",".")}} đ</del></small>{{number_format($itm->discount_price,0,",",".")}} đ</span></h6>
                            @else
                            <h6><span>{{number_format($itm->price,0,",",".")}} đ</span></h6>
                            @endif
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @foreach($cateShowHome as $itm)
        @if(count($itm->products))
        <div class="build-title">
            <h2>{{$itm->text}}</h2>
            <h6>{{$itm->title}}</h6>
        </div>
        <div class="menu-wrapper">
            <div class="portfolioContainer row">
                @foreach($itm->products as $product)
                <div class="col-md-4 col-sm-4 col-xs-12 isotope-item breakfast dinner wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <a href="{{route('product').'/'.$product->alias.'-'.$product->id}}" class="menu-round">
                        <div class="menu-round-img">
                            @if($product->images !== null && $product->images !== '')
                            <img src="{{asset('userfiles/'.json_decode($product->images)[0]->key)}}" alt="Ảnh sản phẩm">
                            @else
                            <img src="abc.png" alt="Ảnh sản phẩm">
                            @endif
                        </div>
                        <div class="menu-round-info">
                            <h6 data-toggle="tooltip" title="{{$product->name}}" class="custom-tooltip">{{$product->name}} </h6>
                            @if(Config::get('constants.hideProductPrice'))
                            <h6><span>Giá: Liên hệ</span></h6>
                            @else
                            @if($product->discount_price !== 0 && $product->discount_price !== '' && $product->discount_price !== null)
                            <h6><span><small><del>{{number_format($product->price,0,",",".")}} đ</del></small>{{number_format($product->discount_price,0,",",".")}} đ</span></h6>
                            @else
                            <h6><span>{{number_format($product->price,0,",",".")}} đ</span></h6>
                            @endif
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <h5 class="text-right"><a href="{{asset($itm->href)}}">Xem thêm...</a></h5>
        @endif
        @endforeach
    </div>
    <div class="float-main">
        <div class="icon-top-left">
            <img src="{{asset('newtheme/images/icon7.png')}}" alt="">
        </div>
        <div class="icon-bottom-left">
            <img src="{{asset('newtheme/images/icon8.png')}}" alt="">
        </div>
        <div class="icon-top-right">
            <img src="{{asset('newtheme/images/icon9.png')}}" alt="">
        </div>
        <div class="icon-bottom-right">
            <img src="{{asset('newtheme/images/icon10.png')}}" alt="">
        </div>
    </div>
</section>
@stop