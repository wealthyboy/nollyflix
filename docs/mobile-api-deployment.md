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

- All mobile API requests currently use `videos.buy_price` and
  `videos.rent_price` as NGN, regardless of country.
- There is no exchange-rate conversion.
- The USD columns remain available for restoring regional pricing later.

## Smoke checks

```bash
curl -H 'Accept: application/json' -H 'CF-IPCountry: NG' https://nollyflix.tv/api/browse
curl -H 'Accept: application/json' -H 'CF-IPCountry: US' https://nollyflix.tv/api/browse
```

Both responses must show `iso_code: NGN`. Public catalogue responses must not contain `link` or
`stream_url`. Authenticated playback is obtained from:

```text
GET /api/video/{video}/play
GET /api/video/{video}/play?episode_id={episode}
```

Playback responses now include subtitle metadata when a movie or episode has an uploaded subtitle file:

```json
{
  "subtitle_url": "https://nollyflix.tv/videos/subtitles/episodes/example.vtt",
  "subtitles": [
    {
      "label": "English",
      "language": "en",
      "format": "webvtt",
      "url": "https://nollyflix.tv/videos/subtitles/episodes/example.vtt"
    }
  ]
}
```

`GET /api/video/{video}` also exposes `subtitle_url` / `has_subtitles` for the title and for every episode. Episode subtitles override the title-level subtitle; the title-level file remains a fallback for older series records.
