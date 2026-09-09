<div class="row">
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Movies</h6>
            <h3>{{ $stats['movies'] }}</h3>
        </div></div>
    </div>
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Movie views</h6>
            <h3>{{ number_format($stats['movie_views']) }}</h3>
        </div></div>
    </div>
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Attributed sales</h6>
            <h3>{{ $stats['total'] }}</h3>
        </div></div>
    </div>
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Customers</h6>
            <h3>{{ $stats['customers'] }}</h3>
        </div></div>
    </div>
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Buys</h6>
            <h3>{{ $stats['buys'] }}</h3>
        </div></div>
    </div>
    <div class="col-md-2 col-sm-6">
        <div class="card"><div class="card-content text-center">
            <h6 class="category">Rents</h6>
            <h3>{{ $stats['rents'] }}</h3>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Attributed revenue</h4>
            </div>
            <div class="card-content">
                @forelse($stats['revenue_by_currency'] as $currency => $amount)
                    <h3 style="display:inline-block; margin-right:25px;">{{ $currency }} {{ number_format($amount, 2) }}</h3>
                @empty
                    <h3>0.00</h3>
                @endforelse
                <p class="text-muted">Revenue shown here only includes purchases or rentals attributed to this profile.</p>
            </div>
        </div>
    </div>
</div>

<h4>Movie performance</h4>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Movie</th>
                <th>Views</th>
                <th>Attributed sales</th>
                <th>Buys</th>
                <th>Rents</th>
                <th>Attributed revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movieBreakdown as $movieStat)
                <tr>
                    <td><strong>{{ $movieStat['video']->title }}</strong></td>
                    <td>{{ number_format($movieStat['views']) }}</td>
                    <td>{{ $movieStat['conversions'] }}</td>
                    <td>{{ $movieStat['buys'] }}</td>
                    <td>{{ $movieStat['rents'] }}</td>
                    <td>
                        @forelse($movieStat['revenue_by_currency'] as $currency => $amount)
                            <div>{{ $currency }} {{ number_format($amount, 2) }}</div>
                        @empty
                            0.00
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No movies are assigned to this profile yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h4 style="margin-top:30px;">Recent attributed transactions</h4>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead><tr><th>Date</th><th>Customer</th><th>Movie</th><th>Type</th><th>Amount</th><th>Channel</th><th>Invoice</th></tr></thead>
        <tbody>
            @forelse($attributedOrders as $order)
                @php
                    $purchaseType = $order->purchase_type ?: optional($order->cart)->purchase_type;
                    $amount = ($order->total !== null && $order->total !== '') ? $order->total : optional($order->cart)->total;
                    $channel = $order->request_from ?: optional($order->cart)->request_from ?: 'web';
                @endphp
                <tr>
                    <td>{{ optional($order->created_at)->format('d M Y H:i') }}</td>
                    <td>{{ optional($order->user)->email ?: '—' }}</td>
                    <td>{{ optional($order->video)->title ?: '—' }}</td>
                    <td><strong>{{ ucfirst(strtolower($purchaseType ?: 'unknown')) }}</strong></td>
                    <td>{{ strtoupper($order->currency ?: 'NGN') }} {{ is_numeric($amount) ? number_format((float) $amount, 2) : '0.00' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $channel)) }}</td>
                    <td>{{ $order->invoice ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No attributed purchases or rentals yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
