<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Signup Page</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">


    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Yummy
  * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#D33333'
            });
        </script>
    @endif

    <section class="bg-light min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="container ">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="card border-light-subtle shadow-lg">
                        <div class="card-body p-4 p-md-5">

                            <!-- Heading -->
                            <p class="text-center mb-5 fw-bold fs-3">Enter your details to register</p>

                            <!-- Register Form -->
                            <form action="{{ url('/customer/store') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                                @csrf
                                <div class="row gy-3">

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Enter Your Name" required>
                                            <label for="name">Name</label>
                                            @error('name')
                                                <span class="text-danger"
                                                    style="font-size:12px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="name@example.com" required>
                                            <label for="email">Email</label>
                                            @error('email')
                                                <span class="text-danger"
                                                    style="font-size:12px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Password" required>
                                            <label for="password">Password</label>
                                            @error('password')
                                                <span class="text-danger"
                                                    style="font-size:12px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="password_confirmation" placeholder="Confirm Password" required>
                                            <label for="password_confirmation">Confirm Password</label>
                                            @error('password_confirmation')
                                                <span class="text-danger"
                                                    style="font-size:12px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="age" id="age" placeholder="Age" min="10" max="100" required>
                                            <label for="age">Age</label>
                                            @error('age')
                                                <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Dietary restrictions</label>
                                        <small class="text-muted d-block mb-2">Select any that apply.</small>
                                        <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                                            @foreach(['Vegetarian', 'Non-vegetarian', 'Vegan', 'Low sugar', 'Low salt', 'Low fat', 'Gluten free', 'Dairy free', 'Heart friendly', 'High protein'] as $diet)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="dietary_restrictions[]" value="{{ $diet }}" id="diet_{{ Str::slug($diet) }}">
                                                    <label class="form-check-label" for="diet_{{ Str::slug($diet) }}">
                                                        {{ $diet }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('dietary_restrictions')
                                            <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Health conditions</label>
                                        <small class="text-muted d-block mb-2">Select any that apply.</small>
                                        <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                                            @foreach(['Diabetes', 'High blood pressure', 'Low blood pressure', 'Heart disease', 'Thyroid', 'PCOS', 'Kidney issue', 'Joint pain', 'Anemia', 'Stomach sensitivity'] as $health)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="health_conditions[]" value="{{ $health }}" id="health_{{ Str::slug($health) }}">
                                                    <label class="form-check-label" for="health_{{ Str::slug($health) }}">
                                                        {{ $health }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('health_conditions')
                                            <span class="text-danger" style="font-size:12px;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                   

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-dark btn-lg"
                                                style="background-color:#D33333;border:none;">
                                                Sign up
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                            <!-- Footer -->
                            <p class="mt-5 text-secondary text-center mb-0">
                                Already have an account?
                                <a href="{{ url('/customer/login') }}" class="link-primary text-decoration-none">
                                    Login
                                </a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>


    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let age = parseInt(document.getElementById('age').value);

            if (!email.toLowerCase().endsWith('@gmail.com')) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Email',
                    text: 'Please enter a valid @gmail.com email address.',
                    confirmButtonColor: '#D33333'
                });
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Too Short',
                    text: 'Your password must be at least 6 characters long.',
                    confirmButtonColor: '#D33333'
                });
                return;
            }
            
            if (age > 100) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Age',
                    text: 'Age cannot be more than 100.',
                    confirmButtonColor: '#D33333'
                });
                return;
            }
        });
    </script>
</body>

</html>
