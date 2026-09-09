@php
    $profileName = isset($cast) ? $cast->fullname() : (isset($filmer) ? $filmer->fullname() : 'this profile');
@endphp

<style>
    .nfx-stats-wrap {
        padding-top: 12px;
    }

    .nfx-stats-hero {
        background: linear-gradient(135deg, #202127 0%, #34363f 100%);
        color: #fff;
        border-radius: 10px;
        padding: 22px 24px;
        margin-bottom: 22px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    }

    .nfx-stats-hero h3 {
        margin: 0 0 6px;
        color: #fff;
        font-weight: 600;
    }

    .nfx-stats-hero p {
        margin: 0;
        color: rgba(255, 255, 255, .72);
        font-size: 13px;
    }

    .nfx-stat-card {
        background: #fff;
        border: 1px solid #ececf1;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
        min-height: 112px;
        box-shadow: 0 3px 14px rgba(26, 27, 35, .06);
        display: flex;
        align-items: center;
    }

    .nfx-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        background: #f5f5f7;
        color: #e53935;
        flex: 0 0 46px;
    }

    .nfx-stat-icon .material-icons {
        font-size: 23px;
    }

    .nfx-stat-copy small {
        display: block;
        color: #888b94;
        text-transform: uppercase;
        letter-spacing: .6px;
        font-weight: 600;
        font-size: 10px;
        margin-bottom: 5px;
    }

    .nfx-stat-copy strong {
        display: block;
        color: #25262b;
        font-size: 25px;
        line-height: 1.05;
        font-weight: 600;
    }

    .nfx-revenue-card,
    .nfx-table-card {
        background: #fff;
        border: 1px solid #ececf1;
        border-radius: 10px;
        margin-bottom: 22px;
        box-shadow: 0 3px 14px rgba(26, 27, 35, .06);
        overflow: hidden;
    }

    .nfx-card-heading {
        padding: 17px 20px;
        border-bottom: 1px solid #eeeeF2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nfx-card-heading h4 {
        margin: 0;
        color: #2f3036;
        font-size: 16px;
        font-weight: 600;
    }

    .nfx-card-heading span {
        color: #92949c;
        font-size: 12px;
    }

    .nfx-revenue-body {
        padding: 20px;
    }

    .nfx-revenue-amounts {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 8px;
    }

    .nfx-revenue-pill {
        display: inline-flex;
        align-items: baseline;
        background: #f7f7f9;
        border: 1px solid #ededf1;
        border-radius: 8px;
        padding: 10px 14px;
        color: #25262b;
    }

    .nfx-revenue-pill .currency {
        font-size: 11px;
        font-weight: 700;
        color: #e53935;
        margin-right: 7px;
        letter-spacing: .5px;
    }

    .nfx-revenue-pill .amount {
        font-size: 20px;
        font-weight: 600;
    }

    .nfx-table-card .table-responsive {
        margin: 0;
        border: 0;
    }

    .nfx-table-card table {
        margin-bottom: 0;
    }

    .nfx-table-card thead th {
        background: #f7f7f9;
        color: #696c75;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .45px;
        font-weight: 600;
        border-bottom: 1px solid #e9e9ee !important;
        white-space: nowrap;
    }

    .nfx-table-card tbody td {
        vertical-align: middle !important;
        color: #42444b;
        border-top-color: #f0f0f3 !important;
        padding-top: 13px !important;
        padding-bottom: 13px !important;
    }

    .nfx-movie-title {
        font-weight: 600;
        color: #24252a;
    }

    .nfx-badge {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 20px;
        font-size: 10px;
        line-height: 1.2;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .nfx-badge-buy {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .nfx-badge-rent {
        background: #fff3e0;
        color: #ef6c00;
    }

    .nfx-badge-default {
        background: #f1f1f4;
        color: #666871;
    }

    .nfx-empty {
        padding: 28px !important;
        color: #9698a0 !important;
    }

    .nfx-profile-tabs {
        margin-bottom: 24px !important;
        border-bottom: 1px solid #ededf1;
        padding-bottom: 10px;
    }

    .nfx-profile-tabs > li > a {
        border-radius: 7px !important;
        padding: 9px 16px !important;
        font-weight: 500;
    }

    .nfx-profile-tabs > li > a .material-icons {
        font-size: 16px;
        vertical-align: -3px;
        margin-right: 4px;
    }

    .nfx-profile-tabs > li.active > a,
    .nfx-profile-tabs > li.active > a:hover,
    .nfx-profile-tabs > li.active > a:focus {
        box-shadow: 0 3px 10px rgba(230, 57, 53, .22) !important;
    }

    @media (max-width: 767px) {
        .nfx-stats-hero {
            padding: 18px;
        }

        .nfx-stat-card {
            min-height: 96px;
        }

        .nfx-card-heading {
            display: block;
        }

        .nfx-card-heading span {
            display: block;
            margin-top: 5px;
        }

        .nfx-profile-tabs > li > a {
            padding: 8px 10px !important;
            font-size: 12px;
        }
    }
</style>

<div class="nfx-stats-wrap">
    <div class="nfx-stats-hero">
        <h3>{{ $profileName }} — Performance</h3>
        <p>Views, customer conversions and revenue attributed directly to this profile.</p>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">movie</i></div>
                <div class="nfx-stat-copy"><small>Movies</small><strong>{{ number_format($stats['movies']) }}</strong></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">visibility</i></div>
                <div class="nfx-stat-copy"><small>Movie views</small><strong>{{ number_format($stats['movie_views']) }}</strong></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">shopping_cart</i></div>
                <div class="nfx-stat-copy"><small>Sales</small><strong>{{ number_format($stats['total']) }}</strong></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">people</i></div>
                <div class="nfx-stat-copy"><small>Customers</small><strong>{{ number_format($stats['customers']) }}</strong></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">local_mall</i></div>
                <div class="nfx-stat-copy"><small>Buys</small><strong>{{ number_format($stats['buys']) }}</strong></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="nfx-stat-card">
                <div class="nfx-stat-icon"><i class="material-icons">schedule</i></div>
                <div class="nfx-stat-copy"><small>Rents</small><strong>{{ number_format($stats['rents']) }}</strong></div>
            </div>
        </div>
    </div>

    <div class="nfx-revenue-card">
        <div class="nfx-card-heading">
            <h4>Attributed revenue</h4>
            <span>Only completed buys and rentals credited to this profile</span>
        </div>
        <div class="nfx-revenue-body">
            <div class="nfx-revenue-amounts">
                @forelse($stats['revenue_by_currency'] as $currency => $amount)
                    <div class="nfx-revenue-pill">
                        <span class="currency">{{ strtoupper($currency) }}</span>
                        <span class="amount">{{ number_format($amount, 2) }}</span>
                    </div>
                @empty
                    <div class="nfx-revenue-pill">
                        <span class="currency">Revenue</span>
                        <span class="amount">0.00</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="nfx-table-card">
        <div class="nfx-card-heading">
            <h4>Movie performance</h4>
            <span>Performance for every movie attached to {{ $profileName }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Views</th>
                        <th>Sales</th>
                        <th>Buys</th>
                        <th>Rents</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movieBreakdown as $movieStat)
                        <tr>
                            <td><span class="nfx-movie-title">{{ $movieStat['video']->title }}</span></td>
                            <td>{{ number_format($movieStat['views']) }}</td>
                            <td>{{ number_format($movieStat['conversions']) }}</td>
                            <td>{{ number_format($movieStat['buys']) }}</td>
                            <td>{{ number_format($movieStat['rents']) }}</td>
                            <td>
                                @forelse($movieStat['revenue_by_currency'] as $currency => $amount)
                                    <div><strong>{{ strtoupper($currency) }}</strong> {{ number_format($amount, 2) }}</div>
                                @empty
                                    <span class="text-muted">0.00</span>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center nfx-empty">No movies are assigned to this profile yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="nfx-table-card">
        <div class="nfx-card-heading">
            <h4>Recent attributed transactions</h4>
            <span>Latest buys and rentals originating from this profile</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Movie</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Channel</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attributedOrders as $order)
                        @php
                            $purchaseType = $order->purchase_type ?: optional($order->cart)->purchase_type;
                            $amount = ($order->total !== null && $order->total !== '') ? $order->total : optional($order->cart)->total;
                            $channel = $order->request_from ?: optional($order->cart)->request_from ?: 'web';
                            $normalizedType = strtolower((string) $purchaseType);
                        @endphp
                        <tr>
                            <td>{{ optional($order->created_at)->format('d M Y, H:i') }}</td>
                            <td>{{ optional($order->user)->email ?: '—' }}</td>
                            <td><span class="nfx-movie-title">{{ optional($order->video)->title ?: '—' }}</span></td>
                            <td>
                                <span class="nfx-badge {{ $normalizedType === 'buy' ? 'nfx-badge-buy' : ($normalizedType === 'rent' ? 'nfx-badge-rent' : 'nfx-badge-default') }}">
                                    {{ ucfirst($normalizedType ?: 'unknown') }}
                                </span>
                            </td>
                            <td><strong>{{ strtoupper($order->currency ?: 'NGN') }}</strong> {{ is_numeric($amount) ? number_format((float) $amount, 2) : '0.00' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $channel)) }}</td>
                            <td>{{ $order->invoice ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center nfx-empty">No attributed purchases or rentals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
