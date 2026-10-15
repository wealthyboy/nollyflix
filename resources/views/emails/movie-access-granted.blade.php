<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Your movie is ready</title></head>
<body style="margin:0;background:#f4f5f7;font-family:Arial,sans-serif;color:#202431;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center" style="padding:32px 16px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:580px;background:#ffffff;border-radius:16px;">
            <tr><td style="padding:32px;">
                <p style="font-size:14px;font-weight:bold;letter-spacing:2px;color:#c71f27;">NOLLYFLIX</p>
                <h1 style="font-size:26px;line-height:1.3;">Your movie is ready to watch.</h1>
                <p>Hello {{ $grant->customer->fullname() }},</p>
                <p>Our team has made <strong>{{ $grant->video->title }}</strong> available in your Nollyflix account.</p>
                @if(strtolower((string) (optional($grant->order->cart)->purchase_type ?: $grant->order->purchase_type)) === 'rent')
                    <p><strong>Rental access</strong><br>Available until {{ optional($grant->order->video_rent_expires)->format('j M Y, H:i') }} {{ config('app.timezone') }}. The rental period has already started.</p>
                @else
                    <p><strong>Buy access</strong><br>This movie has been added to your library with no rental expiry.</p>
                @endif
                <p>Use <strong>{{ $grant->customer->email }}</strong> to log in. If you are already signed in with this account, the button will take you straight to the movie.</p>
                <p style="margin:28px 0;"><a href="{{ route('movie-access.show', ['grant' => $grant->id]) }}" style="display:inline-block;padding:16px 24px;background:#c71f27;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;">Log in &amp; watch</a></p>
                <p style="font-size:13px;line-height:1.7;color:#626977;">No additional payment is required. This is an access confirmation, not a payment receipt.<br>Order: {{ $grant->order->invoice }}</p>
                <p style="font-size:13px;line-height:1.7;">Button not working? Open this link:<br><a href="{{ route('movie-access.show', ['grant' => $grant->id]) }}">{{ route('movie-access.show', ['grant' => $grant->id]) }}</a></p>
            </td></tr>
        </table>
    </td></tr></table>
</body>
</html>
