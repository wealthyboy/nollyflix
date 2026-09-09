<div class="row">
    <div class="col-md-3 col-sm-6"><div class="card"><div class="card-content text-center"><h6 class="category">Attributed conversions</h6><h3>{{ $stats['total'] }}</h3></div></div></div>
    <div class="col-md-3 col-sm-6"><div class="card"><div class="card-content text-center"><h6 class="category">Buys</h6><h3>{{ $stats['buys'] }}</h3></div></div></div>
    <div class="col-md-3 col-sm-6"><div class="card"><div class="card-content text-center"><h6 class="category">Rents</h6><h3>{{ $stats['rents'] }}</h3></div></div></div>
    <div class="col-md-3 col-sm-6"><div class="card"><div class="card-content text-center"><h6 class="category">Attributed revenue</h6>@forelse($stats['revenue_by_currency'] as $currency => $amount)<h4>{{ $currency }} {{ number_format($amount, 2) }}</h4>@empty<h4>0.00</h4>@endforelse</div></div></div>
</div>
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
