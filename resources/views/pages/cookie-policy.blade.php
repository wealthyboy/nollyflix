@extends('layouts.access')

@section('content')
<section class="section-content">
    <div class="container" style="max-width: 980px; padding-top: 48px; padding-bottom: 70px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 style="margin-bottom: 8px;">Cookie Policy</h1>
                <p class="text-muted" style="margin-bottom: 32px;">Last updated: September 2026</p>

                <p>
                    This Cookie Policy explains how Nollyflix uses cookies and similar technologies when you visit
                    our website or use our web-based streaming services. It also explains the choices available to
                    you and how you can change those choices.
                </p>

                <h3>1. What are cookies?</h3>
                <p>
                    Cookies are small text files stored on your browser or device when you visit a website. They can
                    help a service remember that you are signed in, keep a transaction secure, remember preferences,
                    and understand how the service is being used.
                </p>

                <h3>2. How Nollyflix uses cookies</h3>
                <p>Nollyflix may use cookies and similar technologies for the following purposes:</p>

                <h4>Strictly necessary cookies</h4>
                <p>
                    These are required for the Nollyflix service to work. They may be used for authentication,
                    account security, fraud prevention, checkout, purchases and rentals, playback access, shopping
                    cart functions, session management, and remembering your cookie choices. Because these cookies
                    are necessary to provide the service, they cannot be switched off through our cookie tool.
                </p>

                <h4>Functional cookies</h4>
                <p>
                    These cookies help remember choices and improve convenience, such as selected preferences or
                    other settings that make your experience more consistent. Where consent is required by law,
                    functional cookies that are not strictly necessary are used only after you allow them.
                </p>

                <h4>Analytics cookies</h4>
                <p>
                    Where enabled, analytics cookies help us understand how visitors use Nollyflix, such as which
                    pages are visited, how the service performs, and where users experience errors. We use this
                    information to improve the platform. Where consent is required, these cookies are not enabled
                    until you choose to allow them.
                </p>

                <h4>Advertising and marketing cookies</h4>
                <p>
                    Advertising or marketing cookies, where used, may help measure campaigns or make advertising more
                    relevant. They are optional. For visitors who are shown our consent tool, these cookies are not
                    enabled unless the visitor chooses to allow them.
                </p>

                <h3>3. Your cookie choices</h3>
                <p>
                    Visitors outside Nigeria are shown a cookie consent panel when Nollyflix identifies the request as
                    non-Nigerian. You can accept all optional cookies, reject non-essential cookies, or choose your
                    preferences by category. If we cannot reliably determine your location, we show the consent panel
                    as a precaution.
                </p>
                <p>
                    Your consent preference is stored for up to 180 days so we can remember your choice. You can
                    change or withdraw your optional-cookie consent at any time by opening Cookie Settings.
                </p>

                @if(!empty($cookie_consent_required))
                    <p>
                        <button type="button" class="btn btn-primary" onclick="window.NollyflixCookieConsent && window.NollyflixCookieConsent.open();">
                            Cookie Settings
                        </button>
                    </p>
                @endif

                <h3>4. Browser controls</h3>
                <p>
                    Most browsers allow you to block, delete, or receive warnings about cookies through their privacy
                    settings. Blocking strictly necessary cookies may prevent sign-in, checkout, rentals, purchases,
                    playback, or other parts of Nollyflix from working correctly. If you clear your browser cookies
                    or change browsers or devices, you may need to set your preferences again.
                </p>

                <h3>5. How long cookies last</h3>
                <p>
                    Some cookies last only for the current browser session and are removed when the session ends.
                    Others remain for a defined period so they can remember a setting or consent choice. The Nollyflix
                    cookie-consent preference is stored for up to 180 days. The duration of third-party cookies, where
                    used and permitted, is determined by the relevant provider and may vary.
                </p>

                <h3>6. Third-party services</h3>
                <p>
                    Some Nollyflix features may rely on third-party service providers, such as payment, security,
                    hosting, video delivery, or analytics providers. These providers may process information needed to
                    deliver their services. Optional third-party cookies are subject to your consent where applicable.
                    Payment providers and other external services may also have their own privacy and cookie policies.
                </p>

                <h3>7. Changes to this policy</h3>
                <p>
                    We may update this Cookie Policy when our technology, services, providers, or legal requirements
                    change. When we make a material update, we will revise the date shown at the top of this page and,
                    where appropriate, ask you to make a new consent choice.
                </p>

                <h3>8. Contact us</h3>
                <p>
                    If you have questions about this Cookie Policy or Nollyflix's privacy practices, please contact
                    Nollyflix Support through the contact details provided on the platform.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
