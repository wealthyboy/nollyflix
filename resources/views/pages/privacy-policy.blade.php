@extends('layouts.access')

@section('content')
<section class="section-content">
    <div class="container" style="max-width: 980px; padding-top: 48px; padding-bottom: 70px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 style="margin-bottom: 8px;">Privacy Policy</h1>
                <p class="text-muted" style="margin-bottom: 32px;">Last updated: September 2026</p>

                <p>
                    This Privacy Policy explains how Nollyflix collects, uses, stores and shares personal information
                    when you use the Nollyflix website, mobile application and streaming services. It also explains the
                    choices available to you. This policy describes the Nollyflix service as it currently operates and
                    does not refer to account tools or support pages that are not available on the platform.
                </p>

                <h3>1. Information you provide to Nollyflix</h3>
                <p>Depending on how you use the service, you may provide information such as:</p>
                <ul>
                    <li>your first name, last name and email address when you create a web account;</li>
                    <li>your phone number when it is requested by the mobile application;</li>
                    <li>your password and other information needed to secure your account;</li>
                    <li>profile information that you choose to add or update;</li>
                    <li>information connected with movies you buy, rent, add to your watchlist or access through your account; and</li>
                    <li>information you submit when communicating with Nollyflix.</li>
                </ul>
                <p>
                    Payment card, bank or other payment credentials may be collected directly by the payment provider
                    used during checkout. Nollyflix receives the transaction information needed to verify the payment
                    and grant the correct movie access, such as a payment reference, transaction identifier, amount,
                    currency and payment status. Nollyflix does not need to store your full payment-card details in
                    order to verify a Flutterwave transaction.
                </p>

                <h3>2. Information collected when you use the service</h3>
                <p>Nollyflix may collect or create service information needed to operate the platform, including:</p>
                <ul>
                    <li>your IP address and an approximate country or region derived from your connection;</li>
                    <li>browser, device, application and basic request or diagnostic information;</li>
                    <li>session and authentication information;</li>
                    <li>cookie choices and other information stored in your browser where applicable;</li>
                    <li>purchase, rental, watchlist and playback-access records associated with your account; and</li>
                    <li>technical logs used to secure, troubleshoot and maintain the website, mobile application and APIs.</li>
                </ul>
                <p>
                    Nollyflix uses location derived from your connection to support features such as regional title
                    availability and currency selection. Nollyflix does not need your precise GPS location for those
                    website functions.
                </p>

                <h3>3. How Nollyflix uses information</h3>
                <p>We use information where necessary to operate and improve Nollyflix, including to:</p>
                <ul>
                    <li>create and authenticate accounts;</li>
                    <li>display and update account and profile information;</li>
                    <li>maintain watchlists and access to purchased or rented titles;</li>
                    <li>process and verify payments and send transaction-related communications;</li>
                    <li>control access to content based on purchases, rentals and regional availability;</li>
                    <li>provide the appropriate currency and service experience for a visitor's region;</li>
                    <li>protect the service against fraud, misuse, security threats and unauthorized access;</li>
                    <li>diagnose errors and improve the reliability and usability of the platform; and</li>
                    <li>comply with applicable legal or regulatory obligations.</li>
                </ul>

                <h3>4. Payments and service providers</h3>
                <p>
                    Nollyflix relies on third-party service providers where needed to operate the service. For example,
                    Flutterwave is used for payment processing and verification. Nollyflix may also use service
                    providers for transactional email delivery, hosting, infrastructure, security and video delivery.
                    These providers may process information needed to perform the service they provide to Nollyflix and
                    may have their own privacy terms for information they collect directly from you.
                </p> 22197849459
                <p>
                    Nollyflix may also disclose information when required by law, to respond to lawful requests, to
                    enforce applicable terms, to investigate fraud or security incidents, or to protect the rights,
                    property and safety of Nollyflix, its users or others.
                </p>

                <h3>5. Cookies and similar technologies</h3>
                <p>
                    Nollyflix uses essential cookies and browser storage for functions such as sessions,
                    authentication, shopping-cart activity, security and remembering your cookie choices. Optional
                    cookies, where used, are controlled through the Nollyflix cookie-consent tool where consent is
                    required.
                </p>
                <p>
                    For more detail about cookie categories, retention and your choices, please read the
                    <a href="{{ route('cookie.policy') }}">Cookie Policy</a>.
                </p>
                @if(!empty($cookie_consent_required))
                    <p>
                        <button type="button" class="btn btn-primary" onclick="window.NollyflixCookieConsent && window.NollyflixCookieConsent.open();">
                            Cookie Settings
                        </button>
                    </p>
                @endif

                <h3>6. Your account and choices</h3>
                <p>
                    When you are signed in, the current Nollyflix website allows you to update your first name, last
                    name and password through My Profile. You can also manage your watchlist through the service. If
                    you no longer want optional cookies, you can change your cookie preferences through Cookie Settings.
                </p>
                <h3>7. Your privacy rights</h3>
                <p>
                    Depending on the law that applies to you, you may have rights to request access to personal
                    information, correction of inaccurate information, deletion, restriction or objection to certain
                    processing, portability of information, or withdrawal of consent where processing is based on
                    consent. Nollyflix may need to verify your identity before acting on a privacy request and may keep
                    information where retention is required or permitted by law, including for transaction, fraud
                    prevention, security and record-keeping purposes.
                </p>
                <p>
                    The current website does not publish a dedicated Data Protection Officer page or a separate
                    Privacy Office email address. Privacy requests should therefore be made through the official
                    customer-support contact Nollyflix provides to you. If a dedicated privacy contact is introduced,
                    this page will be updated to show it.
                </p>

                <h3>8. Data retention</h3>
                <p>
                    Nollyflix keeps personal information for as long as reasonably necessary for the purposes described
                    in this policy, including maintaining your account and viewing entitlements, completing and
                    recording transactions, meeting legal obligations, resolving disputes, preventing fraud and
                    maintaining security. Retention periods may differ depending on the type of information and the
                    reason it is held.
                </p>

                <h3>9. Security</h3>
                <p>
                    Nollyflix uses reasonable technical and organizational measures intended to protect personal
                    information against unauthorized access, loss, misuse or alteration. No online system can be
                    guaranteed to be completely secure, so you should also protect your password and sign out when using
                    a shared or public device.
                </p>

                <h3>10. Children</h3>
                <p>
                    Nollyflix is intended for users who are legally able to create an account and enter into purchases
                    or rentals in their jurisdiction. Where a child is permitted to use the service, a parent or legal
                    guardian should supervise that use and any transaction. Nollyflix may apply additional age
                    requirements where required by law or by a particular service feature.
                </p>

                <h3>11. Third-party websites and services</h3>
                <p>
                    The Nollyflix service may open or use third-party services, for example a payment page, trailer,
                    social platform or other external service. Those third parties operate under their own privacy
                    practices. Nollyflix's Privacy Policy does not control information that a third party collects
                    directly from you on its own service.
                </p>

                <h3>12. Changes to this Privacy Policy</h3>
                <p>
                    Nollyflix may update this Privacy Policy when the service, technology, providers or legal
                    requirements change. When a material change is made, the date at the top of this page will be
                    updated and additional notice will be provided where required.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
