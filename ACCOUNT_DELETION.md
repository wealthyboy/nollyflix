# NollyFlix account deletion requests

## Deploy (PHP 7.4)

```
/usr/bin/php7.4 artisan migrate --force
/usr/bin/php7.4 artisan optimize:clear
```

No seeder or new credentials are needed. Existing transactional email configuration must work.
Set the existing `APP_URL` to `https://nollyflix.tv`; signed confirmation links must use the public HTTPS host. Do not paste mail credentials into logs or Play Console.

## Entry points

- Public, no-login page: `https://nollyflix.tv/account-deletion`.
- Website: Account Settings → Delete account (also linked from Privacy Policy).
- Mobile: Account → Delete account → Send confirmation email. Requires an updated app build.
- Signed-in API: `POST /api/profile/account-deletion` (JWT). No email/user ID is accepted from the caller.
- Admin: Users → Deletion requests (`/admin/account-deletion-requests`), requires an admin permission record and edit permission.

Both entry points send a one-hour signed email link. Opening a link is not consent: a CSRF-protected POST with acknowledgement is required. Email/password changes invalidate old links. Repeat confirmations reuse the same request and reference. A new verified request also sends a notice to support@nollyflix.tv; the durable admin list still works if that notice fails. Public responses do not disclose whether an email is registered. Routes and per-email cooldowns limit email abuse. No passwords or purchase access are changed by requesting deletion.

## IMPORTANT: operator fulfilment required

This feature records **verified requests**, not automated account erasure. It does not send a false "account deleted" message or automatically hard-delete accounts. The request list intentionally has no button that merely marks deletion complete. Staff must monitor it and fulfil requests; deploying this alone does not establish compliance with Google's account deletion requirements.

Before public launch, agree and publish actual processing/retention periods (particularly financial/legal records); confirm the end-to-end mail flow in production and appoint a support owner. Current copy says support explains applicable retention; it does not invent a legal retention period. Do not declare Data safety complete until the public policy and fulfilment process are ready.

For each verified request:

1. Review the reference and account. Confirm administrator/content-owner dependencies before removal; never automatically remove an administrator or another account merely because an email was entered.
2. Review actual schema and storage, and remove/anonymize associated personal data: profile/image, watchlist, viewing/progress/activity, push device registrations/tokens, sessions/auth tokens, reviews/comments, support records and identifying request-monitor logs. Check third-party processors and backups against the approved retention policy. Removing only a user row or soft-deleting is insufficient.
3. Revoke website/mobile access, including existing JWTs (not just refresh tokens). Apply documented legal retention/anonymization to purchase/payment records without corrupting financial records or content-owner attribution.
4. Reply to the verified owner with the outcome, any legally retained data and its duration. Retain only the minimal audit evidence for the approved period. The request reference remains if its user row is removed; that does not certify associated-data cleanup.

Do not use the legacy bulk customer-delete action as a substitute for this review. No production account/data was deleted by this implementation.
