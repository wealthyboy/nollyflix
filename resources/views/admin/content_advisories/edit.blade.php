@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        @include('errors.errors')
        <div class="card"><div class="card-content">
            <h4 class="card-title">Edit content advisory</h4>
            <p>Changes to this label also appear on titles that already use it.</p>
            <form action="{{ route('content-advisories.update', $advisory) }}" method="post">
                @csrf
                @method('PATCH')
                @include('admin.content_advisories.form')
            </form>
            <a href="{{ route('content-advisories.index') }}">Back to Content Advisories</a>
        </div></div>
    </div>
</div>
@endsection
