



@extends('layouts.app')

@section('content')

<div class="background-image">
			
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="membership-plan-background">
	  	 		<div class="membership-width-container">
						
   					 <div class="membership-required-container">
      					 <div class="registration-login-container">
                           <form method="POST" action="{{ route('register') }}">
                                <div class="aligncenter"><h1>Sign Up</h1></div>

                                @csrf
      							<div class="form-group">
                                   <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"  placeholder="Name"  value="{{ old('name') }}" required autocomplete="name" autofocus>

                                   @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
      							</div>
                                <div class="form-group">
                                   <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name"  placeholder="Last Name"  value="{{ old('name') }}" required autocomplete="name" autofocus>

                                   @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
      							</div>
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  placeholder="Email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
      							</div>
                                <div class="form-group">
                                   <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"  name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
      							</div>
      						
                                <div class="form-group">
                                   <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
      							</div>
								 
       						
      							<div class="form-group aligncenter">
      								 <button type="submit" class="btn">Submit</button>
      							</div>
      							 
							
      							<div class="aligncenter"><a class="not-a-member-pro" href="{{ route('login') }}">Already   have an  account? <span>Login</span></a></div>
			   			
      						</form>
      					 </div><!-- close .registration-login-container -->
   					 </div>
						
				
						<div class="clearfix"></div>
	  	 		</div><!-- close .membership-width-container -->
			</div><!-- close #membership-plan-background -->
            
        </div>
    </div>
</div>
         
       
               
</div><!-- close #content-pro -->

@endsection

