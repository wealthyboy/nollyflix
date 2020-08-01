@extends('admin.layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Number of Subscribers</p>
            <h3 class="card-title">{{ $users }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="{{ route('customers.index') }}">View</a>
				</div>
			</div>
      </div>
    </div>
   
    <div class="col-lg-4 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Number of Casts</p>
            <h3 class="card-title">{{ $casts }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="{{ route('casts.index') }}">View</a>
				</div>
			</div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
         <p class="category">Number of Filmer Makers</p>
            <h3 class="card-title">{{ $filmers }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="{{ route('filmers.index') }}">View</a>
				</div>
			</div>
      </div>
    </div>
  
</div>
<div class="row">
   <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Videos Rented Today</p>
            <h3 class="card-title">{{ 0 }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="#">View</a>
				</div>
			</div>
      </div>
   </div>
   <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Videos Sold Today</p>
            <h3 class="card-title">{{ 0 }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="/">View</a>
				</div>
			</div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Number of Movies</p>
            <h3 class="card-title">{{ $videos }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="{{ route('videos.index') }}">View</a>
				</div>
			</div>
      </div>
   </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">All Sales</p>
            <h3 class="card-title">{{ 0 }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="#">View</a>
				</div>
			</div>
      </div>
   </div>
   <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
         <div class="card-content">
            <p class="category">Sales Total</p>
            <h3 class="card-title">{{ 0 }}</h3>
         </div>
         <div class="card-footer text-right">
				<div class="stats">
					<i class="material-icons text-danger">forward</i> <a href="#">View</a>
				</div>
			</div>
      </div>
   </div>
  
 
</div>

@endsection
