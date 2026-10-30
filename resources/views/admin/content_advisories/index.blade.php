@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('includes.success')
        @include('errors.errors')
        <h3>Content Advisories</h3>
        <p>Manage the labels available when uploading or editing a movie or series. Only selected labels appear to viewers.</p>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-content">
            <h4 class="card-title">Add content advisory</h4>
            @if(\App\User::userHasPermission(2))
                <form action="{{ route('content-advisories.store') }}" method="post">
                    @csrf
                    @include('admin.content_advisories.form')
                </form>
            @else
                <p>You do not have permission to add labels.</p>
            @endif
        </div></div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-content">
            <h4 class="card-title">Available labels</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Label</th><th>Order</th><th>Titles</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($advisories as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>{{ $item->videos_count }}</td>
                            <td>
                                @if(\App\User::userHasPermission(3))
                                    <a href="{{ route('content-advisories.edit', $item) }}" class="btn btn-primary btn-simple btn-xs">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No labels yet. Add one here or run ContentAdvisorySeeder to add the default labels.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
</div>
@endsection
