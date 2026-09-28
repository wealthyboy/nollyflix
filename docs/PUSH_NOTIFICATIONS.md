# Push notifications

Nollyflix sends movie announcements through Firebase Cloud Messaging (FCM) to the Android and iOS apps.

## Firebase server credentials

Create or reuse the Firebase project configured by the mobile applications. Download a Firebase service-account JSON file and store it outside the public web root, for example:

`storage/app/firebase/firebase-service-account.json`

Do not commit the service-account file. Configure the server environment:

```env
FIREBASE_PROJECT_ID=nollyflix-620f0
FIREBASE_CREDENTIALS=storage/app/firebase/firebase-service-account.json
```

`FIREBASE_CREDENTIALS` may be an absolute path or a path relative to the Laravel project root.

## Apple configuration

In Firebase Console, open the iOS app and upload its APNs authentication key (recommended) or APNs certificate. The iOS application must be signed with the Push Notifications capability and the Remote notifications background mode.

Push notifications must be tested on a physical iPhone with a valid provisioning profile.

## Android configuration

Ensure the Android app's `google-services.json` belongs to the same Firebase project. Test on a physical device or an emulator image that includes Google Play services.

## Server deployment

After deploying the code, run:

```bash
php artisan migrate --force
php artisan config:clear
php artisan queue:restart
```

A Laravel queue worker must be running because admin campaigns are dispatched in the background. Confirm it with Supervisor or the process manager used by the server.

## Native app release

Rebuild and redistribute both native applications after adding Firebase Messaging. A JavaScript-only update is not sufficient because the change includes native Android and iOS configuration.

## Admin workflow

1. Open the admin video list.
2. Select one or more published videos.
3. Choose **Notify selected**.
4. Confirm the campaign.

The notification opens the selected movie when one video is announced. A campaign containing several videos opens the app home screen.
