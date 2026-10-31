# HTTPS-only location lookups

The currency resolver still uses `CF-IPCountry` first. If that header is
unavailable, Location now uses `App\Location\Drivers\HttpsIpInfo` instead of
the package's HTTP-only IpApi/IpInfo/GeoPlugin drivers.

- Online requests go only to `https://ipinfo.io/<IP>/json` with certificate
  verification enabled, a two-second timeout, and redirects disabled.
- Private, reserved and malformed IP addresses are not sent to IPinfo.
- An optional `IPINFO_TOKEN` is sent in the Authorization header, not the URL.
  The existing legacy JSON endpoint supports limited anonymous requests; see
  [IPinfo's limits and API differences](https://support.ipinfo.io/hc/en-us/articles/34121895556242-Legacy-Free-API-vs-IPinfo-Lite).
  A Lite-only token/endpoint is not a drop-in replacement for this JSON API.
- Provider errors, rate limits, invalid responses and TLS errors never trigger
  HTTP retries. The only fallback is the existing local MaxMind database at
  `database/maxmind/GeoLite2-City.mmdb`, if installed. MaxMind web mode stays off.
  If neither succeeds, the resolver keeps its existing NG/NGN default.
- No payment logic, geofence rules, currencies or movie prices are changed.

## Deployment

Deploy all changed/new files together, then run from the live NollyFlix project:

```sh
/usr/bin/php7.4 artisan config:clear
/usr/bin/php7.4 artisan queue:restart
```

If deployment normally caches config, run its usual `config:cache` step after
the clear. No migration, Composer upgrade, paid subscription or Android rebuild
is required for this backend-only change. An existing compatible IPinfo token
can optionally be set as `IPINFO_TOKEN` in the server environment. Never commit
a token. Do not disable certificate verification to work around CA problems.

Verify active config without printing credentials:

```sh
/usr/bin/php7.4 artisan tinker --execute="dump(config('location.driver'), config('location.fallbacks'), config('location.maxmind.web.enabled'));"
```

Expected: the custom HTTPS driver, only the MaxMind fallback, and `false` for
MaxMind web mode. Test browsing/pricing for NG and non-NG visitors live. Tests
use mocked HTTP responses: they do not prove the live server's certificates,
provider quota, token compatibility or deployment state.

## Google Play declaration

Do not claim the whole app is encrypted solely because this backend path is
fixed. Google's question concerns data flow from the user's device to a server,
including SDKs and controlled WebViews. Audit the actual release build before
updating that declaration. This change does not edit or submit Play Console.

## Local verification (31 October 2026)

- PHP 7.4 syntax checks and `git diff --check` passed.
- 85 location, playback, geofence, account-deletion, advisory, notification and
  device-metadata tests passed (419 assertions), with mocked provider calls.
- The full suite also reported three errors in `ApiCurrencyContractTest`
  (constructing `ApiCurrencyByIp` without its required resolver) and one failure
  in the generic homepage `ExampleTest` (missing `lives` table in its test DB).
  Those existing test files and the corresponding application code were not
  modified as part of this security fix.
