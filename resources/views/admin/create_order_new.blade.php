@extends('layouts._adminlayout')
@section('content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Tạo đơn</h3>
        </div>
    </div>
</div>
<div class="m-content">
    <!--Begin::Section-->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="card" style="max-width: 20rem;">
                        <!-- Card image -->
                        <img class="card-img-top" src="https://mdbootstrap.com/img/Mockups/Lightbox/Thumbnail/img%20(67).jpg" alt="Card image cap">

                        <!-- Card content -->
                        <div class="card-body text-white rgba-black-light p-2">
                            Sample text
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Section-->
</div>
<style>
    .card {
        margin: 1rem auto;
        position: relative;
    }

    .card-body {
        z-index: 2;
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .rgba-black-light,
    .rgba-black-light:after {
        background-color: #0000004d;
    }

    .text-white {
        color: #fff !important;
    }

    .p-2 {
        padding: .5rem !important;
    }
</style>
@endsection