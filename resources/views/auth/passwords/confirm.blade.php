@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Confirm Password') }}</div>

                <div class="card-body">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




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
                     <div class="membership-required-container">
                        <div class="registration-login-container">
                        <form method="POST" action="{{ route('password.confirm') }}">
                         @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                           <div class="aligncenter"><h1>{{ __('Confirm Password') }}</h1></div>
                           
                           <div class="aligncenter">{{ __('Please confirm your password before continuing.') }}</div>

                                @csrf
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                             
                        
                              <!-- close .container-fluid -->
                              <div class="form-group aligncenter">
                                <button type="submit" class="btn"> 
                                 {{ __('Confirm Password') }}
                                </button>
                              </div>
                              <div class="aligncenter">
                                  @if (Route::has('password.request'))
                                    <a class="not-a-member-pro" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif

                                
                               </div>
                           </form>
                        </div>
                        <!-- close .registration-login-container -->
                     </div>
                     <div class="clearfix"></div>
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
