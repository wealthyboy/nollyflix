@extends('admin.layouts.app')

@section('pagespecificstyles')
<style>
    .access-page { max-width:1100px; margin:0 auto; }
    .access-page .card-content { padding:26px; }
    .access-page h2 { margin-top:8px; font-size:25px; font-weight:600; }
    .access-page .access-muted { color:#687384; line-height:1.7; }
    .access-page .access-customer { background:#f4f6fa; border:1px solid #e0e5ee; border-radius:10px; padding:18px; margin:20px 0; overflow-wrap:anywhere; }
    .access-page label.access-label, .access-page legend { display:block; color:#283344; font-size:14px; font-weight:600; margin:20px 0 8px; }
    .access-page .access-input { width:100%; border:1px solid #cbd3df; border-radius:8px; background:#fff; color:#263247; padding:12px; min-height:46px; }
    .access-page textarea.access-input { min-height:100px; resize:vertical; }
    .access-page .access-types { display:flex; gap:12px; flex-wrap:wrap; }
    .access-page .access-type { display:block; flex:1; min-width:180px; padding:18px; border:1px solid #cbd3df; border-radius:10px; cursor:pointer; color:#263247; }
    .access-page .access-type small { display:block; margin:8px 0 0 22px; }
    .access-page .access-type input, .access-page .access-confirm input { appearance:auto; position:static; opacity:1; margin-right:8px; }
    .access-page .access-confirm { display:flex; align-items:flex-start; gap:10px; margin:24px 0 12px; color:#283344; line-height:1.6; }
    .access-page .access-confirm input { margin-top:5px; }
    .access-page .btn { font-weight:600; }
    .access-page .access-history { overflow-x:auto; }
    .access-page .access-history table { min-width:680px; }
    .access-page .access-history td { vertical-align:top; }
</style>
@endsection

@section('content')
<div class="access-page">
    <a href="{{ route('customers.index') }}" class="btn btn-default btn-sm">&larr; Back to customers</a>
    @if(session('access_success'))<div class="alert alert-success" role="status">{{ session('access_success') }}</div>@endif
    @if(session('access_warning'))<div class="alert alert-warning" role="alert">{{ session('access_warning') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert"><strong>Please check the form.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card"><div class="card-content">
        <h2>Grant movie access</h2>
        <p class="access-muted">Select a movie and the access type. We will save the customer's access as an order, then email a link to log in and watch.</p>
        <div class="access-customer"><strong>{{ $customer->fullname() }}</strong><br>{{ $customer->email }}</div>
        <div class="alert alert-info">This does not charge the customer or verify a payment. New manual-access orders have a zero total and are marked as admin grants. Existing matching access is reused.</div>

        <form action="{{ route('customers.movie-access.store', ['customer' => $customer->id]) }}" method="post" data-movie-access-form>
            @csrf
            <input type="hidden" name="grant_token" value="{{ old('grant_token', (string) \Illuminate\Support\Str::uuid()) }}">
            <label class="access-label" for="access-movie-search">Find a movie</label>
            <input class="access-input" id="access-movie-search" type="search" placeholder="Filter movies by title" autocomplete="off">
            <label class="access-label" for="access-video">Movie</label>
            <select class="access-input" id="access-video" name="video_id" required>
                <option value="">Select a movie</option>
                @foreach($videos as $video)
                    <option value="{{ $video->id }}" {{ (string) old('video_id') === (string) $video->id ? 'selected' : '' }}>{{ $video->title }}</option>
                @endforeach
            </select>
            @if($videos->isEmpty())<p class="text-warning">There are no active movies to grant. Activate a movie first.</p>@endif

            <fieldset style="border:0;padding:0;margin:0;">
                <legend>Access type</legend>
                <div class="access-types">
                    <label class="access-type"><input type="radio" name="purchase_type" value="rent" {{ old('purchase_type', 'rent') === 'rent' ? 'checked' : '' }} required><strong>Rent</strong><small>48 hours from the time access is granted. Resending an email does not extend this period.</small></label>
                    <label class="access-type"><input type="radio" name="purchase_type" value="buy" {{ old('purchase_type') === 'buy' ? 'checked' : '' }} required><strong>Buy</strong><small>Added to the customer's library with no rental expiry.</small></label>
                </div>
            </fieldset>
            <label class="access-label" for="access-reason">Reason for granting access</label>
            <textarea class="access-input" id="access-reason" name="reason" maxlength="1000" required placeholder="For example: payment checked, but the customer did not receive movie access.">{{ old('reason') }}</textarea>
            <label class="access-label" for="access-reference">Payment or support reference (optional)</label>
            <input class="access-input" id="access-reference" name="payment_reference" maxlength="128" value="{{ old('payment_reference') }}" placeholder="Internal reference for your records">
            <p class="access-muted">The reason, reference and administrator are kept in the access history, not included in the customer email.</p>
            <label class="access-confirm"><input type="checkbox" name="confirmed" value="1" required {{ old('confirmed') ? 'checked' : '' }}><span>I have checked the customer's payment or am authorizing complimentary access to this movie.</span></label>
            <button type="submit" class="btn btn-primary" {{ $videos->isEmpty() ? 'disabled' : '' }}>Grant access &amp; send email</button>
        </form>
    </div></div>

    <div class="card"><div class="card-content">
        <h2>Recent access grants</h2>
        <p class="access-muted">Resend the email if delivery fails. No new order is created and the rental expiry stays the same. Latest 20 grants.</p>
        <div class="access-history"><table class="table table-striped">
            <thead><tr><th>Movie / order</th><th>Access</th><th>Audit details</th><th>Email</th></tr></thead>
            <tbody>
            @forelse($grants as $grant)
                @php
                    $type = strtolower((string) (optional(optional($grant->order)->cart)->purchase_type ?: optional($grant->order)->purchase_type));
                    $expires = optional($grant->order)->video_rent_expires;
                    $expired = $type === 'rent' && (!$expires || !$expires->isFuture());
                @endphp
                <tr>
                    <td><strong>{{ optional($grant->video)->title }}</strong><br><a href="{{ route('admin.orders.show', ['order' => $grant->order_id]) }}">{{ optional($grant->order)->invoice }}</a><br><small>{{ $grant->order_created ? 'Manual-access order' : 'Existing order reused' }}</small></td>
                    <td>{{ ucfirst($type) }}<br><small>{{ $type === 'rent' ? ($expired ? 'Expired' : 'Until '.$expires->format('j M Y, H:i').' '.config('app.timezone')) : 'No rental expiry' }}</small></td>
                    <td>{{ $grant->reason }}<br>@if($grant->payment_reference)<small>Reference: {{ $grant->payment_reference }}</small><br>@endif<small>By {{ optional($grant->administrator)->fullname() ?: 'Former administrator' }}<br>{{ $grant->created_at->format('j M Y, H:i') }}</small></td>
                    <td>
                        @if($grant->email_error)<span class="text-danger">{{ $grant->email_error }}</span>
                        @elseif($grant->email_sent_at)<span class="text-success">Sent {{ $grant->email_sent_at->format('j M Y, H:i') }}</span>
                        @else<span class="access-muted">Not sent yet</span>@endif
                        @if(!$expired)
                            <form method="post" action="{{ route('customers.movie-access.resend', ['customer' => $customer->id, 'grant' => $grant->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-default btn-sm">Resend email</button>
                            </form>
                        @else<p><small>Grant a new rental if another 48 hours is needed.</small></p>@endif
                    </td>
                </tr>
            @empty<tr><td colspan="4">No manual access has been granted to this customer yet.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div></div>
</div>
@endsection

@section('inline-scripts')
(function () {
    var search = document.getElementById('access-movie-search');
    var select = document.getElementById('access-video');
    if (!search || !select) return;
    var choices = Array.prototype.slice.call(select.options).map(function (option) {
        return { value: option.value, text: option.text };
    });
    search.addEventListener('input', function () {
        var term = search.value.toLowerCase().trim();
        var selected = select.value;
        select.textContent = '';
        choices.forEach(function (choice) {
            if (!choice.value || choice.value === selected || choice.text.toLowerCase().indexOf(term) !== -1) {
                var option = document.createElement('option');
                option.value = choice.value;
                option.textContent = choice.text;
                option.selected = choice.value === selected;
                select.appendChild(option);
            }
        });
    });
    document.querySelector('[data-movie-access-form]').addEventListener('submit', function (event) {
        var button = this.querySelector('button[type="submit"]');
        if (button.disabled) { event.preventDefault(); return; }
        button.disabled = true;
        button.textContent = 'Saving access…';
    });
}());
@endsection
