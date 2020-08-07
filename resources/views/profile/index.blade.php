@extends('layouts.access')

@section('content')
<div id="content-pro">
			
	<div class="container custom-gutters-pro">
			<div id="vayvo-progression-author-content-container">
				<ul id="dashboard-sub-menu">
					<li class="current"><a href="/">Account Settings</a></li>
					<li><a href="{{-- route('videos') --}}">Videos(0)</a></li>
					<li><a href="{{-- route('watchlists') --}}">Watchlist(0)</a></li>
				</ul>
				<!-- close #dashboard-sub-menu -->
				
				<div class="row">	
					
					<div class="col">
					
						<form class="account-settings-form">
					
						<h5>General Information</h5>
						<p class="small-paragraph-spacing">By letting us know your name, we can make our support experience much more personal.</p>
						<div class="row">
							<div class="col-sm">
							<div class="form-group">
								<label for="first-name" class="col-form-label">First Name:</label>
								<input type="text" class="form-control" id="first-name" value="Suzie">
							</div>
							</div><!-- close .col -->
							<div class="col-sm">
								<div class="form-group">
									<label for="last-name" class="col-form-label">Last Name:</label>
									<input type="text" class="form-control" id="last-name" value="Smith">
								</div>
							</div><!-- close .col -->
							
						</div><!-- close .row -->
						<hr>
					
						<h5>Account Information</h5>
						<p class="small-paragraph-spacing">You can change the email address you use here.</p>
						
						<div class="row">
							<div class="col-sm">
							<div class="form-group">
								<label for="e-mail" class="col-form-label">E-mail</label>
								<input type="text" class="form-control" id="e-mail" value="suzie@outlook.com">
							</div>
							</div><!-- close .col -->
							<div class="col-sm">
								<div class="form-group">
									<div><label for="button-change" class="col-form-label">&nbsp; &nbsp;</label></div>
									<a href="dashboard-account.html#!" class="btn btn-form">Change E-mail</a>
								</div>
							</div><!-- close .col -->
							
						</div><!-- close .row -->
						
						<hr>
						<h5>Change Password</h5>
						<p class="small-paragraph-spacing">You can change the password you use for your account here.</p>
						<div class="row">
							<div class="col-sm">
							<div class="form-group">
								<label for="password" class="col-form-label">Current Password:</label>
								<input type="text" class="form-control" id="password" value="&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;">
							</div>
							</div><!-- close .col -->
							<div class="col-sm">
							<div class="form-group">
								<label for="new-password" class="col-form-label">New Password:</label>
								<input type="text" class="form-control" id="new-password" placeholder="Minimum of 6 characters">
							</div>
							</div><!-- close .col -->
							<div class="col-sm">
							<div class="form-group">
								<div><label for="confirm-password" class="col-form-label">&nbsp; &nbsp;</label></div>
								<input type="text" class="form-control" id="confirm-password" placeholder="Confirm New Password">
							</div>
							</div><!-- close .col -->
						</div><!-- close .row -->
						
						<hr>
						
						<p><a href="dashboard-account.html" class="btn btn-green-pro">Save Changes</a></p>
						<br>
						</form>
					
					</div><!-- close .col -->
						
					
			
				</div><!-- close .row -->

			</div><!-- close #vayvo-progression-author-content-container -->
			
			
			
			
		<div class="clearfix"></div>
	</div><!-- close .container -->
	
	
	
</div><!-- close #content-pro -->
@endsection
