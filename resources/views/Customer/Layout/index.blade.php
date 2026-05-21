@extends('Customer.Layout.app')
@section('content')
    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section light-background">
            <div class="container">
                <div class="row gy-4 justify-content-center justify-content-lg-between">
                    <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up">AI-Powered Recipe Recommendation <br>& Nutrition System</h1>
                        <p data-aos="fade-up" data-aos-delay="100">Discover healthy recipes, analyze nutrition, and
                            generate personalized meal plans.</p>
                        <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                            <a href="{{ url('/customer/login') }}" class="btn-get-started">Get Started</a>
                        </div>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                        <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>
        </section>




        <!-- About Section -->
        <section id="about" class="about section mt-5">
            <div class="container section-title" data-aos="fade-up">
                <h2 style="color: black">About Us<br></h2>
                <p><span>Learn More</span> <span class="description-title">About Us</span></p>
            </div>

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('assets/img/about.jpg') }}" class="img-fluid h-75 " alt="">
                        <div class="book-a-table ">
                            <h3>Need Help</h3>
                            <p>+92 306 1562969</p>
                        </div>
                    </div>
                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
                        <div class="content ps-0 ps-lg-5">
                            <p class="fst-italic">What Our AI Can Do</p>
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> <span>Food Image Recognition.</span></li>
                                <p>Upload food image and get instant recipe suggestions.</p>
                                <li><i class="bi bi-check-circle-fill"></i> <span>Ingredient-Based Recipe Search.</span>
                                </li>
                                <p>Finds recipes using available ingredients.</p>
                                <li><i class="bi bi-check-circle-fill"></i> <span>Nutritional Analysis.</span></li>
                                <p>Detailed calories, proteins, fats, and vitamins.</p>
                                <li><i class="bi bi-check-circle-fill"></i> <span>Personalized Meal Planning.</span>
                                </li>
                                <p>AI-generated weekly meal plans based on your goals.</p>
                            </ul>
                            <div class="position-relative ">
                                <img src="{{ asset('assets/img/about1.jpg') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>





        <!-- Why Us Section -->
        <section id="why-us" class="why-us section light-background mt-5">
            <div class="container">
                <div class="row gy-4">

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="why-box">
                            <h3>Why Choose Us</h3>
                            <p><i class="bi bi-check-circle-fill"></i> AI-Powered Recommendations <br><br>
                                <i class="bi bi-check-circle-fill"></i> Accurate Nutrition Data
                                <br><br><i class="bi bi-check-circle-fill"></i> Personalized Health Goals
                                <br> <br><i class="bi bi-check-circle-fill"></i> Easy-to-Use Interface
                            </p>
                            <div class="text-center">
                                <a href="#" class="more-btn"><span>Learn More</span> <i
                                        class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-8 d-flex align-items-stretch">
                        <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                            <!-- Ingredient-Based Recipe Recommendation -->
                            <div class="col-xl-4">
                                <a href="{{ url('/ingredient/search') }}">

                                    <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-basket"></i>
                                        <h4>Ingredient-Based Recipes</h4>
                                        <p>
                                            Enter the ingredients you have and our AI system will intelligently suggest
                                            recipes that can be prepared using available items.
                                        </p>
                                    </div>
                                </a>

                            </div><!-- End Icon Box -->

                            <!-- AI Food Image Recognition -->
                            <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                                <a href="{{ url('/image/recognition') }}">
                                    <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-camera"></i>
                                        <h4>Food Image Recognition</h4>
                                        <p>
                                            Upload a food image and our CNN-based AI model identifies the dish
                                            and recommends relevant recipes with nutritional details.
                                        </p>
                                    </div>
                                </a>
                            </div><!-- End Icon Box -->

                            <!-- Nutrition & Meal Planning -->
                            <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                                <a href="{{ url('/meal/planner') }}">
                                    <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                        <i class="bi bi-heart-pulse"></i>
                                        <h4> Meal Planning</h4>
                                        <p>
                                            Get detailed nutritional analysis and AI-generated personalized meal plans
                                            based on your health goals and dietary preferences.
                                        </p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>




        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section light-background mt-5">

            <div class="container section-title" data-aos="fade-up">
                <h2 style="color: black">USER REVIEWS</h2>
                <p>See What <span class="description-title">People Are Saying About RecipeFinder</span></p>
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
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
            </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="testimonial-content">
                                            <p>
                                                <i class="bi bi-quote quote-icon-left"></i>
                                                <span>Uploading a photo of my ingredients was so easy, and the app suggested
                                                    amazing recipes instantly! My dinner was ready in no time.</span>
                                                <i class="bi bi-quote quote-icon-right"></i>
                                            </p>
                                            <h3>Mary Thompson</h3>
                                            <h4>Home Cook</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 text-center">
                                        <img src="assets/img/testimonials/testimonials-1.jpg"
                                            class="img-fluid testimonial-img" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="testimonial-content">
                                            <p>
                                                <i class="bi bi-quote quote-icon-left"></i>
                                                <span>This app is a game-changer! I no longer waste ingredients. Just snap a
                                                    photo, and I get recipes I can make immediately.</span>
                                                <i class="bi bi-quote quote-icon-right"></i>
                                            </p>
                                            <h3>David Lee</h3>
                                            <h4>Food Enthusiast</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 text-center">
                                        <img src="assets/img/testimonials/testimonials-2.jpg"
                                            class="img-fluid testimonial-img" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="testimonial-content">
                                            <p>
                                                <i class="bi bi-quote quote-icon-left"></i>
                                                <span>I love how fast it works! I just upload a photo, and it recognizes the
                                                    ingredients perfectly. Cooking has never been this fun.</span>
                                                <i class="bi bi-quote quote-icon-right"></i>
                                            </p>
                                            <h3>Emma Rodriguez</h3>
                                            <h4>Student Chef</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 text-center">
                                        <img src="assets/img/testimonials/testimonials-3.jpg"
                                            class="img-fluid testimonial-img" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section>





        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <div class="container" data-aos="fade-up" data-aos-delay="100"
                style="background-color: #ffe6e6; padding: 20px; border-radius: 8px;">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                            <i class="icon bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Our Location</h3>
                                <p>Online Platform – Access Anywhere</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
                            <i class="icon bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Contact Support</h3>
                                <p>+92 306 1562969</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
                            <i class="icon bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p>hamirzaman2003@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
                            <i class="icon bi bi-clock flex-shrink-0"></i>
                            <div>
                                <h3>System Availability</h3>
                                <p><strong>Access:</strong> 24/7 Online <strong>Mode:</strong> Web-Based Application</p>
                            </div>
                        </div>
                    </div>

                </div>

                <form action="{{ url('/contact') }}" method="Post" data-aos="fade-up" data-aos-delay="600"
                    class="mt-5">
                    @csrf
                    <div class="row gy-4">

                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Your Name"
                                required="">
                        </div>

                        <div class="col-md-6 ">
                            <input type="email" class="form-control" name="email" placeholder="Your Email"
                                required="">
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" placeholder="Subject"
                                required="">
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                        </div>

                        <div class="align-items-center justify-content-center d-flex">
                            <button type="submit" class="hamir">Send Message</button>
                        </div>


                    </div>
                </form>

                {{-- for sweet alert --}}
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '{{ session('success') }}',
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ $errors->first() }}',
                        });
                    </script>
                @endif
            </div>

        </section>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </body>

    </html>
@endsection
