@extends('layouts.access')

@section('content')
<div id="content-pro">
			
	<div class="container custom-gutters-pro">
	    <div class="row">	
			<div class="col-12">
				@include('includes.success')
				@include('includes.errors')
            </div>
        </div>
		<div id="vayvo-progression-author-sidebar">
			<div>
			<profile-picture />
            </div>
			@include('partials.profile_aside',['user' => $user])
		</div><!-- close #vayvo-progression-author-content-sidebar -->
			
		<div id="vayvo-progression-author-content-container">
		    @include('partials.profile_nav_link',['user' => $user,'current' => 'profile'])

			    <!-- close #dashboard-sub-menu -->
				<div class="row">	
					<div class="col">
						<form action="{{ route('profile.update',['profile' => $user->id ]) }}" method="POST" class="account-settings-form">
					     	@csrf
                            @method('PATCH')
							<h5 class="ml-4">General Information</h5>
							<p class="small-paragraph-spacing"></p>
							<div class="row">
								<div class="col-md-6">
								<div class="form-group">
									<label for="first-name" class="col-form-label">First Name:</label>
									<input type="text" name="name" class="form-control" id="first-name" value="{{ $user->name }}">
								</div>
								</div><!-- close .col -->
								<div class="col-md-6">
									<div class="form-group">
										<label for="last-name" class="col-form-label">Last Name:</label>
										<input type="text" name="last_name" class="form-control" id="last-name" value="{{ $user->last_name }}">
									</div>
								</div><!-- close .col -->

								@if (!$user->isSubscriber()  && !$user->isAdmin())
								<div class="col-md-12">
									<div class="form-group">
										<label for="description">Bio</label>
										<textarea class="form-control" name="description" id="description" rows="3">{{ $user->description }}</textarea>
									</div>
								</div><!-- close .col -->
								@endif

								<div class="col-md-12">
							     	<div class="form-group float-right">
								     	<button type="submit" class="btn btn-green-pro">Save Changes</button>
                                    </div>
                                </div>
							</div><!-- close .row -->
						
						</form>

						<form action="/change/password" method="POST" class="account-settings-form">
						    @csrf
							<hr>
							<h5 class="ml-4">Change Password</h5>
							<p class="small-paragraph-spacing"></p>
							<div class="row">
								<div class="col-sm">
									<div class="form-group">
										<label for="password" class="col-form-label">Current Password:</label>
										<input type="password" class="form-control" name="old_password" id="password" value="&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;">
									</div>
								</div><!-- close .col -->
								<div class="col-sm">
									<div class="form-group">
										<label for="new-password" class="col-form-label">New Password:</label>
										<input type="password" class="form-control" name="password" id="new-password" placeholder="New Password">
									</div>
								</div><!-- close .col -->
								<div class="col-sm">
									<div class="form-group">
										<div><label for="confirm-password" class="col-form-label">&nbsp; &nbsp;</label></div>
										<input type="password" class="form-control" name="password_confirmation" id="confirm-password" placeholder="Confirm New Password">
									</div>
								</div><!-- close .col -->
								<div class="col-md-12">
							     	<div class="form-group float-right">
								     	<button type="submit" class="btn btn-green-pro">Save Changes</button>
                                    </div>
                                </div>
							</div><!-- close .row -->
							
							<hr>
							
							<br>
						</form>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close #vayvo-progression-author-content-container -->
		<div class="clearfix"></div>
	</div><!-- close .container -->
</div><!-- close #content-pro -->
@endsection
