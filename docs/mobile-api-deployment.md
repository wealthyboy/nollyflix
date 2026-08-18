# NollyFlix mobile API deployment

## Required production environment

Configure these values on the server. Never commit their real values.

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nollyflix.tv

FLW_PUBLIC_KEY=your_flutterwave_public_key
FLW_SECRET_KEY=your_flutterwave_secret_key
FLW_SECRET_HASH=your_random_flutterwave_webhook_secret
FLW_REDIRECT_URL=nollyflix://payment-callback
```

Set the Flutterwave dashboard webhook URL to:

```text
https://nollyflix.tv/webhook/payment
```

The webhook secret configured in Flutterwave must exactly match
`FLW_SECRET_HASH`.

## Deploy commands

```bash
php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan route:clear
```

## Catalogue pricing

- Nigerian requests use `videos.buy_price` and `videos.rent_price` as NGN.
- Requests outside Nigeria use `videos.buy_price_usd` and
  `videos.rent_price_usd` as USD.
- There is no exchange-rate conversion. Populate both price pairs for every
  title that can be bought or rented.
- `CF-IPCountry` is preferred when the production proxy supplies it. The API
  falls back to a cached IP location lookup.

## Smoke checks

```bash
curl -H 'Accept: application/json' -H 'CF-IPCountry: NG' https://nollyflix.tv/api/browse
curl -H 'Accept: application/json' -H 'CF-IPCountry: US' https://nollyflix.tv/api/browse
```

The first response must show `iso_code: NGN`; the second must show
`iso_code: USD`. Public catalogue responses must not contain `link` or
`stream_url`. Authenticated playback is obtained from:

```text
GET /api/video/{video}/play
GET /api/video/{video}/play?episode_id={episode}
```
