@extends('layouts._layout')

@section('title', 'Tin tức')

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
<section class="home-icon blog-main-section blog-list-outer">
    <div class="icon-default">
        <img src="images/scroll-arrow.png" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="blog-right-section">
                    @foreach($news as $item)
                    <div class="blog-right-listing wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                        <a href="{{route('new_detail').'/'.$item->alias.'-'.$item->id}}">
                            <div class="feature-img">
                                @if(!($item->banner == '' || $item->banner == null))
                                <img src="{{asset('userfiles') .'/'. json_decode($item->banner)[0]->key}}" />
                                @else
                                <img src="{{asset('newtheme/images/img31.jpg')}}" alt="">
                                @endif
                                <div class="date-feature">{{date("d/m", strtotime($item->created_at))}}</div>
                            </div>
                        </a>
                        <div class="feature-info">
                            <span><i class="icon-user"></i>&nbspChess art coffee</span>
                            <h5><a href="{{route('new_detail').'/'.$item->alias.'-'.$item->id}}">{{$item->title}}</a></h5>
                            <p>{{$item->summary}}</p>
                            <a href="{{route('new_detail').'/'.$item->alias.'-'.$item->id}}" class="read-arrow">Đọc tiếp</a>
                        </div>
                    </div>
                    @endforeach
                    <div class="gallery-pagination">
                        <div class="gallery-pagination-inner">
                            {{ $news->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@stop