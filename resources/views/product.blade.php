@extends('layouts._layout')

@section('title', $product->name)

@section('content')
<section class="home-icon shop-single pad-bottom-remove">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <div class="slider slider-for slick-shop">
                    @php
                    $arr_img = json_decode($product->images);
                    @endphp
                    @foreach($arr_img as $itm)
                    <div>
                        <img src="{{asset('userfiles/'.$itm->key)}}" alt="Ảnh sản phẩm">
                    </div>
                    @endforeach
                </div>
                <div class="slider slider-nav slick-shop-thumb">
                    @foreach($arr_img as $itm)
                    <div>
                        <img src="{{asset('userfiles/'.$itm->key)}}" alt="Ảnh sản phẩm">
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                <h4 id="product_name" class="text-coffee">{{$product->name}}</h4>
                <p>{{$product->sumary}}</p>
                @if(Config::get('constants.hideProductPrice'))
                <h3 class="text-coffee">Giá: Liên hệ</h3>
                @else
                @if(!$product->is_deleted)
                @if($product->discount_price !== 0 && $product->discount_price !== '' && $product->discount_price !== null)
                <del>{{number_format($product->price,0,",",".")}} đ</del>
                <h3 id="product_price" class="text-coffee">{{number_format($product->discount_price,0,",",".")}} đ</h3>
                @else
                <h3 id="product_price" class="text-coffee">{{number_format($product->price,0,",",".")}} đ</h3>
                @endif
                @else
                <h3 class="text-danger">Sản phẩm ngừng kinh doanh</h3>
                @endif
                @endif
                <div class="price-textbox">
                    <span class="minus-text" onclick="updownQty(false);"><i class="icon-minus"></i></span>
                    <input type="text" id="txtQty" value="1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                    <span class="plus-text" onclick="updownQty(true);"><i class="icon-plus"></i></span>
                </div>
                <a href="javascript:;" data-img="{{asset('userfiles/'.$arr_img[0]->key)}}" class="add-to-cart filter-btn btn-large">Thêm vào giỏ</a>
            </div>
        </div>
    </div>
</section>
<!-- Start Tab Part -->
<section class="default-section comment-review-tab bg-grey v-pad-remove wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="container">
        <div class="tab-part">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#description" aria-controls="description" role="tab" data-toggle="tab">Mô tả chi tiết</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="description">
                    {!!$product->description!!}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Tab Part -->
<!-- Start Related Product -->
@if(count($relatedProduct))
<section class="home-icon related-product wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="container">
        <div class="build-title">
            <h3>Có thể bạn cũng thích</h3>
        </div>
        @if(count($relatedProduct) === 1)
        <div class="owl-carousel owl-theme col-lg-3 col-md-6 col-sm-12" data-items="1" data-laptop="1" data-tablet="1" data-mobile="1" data-nav="false" data-dots="true" data-autoplay="true" data-speed="1800" data-autotime="5000" style="float:none;margin:0 auto">
            @elseif(count($relatedProduct) === 2)
            <div class="owl-carousel owl-theme col-lg-6 col-md-9 col-sm-12" data-items="2" data-laptop="2" data-tablet="2" data-mobile="1" data-nav="false" data-dots="true" data-autoplay="true" data-speed="1800" data-autotime="5000" style="float:none;margin:0 auto">
                @elseif(count($relatedProduct) === 3)
                <div class="owl-carousel owl-theme col-lg-9 col-md-12" data-items="3" data-laptop="3" data-tablet="2" data-mobile="1" data-nav="false" data-dots="true" data-autoplay="true" data-speed="1800" data-autotime="5000" style="float:none;margin:0 auto">
                    @else
                    <div class="owl-carousel owl-theme" data-items="4" data-laptop="3" data-tablet="2" data-mobile="1" data-nav="false" data-dots="true" data-autoplay="true" data-speed="1800" data-autotime="5000">
                        @endif
                        @foreach($relatedProduct as $product)
                        <a href="{{route('product').'/'.$product->alias.'-'.$product->id}}" class="item">
                            <div class="related-img">
                                @if($product->images !== null && $product->images !== '')
                                <img src="{{asset('userfiles/'.json_decode($product->images)[0]->key)}}" alt="Ảnh sản phẩm">
                                @else
                                <img src="abc.png" alt="Ảnh sản phẩm">
                                @endif
                            </div>
                            <div class="related-info">
                                <h6>{{$product->name}}</h6>
                                @if(Config::get('constants.hideProductPrice'))
                                <h6><span>Giá: Liên hệ</span></h6>
                                @else
                                @if($product->discount_price !== 0 && $product->discount_price !== '' && $product->discount_price !== null)
                                <h6><span><small style="margin-right:1rem"><del>{{number_format($product->price,0,",",".")}} đ</del></small>{{number_format($product->discount_price,0,",",".")}} đ</span></h6>
                                @else
                                <h6><span>{{number_format($product->price,0,",",".")}} đ</span></h6>
                                @endif
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
</section>
@endif
<!-- End Related Product -->
<script>
    function updownQty(isUp) {
        if (isUp) {
            $('#txtQty').val(Number($('#txtQty').val()) + 1);
        } else {
            $('#txtQty').val(Number($('#txtQty').val()) <= 1 ? 1 : Number($('#txtQty').val()) - 1);
        }
    }
</script>
@stop