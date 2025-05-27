@extends('auth.layouts.authentication')

@section('content')
    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <!-- Left Side Image-->
                            <div class="col-lg-6">
                                <img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}"
                                    alt="{{ translate('Customer Register Page Image') }}" class="img-fit h-100">
                            </div>

                            <!-- Right Side -->
                            <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content"
                                style="height: auto;">
                                <!-- Site Icon -->
                                <div class="size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}"
                                        alt="{{ translate('Site Icon') }}" class="img-fit h-100">
                                </div>

                                <!-- Titles -->
                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">
                                        {{ translate('Create an account') }}</h1>
                                </div>

                                <!-- Register form -->
                                <div class="pt-3">
                                    <div class="">
                                        <form id="reg-form" class="form-default" role="form"
                                            action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <!-- Clinic Information -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Name') }}
                                                            *</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                            value="{{ old('name') }}"
                                                            placeholder="{{ translate('Name') }}" name="name" required>
                                                        @if ($errors->has('name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="address"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Address') }}
                                                            *</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                            value="{{ old('address') }}"
                                                            placeholder="{{ translate('Address') }}" name="address"
                                                            required>
                                                        @if ($errors->has('address'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('address') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Phone Number') }}
                                                            *</label>
                                                        <input type="tel"
                                                            class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                            value="{{ old('phone') }}"
                                                            placeholder="{{ translate('Phone Number') }}" name="phone"
                                                            required>
                                                        @if ($errors->has('phone'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('phone') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}
                                                            *</label>
                                                        <input type="email"
                                                            class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                            value="{{ old('email') }}"
                                                            placeholder="{{ translate('Email') }}" name="email" required>
                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="website"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Website') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('website') ? ' is-invalid' : '' }}"
                                                            value="{{ old('website') }}"
                                                            placeholder="{{ translate('Website') }}" name="website">
                                                        @if ($errors->has('website'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('website') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="specialties"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Specialties') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('specialties') ? ' is-invalid' : '' }}"
                                                            value="{{ old('specialties') }}"
                                                            placeholder="{{ translate('Specialties') }}"
                                                            name="specialties">
                                                        @if ($errors->has('specialties'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('specialties') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="business_license"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Business License/Registration Number') }}</label>
                                                        <input type="file"
                                                            class="form-control-file{{ $errors->has('business_license') ? ' is-invalid' : '' }}"
                                                            name="business_license">
                                                        @if ($errors->has('business_license'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('business_license') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tax_identification"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Tax Identification Number (TIN)') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('tax_identification') ? ' is-invalid' : '' }}"
                                                            value="{{ old('tax_identification') }}"
                                                            placeholder="{{ translate('Tax Identification Number') }}"
                                                            name="tax_identification">
                                                        @if ($errors->has('tax_identification'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('tax_identification') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="operating_hours"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Operating Hours') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('operating_hours') ? ' is-invalid' : '' }}"
                                                            value="{{ old('operating_hours') }}"
                                                            placeholder="{{ translate('Operating Hours') }}"
                                                            name="operating_hours">
                                                        @if ($errors->has('operating_hours'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('operating_hours') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Primary Contact Person -->
                                            <h4 class="fs-16 fw-600 mt-4">{{ translate('Primary Contact Person') }}</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_name"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Full Name') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('contact_name') ? ' is-invalid' : '' }}"
                                                            value="{{ old('contact_name') }}"
                                                            placeholder="{{ translate('Full Name') }}"
                                                            name="contact_name">
                                                        @if ($errors->has('contact_name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('contact_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_position"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Position/Title') }}</label>
                                                        <input type="text"
                                                            class="form-control rounded-0{{ $errors->has('contact_position') ? ' is-invalid' : '' }}"
                                                            value="{{ old('contact_position') }}"
                                                            placeholder="{{ translate('Position/Title') }}"
                                                            name="contact_position">
                                                        @if ($errors->has('contact_position'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('contact_position') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_phone"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Phone Number') }}</label>
                                                        <input type="tel"
                                                            class="form-control rounded-0{{ $errors->has('contact_phone') ? ' is-invalid' : '' }}"
                                                            value="{{ old('contact_phone') }}"
                                                            placeholder="{{ translate('Phone Number') }}"
                                                            name="contact_phone">
                                                        @if ($errors->has('contact_phone'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('contact_phone') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_email"
                                                            class="fs-12 fw-700 text-soft-dark">{{ translate('Email Address') }}</label>
                                                        <input type="email"
                                                            class="form-control rounded-0{{ $errors->has('contact_email') ? ' is-invalid' : '' }}"
                                                            value="{{ old('contact_email') }}"
                                                            placeholder="{{ translate('Email Address') }}"
                                                            name="contact_email">
                                                        @if ($errors->has('contact_email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('contact_email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label for="password"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }}</label>
                                                <div class="position-relative">
                                                    <input type="password"
                                                        class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                        placeholder="{{ translate('Password') }}" name="password">
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                                <div class="text-right mt-1">
                                                    <span
                                                        class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                                                </div>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- password Confirm -->
                                            <div class="form-group">
                                                <label for="password_confirmation"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Confirm Password') }}</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control rounded-0"
                                                        placeholder="{{ translate('Confirm Password') }}"
                                                        name="password_confirmation">
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="form-group text-center mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary rounded-0">{{ translate('Register') }}</button>
                                            </div>
                                        </form>

                                        @if (get_setting('google_login') == 1 ||
                                                get_setting('facebook_login') == 1 ||
                                                get_setting('twitter_login') == 1 ||
                                                get_setting('apple_login') == 1)
                                            <div class="text-center mb-3">
                                                <span
                                                    class="bg-white fs-12 text-gray">{{ translate('Or Join With') }}</span>
                                            </div>
                                            <ul class="list-inline social colored text-center mb-4">
                                                @if (get_setting('facebook_login') == 1)
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                                            class="facebook">
                                                            <i class="lab la-facebook-f"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (get_setting('google_login') == 1)
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('social.login', ['provider' => 'google']) }}"
                                                            class="google">
                                                            <i class="lab la-google"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (get_setting('twitter_login') == 1)
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                                            class="twitter">
                                                            <i class="lab la-twitter"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (get_setting('apple_login') == 1)
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('social.login', ['provider' => 'apple']) }}"
                                                            class="apple">
                                                            <i class="lab la-apple"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        @endif
                                    </div>

                                    <!-- Log In -->
                                    <p class="fs-12 text-gray mb-0">
                                        {{ translate('Already have an account?') }}
                                        <a href="{{ route('user.login') }}"
                                            class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In') }}</a>
                                    </p>
                                    <!-- Go Back -->
                                    <a href="{{ url()->previous() }}"
                                        class="mt-3 fs-14 fw-700 d-flex align-items-center text-primary"
                                        style="max-width: fit-content;">
                                        <i class="las la-arrow-left fs-20 mr-1"></i>
                                        {{ translate('Back to Previous Page') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    @if (get_setting('google_recaptcha') == 1)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <script type="text/javascript">
        @if (get_setting('google_recaptcha') == 1)
            // making the CAPTCHA  a required field for form submission
            $(document).ready(function() {
                $("#reg-form").on("submit", function(evt) {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        //reCaptcha not verified
                        alert("please verify you are human!");
                        evt.preventDefault();
                        return false;
                    }
                    //captcha verified
                    //do the rest of your validations here
                    $("#reg-form").submit();
                });
            });
        @endif
    </script>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
    </div>
@endsection
