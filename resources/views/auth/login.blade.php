@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row">
    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center min-vh-lg-100 position-relative bg-light px-0">
        <!-- Logo & Language -->    
        <div class="position-absolute top-0 start-0 end-0 mt-3 mx-3">
            <div class="d-none d-lg-flex justify-content-between">
                <a href="{{ route('home') }}">
                    <img class="w-100" src="{{ asset('assets/svg/logos/logo.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="min-width: 7rem; max-width: 7rem;">
                    <img class="w-100" src="{{ asset('assets/svg/logos-light/logo.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="min-width: 7rem; max-width: 7rem;">
                </a>
            </div>
        </div>
        <!-- End Logo & Language -->

        <div style="max-width: 23rem;">
            <div class="text-center mb-5">
            <img class="img-fluid" src="{{ asset('assets/svg/illustrations/oc-chatting.svg') }}" alt="Image Description" style="width: 12rem;" data-hs-theme-appearance="default">
            <img class="img-fluid" src="{{ asset('assets/svg/illustrations-light/oc-chatting.svg') }}" alt="Image Description" style="width: 12rem;" data-hs-theme-appearance="dark">
            </div>

            <div class="mb-5">
                <h2 class="display-5">Build digital products with:</h2>
            </div>

            <!-- List Checked -->
            <ul class="list-checked list-checked-lg list-checked-primary list-py-2">
                <li class="list-checked-item">
                    <span class="d-block fw-semibold mb-1">All-in-one tool</span>
                    Build, run, and scale your apps - end to end
                </li>

                <li class="list-checked-item">
                    <span class="d-block fw-semibold mb-1">Easily add &amp; manage your services</span>
                    It brings together your tasks, projects, timelines, files and more
                </li>
            </ul>
            <!-- End List Checked -->

            <div class="row justify-content-between mt-5 gx-3">
                <div class="col text-center mt-5">
                    <i class="fs-6 mb-0 text-muted">
                        Made with&nbsp;
                        <svg class="heart-svg m-1" viewBox="0 0 32 29.6">
                            <path d="M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z"/>
                        </svg>
                        by <a class="text-muted text-decoration-none" href="http://tumbuhdanberproses.azisalvriyanto.net" target="_blank">Alvriyanto Azis</a>
                    </i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </div>
    <!-- End Col -->

    <div class="col-lg-6 d-flex justify-content-center align-items-center min-vh-lg-100">
        <div class="w-100 content-space-t-4 content-space-t-lg-2 content-space-b-1" style="max-width: 25rem;">
            <!-- Form -->
            <form id="login" class="js-validate needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="text-center">
                    <div class="mb-5">
                        <h1 class="display-5">Sign in</h1>
                        <!-- <p>Don't have an account yet? <a class="link" href="{{ route('register') }}">Sign up here</a></p> -->
                    </div>

                    <!-- <div class="d-grid mb-4">
                        <a class="btn btn-white btn-lg" href="#">
                            <span class="d-flex justify-content-center align-items-center">
                                <img class="avatar avatar-xss me-2" src="{{ asset('assets/svg/brands/google-icon.svg') }}" alt="Image Description">
                                Sign in with Google
                            </span>
                        </a>
                    </div>

                    <span class="divider-center text-muted mb-4">OR</span> -->
                </div>

                <!-- Form -->
                <div class="mb-4">
                    <label class="form-label" for="signinSrEmail">Your email</label>
                    <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail" tabindex="1" placeholder="email@address.com" aria-label="email@address.com" value="{{ old('email') }}" required>
                    <span class="invalid-feedback">Please enter a valid email address.</span>
                </div>
                <!-- End Form -->

                <!-- Form -->
                <div class="mb-4">
                    <label class="form-label w-100" for="signupSrPassword" tabindex="0">
                        <span class="d-flex justify-content-between align-items-center">
                            <span>Password</span>
                            <!-- <a class="form-label-link mb-0" href="{{ route('password.request') }}">Forgot Password?</a> -->
                        </span>
                    </label>

                    <div class="input-group input-group-merge" data-hs-validation-validate-class>
                        <input type="password" class="js-toggle-password form-control form-control-lg" name="password" id="signupSrPassword" placeholder="8+ characters required" aria-label="8+ characters required" required minlength="8" data-hs-toggle-password-options='{
                            "target": "#changePassTarget",
                            "defaultClass": "bi-eye-slash",
                            "showClass": "bi-eye",
                            "classChangeTarget": "#changePassIcon"
                        }'>
                        <a id="changePassTarget" class="input-group-append input-group-text" href="javascript:;">
                            <i id="changePassIcon" class="bi-eye"></i>
                        </a>
                    </div>

                    <span class="invalid-feedback">Please enter a valid password.</span>
                </div>
                <!-- End Form -->

                <!-- Form Check -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" <?= old('remember') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="termsCheckbox">Remember me</label>
                </div>
                <!-- End Form Check -->

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Sign in</button>
                </div>
            </form>
            <!-- End Form -->
        </div>
    </div>
    <!-- End Col -->
</div>
@endsection

@section('javascript')
<script src="{{ asset('assets/vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>
<script src="{{ asset('assets/vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

<!-- JS Plugins Init. -->
<script>
    (function () {
        window.onload = function () {
            // INITIALIZATION OF BOOTSTRAP VALIDATION
            HSBsValidation.init('.js-validate', {
                onSubmit: data => {
                    data.event.preventDefault();
                    return $("#login").submit();
                }
            })

            // INITIALIZATION OF TOGGLE PASSWORD
            new HSTogglePassword('.js-toggle-password');

            // INITIALIZATION OF SELECT
            HSCore.components.HSTomSelect.init('.js-select');
        }
    })();
</script>
@endsection