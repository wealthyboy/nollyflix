

@extends('layouts.app')
@section('content')
<div class="background-image">
   <div class="container">
      <div class="centered-headings-pro pricing-plans-headings">
      </div>
   </div>
   <!-- close .container -->
   <div class="container-fluid">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div id="membership-plan-background">
               <div class="membership-width-container">
                  <div class="container">
                     <div class="membership-required-container">
                        <div class="registration-login-container">
                         <form method="POST" action="{{ route('password.email') }}">
                         @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
						   <div class="aligncenter"><h1>{{ __('Reset Password') }}</h1></div>
                              @csrf
                              <div class="form-group">
                                 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                 @error('email')
                                 <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                                 </span>
                                 @enderror
                              </div>
                             
                              
                              <!-- close .container-fluid -->
                              <div class="form-group aligncenter">
                                <button type="submit" class="btn"> 
                                    {{ __('Send Password Reset Link') }}
                                </button>
                              </div>
                              <div class="aligncenter"><a class="not-a-member-pro" href="{{ route('register') }}">Don't have account? <span>Signup</span></a></div>
                           </form>
                        </div>
                        <!-- close .registration-login-container -->
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <!-- close .container -->
               </div>
               <!-- close .membership-width-container -->
            </div>
            <!-- close #membership-plan-background -->
         </div>
      </div>
   </div>
</div>
<!-- close #content-pro -->
@endsection