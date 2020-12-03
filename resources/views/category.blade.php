@extends('layouts._layout')

@section('title', $cate->text)

@section('content')
<!-- Start Menu Part -->
<section class="special-menu bg-skeen home-icon">
    <div class="container">
        <div class="build-title">
            <h2>{{$cate->text}}</h2>
            <h6>{{$cate->title}}</h6>
        </div>
        <div class="menu-wrapper">
            @if(!count($cate->products))
            <h4 class="text-center">Không có sản phẩm nào trong danh mục</h4>
            @else
            <div class="portfolioContainer row">
                @foreach($cate->products as $itm)
                <div class="col-md-4 col-sm-4 col-xs-12 isotope-item breakfast dinner wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                    <a href="{{route('product').'/'.$itm->alias.'-'.$itm->id}}" class=" menu-round">
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
            @endif
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
<!-- End Menu Part -->
@stop