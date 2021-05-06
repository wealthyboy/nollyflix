@extends('layouts.checkout')

@section('content')

<div id="content-pro">
    <section class="pb-4 mt-1">
    {{ $params }}
       <mobile-payment :params={{ $params }} />
    </div>
</section>

</div><!-- close #content-pro -->
@endsection
