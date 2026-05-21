@extends('Customer.Layout.app')
@section('content')
    <section id="book-a-table" class="book-a-table section">





        <div class="container section-title" data-aos="fade-up">
            <h2 style="color: black">Recipe Finder by Image</h2>
            <p><span>Search By</span> <span class="description-title">Upload an Image to Recognize Recipes<br></span></p>
        </div>



        <div class="container" style="background-color: #ffe6e6;">
            <div class="row g-0 " data-aos="fade-up" data-aos-delay="100">

                {{-- image section --}}
                <div class="col-lg-4 reservation-img" style="background-image: url('{{ asset('/assets/img/s2.jpg') }}');">
                </div>

                {{-- form section --}}
                <div class="col-12 col-md-8 justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                    <form action="/predict" method="POST" enctype="multipart/form-data" role="form" class="w-100">
                        @csrf
                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group mt-3 text-center">
                            <label class="mb-2 fw-semibold">
                                <p style="font-size: 26px; ">Upload Food Image for AI Recognition</p>
                            </label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted d-block mt-2">
                                Supported formats: JPG, PNG, JPEG
                            </small>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="">Search Recipes</button>
                        </div>
                    </form>

                    <div class="justify-content-center align-items-center text-muted ms-5 mt-5">
                        <p>
                            <i class="bi bi-check-circle-fill"></i>
                            "Snap a photo of your ingredients, and let smart technology do the rest."<br>
                            <i class="bi bi-check-circle-fill"></i>
                            "Discover delicious recipes in seconds."
                        </p>
                    </div>

                </div>

            </div>

        </div>



        {{-- gallery section --}}
        {{-- <section id="gallery" class="gallery section light-background mt-4" style="background-color: #ffe6e6;">

            <div class="container section-title" data-aos="fade-up">
                <h2 style="color: black">Gallery</h2>
                <p><span>Check</span> <span class="description-title">Our Gallery</span></p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "centeredSlides": true,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 0
                },
                "768": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                },
                "1200": {
                  "slidesPerView": 5,
                  "spaceBetween": 20
                }
              }
            }
          </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-1.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-2.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-3.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-4.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-5.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="assets/img/gallery/gallery-6.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-7.jpg" class="img-fluid" alt=""></a></div>
                        <div class="swiper-slide"><a class="" data-gallery="images-gallery" href=""><img
                                    src="/assets/img/gallery/gallery-8.jpg" class="img-fluid" alt=""></a></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section> --}}






    </section>
@endsection
