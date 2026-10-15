# Manual customer movie access

## Admin workflow

1. Open **Admin → Customers**, find the customer's existing account and choose **Grant movie access**.
2. Search/select the movie, choose **Rent** or **Buy**, and record the reason. A payment/support reference is optional and is kept internally.
3. Confirm that the payment has been checked or that complimentary access is authorized, then select **Grant access & send email**.
4. The customer opens the email, signs in using the recipient account and is redirected to the existing movie player. No new payment is required. Forwarded links do not grant another account access.

Rent access lasts **48 hours from granting**, not from opening the email or first playback. Buy access has no rental expiry. An active existing purchase is never downgraded to a rental. Existing matching current orders are reused; an expired rental needs a new explicit grant.

## Orders and accounting

This is an administrative access correction/complimentary grant, **not payment verification or a new charge**. It does not call the payment gateway, modify payment transactions, mark an unverified payment successful or send a payment receipt.

When a new order is needed, it is created with its normal linked cart, customer, movie, purchase type and rental expiry. Its total is zero, invoice starts with `ACCESS-`, and `payment_type`/`request_from` are `admin_access_grant`. This preserves the standard order/watchlist/player relationships without counting an earlier payment twice. Reconciling any actual payment remains a separate accounting task.

`movie_access_grants` records the administrator, customer, movie, order, requested access type, reason, reference, idempotency token and email-delivery state. If an existing purchase covers a Rent request, the email describes the actual Buy access on the reused order.

## Safe retries

The order/cart/audit record commit together before email delivery. If email fails, access remains available in the customer's watchlist. Use **Resend email** in the access history; it never creates another order or extends a rental. Duplicate submissions of the same form reuse the same grant. Separate requests for matching current access reuse the existing order. The history shows the latest 20 manual grants.

An email link checks account ownership and the granted order's status/expiry before redirecting. Existing checkout, shared playback authorization and payment endpoints are unchanged. Normal content availability and regional restrictions still apply in the existing player.

## Deployment

Deploy the new files and run the additive migration using this project's **PHP 7.4 runtime** before opening the grant form:

```sh
php artisan migrate --force
```

If deployment normally caches routes/config/views, refresh those caches through the existing deployment process. Ensure `APP_URL` is the public HTTPS Nollyflix domain, not a local/test hostname, and that the configured Laravel mail transport is working. Access emails send immediately, so no queue worker is needed for this feature.

The migration creates only `movie_access_grants`; it does not change or delete existing orders, carts or payments. Do not roll it back after issuing grants unless you have backed up and intentionally accepted losing the audit/email-link records; rolling it back does not revoke the associated orders.

## Verification

```sh
DB_CONNECTION=sqlite DB_DATABASE=:memory: APP_ENV=testing php vendor/bin/phpunit --do-not-cache-result --filter CustomerMovieAccessTest
```

Tests use an isolated in-memory database and fake mail. The login/player-render test isolates the unrelated next-movie recommendation query because Laravel 7's SQLite grammar cannot execute the production JSON region filter; authentication, grant/order checks and watch-page rendering are exercised normally. No real gateway requests, charges, customer orders or emails are made by these tests.
