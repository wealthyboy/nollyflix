# Temporary mobile playback geofence bypass

For the requested testing window only, deploy this backend change and set:

```dotenv
MOBILE_PLAYBACK_IGNORE_GEOFENCE=true
```

Then refresh the server's configuration:

```sh
php artisan config:clear
```

If deployment normally caches configuration, rebuild that cache afterwards with
`php artisan config:cache`. No migration or new APK is required when the installed
app already sends `X-App-Platform: android` or `ios`, as the current native client does.
An older app that does not send the header will still be blocked.

Only the mobile playback API (`GET /api/video/{id}/play`, including episodes) can
use this switch. A title still must be active and have a playable source. Paid
titles still need an existing purchase or unexpired rental. Website playback,
checkout geofencing and the stored blocked-continent rules are unchanged.
The catalogue's regional flags also continue reporting the original restriction.

Successful bypassed playback returns `geofence_bypassed_for_testing: true` and
writes a `mobile_playback_geofence_testing_bypass` notice with title ID and platform
only. It does not log stream URLs, tokens, guest data or payment information.

The platform header is a client declaration, not device attestation or an access
control boundary: another API client could imitate it. This switch deliberately
relaxes regional enforcement for mobile-marked API playback during testing. It
does not provide free access to paid content or bypass external CDN restrictions.
Do not leave it enabled after testing.

To restore normal mobile restrictions immediately:

```dotenv
MOBILE_PLAYBACK_IGNORE_GEOFENCE=false
```

Refresh configuration again. A new playback request is required; a previously
issued stream URL is not revoked by this switch. Once testing is finished, remove
the temporary switch/code as requested, after checking the removal tests.
