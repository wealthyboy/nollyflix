# Content advisories

After deploying the code, run from the NollyFlix website directory:

```sh
composer dump-autoload
php artisan migrate --force
php artisan db:seed --class=ContentAdvisorySeeder --force
php artisan optimize:clear
```

The seeder adds Violence, Sexual content, Nudity, Strong language, Alcohol,
Smoking, Drug use, and Disturbing scenes. It is safe to re-run: it does not
overwrite edited labels, modify age ratings, or assign advisories to any title.
Do not use `migrate:fresh` or a database reset on the live server.

## Use

- Open **Videos → Content Advisories** to add labels, edit names, and set their display order.
- In **Videos → Add/Edit**, tick the relevant labels below the Film Rating field.
- Save the movie or series. Unchecking every label removes the advisory display for that title.
- Labels appear under **Content advisories** below the metadata on the public title details page.
- Unclassified titles show no advisory section; an empty selection does not certify child suitability.
- Age ratings remain separate and unchanged. Review the actual film or distributor guidance before tagging.
- The detail API (`GET /api/video/{id}`) includes the ordered `content_advisories` array
  (`id`, `name`, `slug`). This does not update the installed mobile app's UI or player overlays.
- Existing update clients that omit advisory fields retain the saved selection. Current forms
  send `content_advisories_present=1` so unchecking all boxes explicitly clears it.

New tables: `content_advisories` and `content_advisory_video`. Apply migrations before
serving the new code. No production database or deployed website is changed by local tests.

## Tests

```sh
php vendor/bin/phpunit --filter ContentAdvisoryTest
```

Tests use an isolated SQLite in-memory database.

Verified locally: 12 advisory tests, including full admin/public Blade rendering,
API output, permissions, invalid IDs, restoring and clearing checkboxes, and
repeatable seeding. The existing broader suite also reports three currency-test
constructor errors and a homepage test lacking a site-settings fixture; those
unrelated test files were not changed for this feature. Local browser previews
used only synthetic records in an in-memory database.
