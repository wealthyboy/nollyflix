(function () {
    'use strict';

    var CONSENT_COOKIE = 'nollyflix_cookie_consent';
    var MAX_AGE = 60 * 60 * 24 * 180;
    var policyMeta = document.querySelector('meta[name="nollyflix-cookie-policy-url"]');
    var policyUrl = policyMeta ? policyMeta.getAttribute('content') : '/cookie-policy';

    function readCookie(name) {
        var prefix = name + '=';
        var cookies = document.cookie ? document.cookie.split(';') : [];
        for (var i = 0; i < cookies.length; i++) {
            var item = cookies[i].trim();
            if (item.indexOf(prefix) === 0) {
                return decodeURIComponent(item.substring(prefix.length));
            }
        }
        return null;
    }

    function writeCookie(value) {
        var secure = window.location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = CONSENT_COOKIE + '=' + encodeURIComponent(value)
            + '; Max-Age=' + MAX_AGE
            + '; Path=/; SameSite=Lax' + secure;
    }

    function parseConsent(value) {
        var defaults = {
            necessary: true,
            functional: false,
            analytics: false,
            advertising: false
        };

        if (!value) {
            return defaults;
        }

        if (value === 'all') {
            return {
                necessary: true,
                functional: true,
                analytics: true,
                advertising: true
            };
        }

        if (value === 'essential') {
            return defaults;
        }

        // Keep support for preferences saved by the previous consent panel.
        if (value.indexOf('custom:') === 0) {
            var selected = value.substring(7).split(',');
            defaults.functional = selected.indexOf('functional') !== -1;
            defaults.analytics = selected.indexOf('analytics') !== -1;
            defaults.advertising = selected.indexOf('advertising') !== -1;
        }

        return defaults;
    }

    function serializeConsent(consent) {
        if (consent.functional && consent.analytics && consent.advertising) {
            return 'all';
        }
        if (!consent.functional && !consent.analytics && !consent.advertising) {
            return 'essential';
        }

        var selected = [];
        if (consent.functional) selected.push('functional');
        if (consent.analytics) selected.push('analytics');
        if (consent.advertising) selected.push('advertising');
        return 'custom:' + selected.join(',');
    }

    function emit(consent) {
        window.NollyflixCookieConsent.current = consent;
        try {
            document.dispatchEvent(new CustomEvent('nollyflix:cookie-consent', {
                detail: consent
            }));
        } catch (e) {
            // Older browsers can still use window.NollyflixCookieConsent.current.
        }
    }

    function makeMarkup() {
        var root = document.createElement('div');
        root.id = 'nollyflix-cookie-consent';
        root.setAttribute('role', 'dialog');
        root.setAttribute('aria-live', 'polite');
        root.setAttribute('aria-labelledby', 'nollyflix-cookie-title');
        root.innerHTML = ''
            + '<div class="nollyflix-cookie-shell">'
            + '  <div class="nollyflix-cookie-row">'
            + '    <div class="nollyflix-cookie-copy">'
            + '      <div class="nollyflix-cookie-kicker">Cookies &amp; privacy</div>'
            + '      <h2 id="nollyflix-cookie-title" class="nollyflix-cookie-title">Your privacy matters</h2>'
            + '      <p class="nollyflix-cookie-text">Nollyflix uses essential cookies to keep the service secure and working. Please review how we use cookies and your choices in our <a href="' + policyUrl + '">Cookie Policy</a>. Optional cookies are used only with your permission, including where EU/EEA or UK law requires consent.</p>'
            + '    </div>'
            + '    <div class="nollyflix-cookie-actions">'
            + '      <a class="nollyflix-cookie-btn nollyflix-cookie-btn-policy" href="' + policyUrl + '">Read Cookie Policy</a>'
            + '      <button type="button" class="nollyflix-cookie-btn" data-cookie-action="reject">Essential only</button>'
            + '      <button type="button" class="nollyflix-cookie-btn" data-cookie-action="manage">Manage choices</button>'
            + '      <button type="button" class="nollyflix-cookie-btn nollyflix-cookie-btn-primary" data-cookie-action="accept">Accept all</button>'
            + '    </div>'
            + '  </div>'
            + '  <div id="nollyflix-cookie-preferences">'
            + '    <div class="nollyflix-cookie-option">'
            + '      <div><strong>Strictly necessary</strong><span>Required for sign-in, security, checkout, purchases/rentals, playback and remembering your cookie choice.</span></div>'
            + '      <input class="nollyflix-cookie-toggle" type="checkbox" checked disabled aria-label="Strictly necessary cookies always enabled">'
            + '    </div>'
            + '    <div class="nollyflix-cookie-option">'
            + '      <div><strong>Functional</strong><span>Helps remember optional preferences and improve convenience.</span></div>'
            + '      <input id="nollyflix-consent-functional" class="nollyflix-cookie-toggle" type="checkbox">'
            + '    </div>'
            + '    <div class="nollyflix-cookie-option">'
            + '      <div><strong>Analytics</strong><span>Helps us understand usage and improve performance where analytics tools are enabled.</span></div>'
            + '      <input id="nollyflix-consent-analytics" class="nollyflix-cookie-toggle" type="checkbox">'
            + '    </div>'
            + '    <div class="nollyflix-cookie-option">'
            + '      <div><strong>Advertising</strong><span>Allows optional advertising or campaign measurement technologies where used.</span></div>'
            + '      <input id="nollyflix-consent-advertising" class="nollyflix-cookie-toggle" type="checkbox">'
            + '    </div>'
            + '    <div class="nollyflix-cookie-preference-actions">'
            + '      <a class="nollyflix-cookie-link" href="' + policyUrl + '">Read full Cookie Policy</a>'
            + '      <button type="button" class="nollyflix-cookie-btn nollyflix-cookie-btn-primary" data-cookie-action="save">Save choices</button>'
            + '    </div>'
            + '  </div>'
            + '</div>';

        document.body.appendChild(root);

        var settings = document.createElement('button');
        settings.type = 'button';
        settings.id = 'nollyflix-cookie-settings-button';
        settings.textContent = 'Cookie settings';
        settings.setAttribute('aria-label', 'Open cookie settings');

        var footers = document.querySelectorAll('footer#footer-pro');
        var footer = footers.length ? footers[footers.length - 1] : null;
        if (footer) {
            var settingsWrap = document.createElement('div');
            settingsWrap.className = 'nollyflix-cookie-footer-settings';
            settingsWrap.appendChild(settings);
            footer.appendChild(settingsWrap);
        } else {
            settings.className = 'nollyflix-cookie-settings-fallback';
            document.body.appendChild(settings);
        }

        return { root: root, settings: settings };
    }

    function init() {
        var ui = makeMarkup();
        var root = ui.root;
        var settingsButton = ui.settings;
        var preferences = root.querySelector('#nollyflix-cookie-preferences');
        var functional = root.querySelector('#nollyflix-consent-functional');
        var analytics = root.querySelector('#nollyflix-consent-analytics');
        var advertising = root.querySelector('#nollyflix-consent-advertising');
        var stored = readCookie(CONSENT_COOKIE);
        var current = parseConsent(stored);

        function syncControls(consent) {
            functional.checked = !!consent.functional;
            analytics.checked = !!consent.analytics;
            advertising.checked = !!consent.advertising;
        }

        function open(showPreferences) {
            syncControls(parseConsent(readCookie(CONSENT_COOKIE)));
            if (showPreferences) {
                preferences.classList.add('nollyflix-cookie-preferences-open');
            } else {
                preferences.classList.remove('nollyflix-cookie-preferences-open');
            }
            requestAnimationFrame(function () {
                root.classList.add('nollyflix-cookie-show');
            });
        }

        function close() {
            root.classList.remove('nollyflix-cookie-show');
            preferences.classList.remove('nollyflix-cookie-preferences-open');
        }

        function save(consent) {
            writeCookie(serializeConsent(consent));
            current = consent;
            emit(consent);
            settingsButton.classList.add('nollyflix-cookie-settings-visible');
            close();
        }

        root.addEventListener('click', function (event) {
            var button = event.target.closest('[data-cookie-action]');
            if (!button) return;

            var action = button.getAttribute('data-cookie-action');
            if (action === 'accept') {
                save({ necessary: true, functional: true, analytics: true, advertising: true });
            } else if (action === 'reject') {
                save({ necessary: true, functional: false, analytics: false, advertising: false });
            } else if (action === 'manage') {
                syncControls(parseConsent(readCookie(CONSENT_COOKIE)));
                preferences.classList.toggle('nollyflix-cookie-preferences-open');
            } else if (action === 'save') {
                save({
                    necessary: true,
                    functional: functional.checked,
                    analytics: analytics.checked,
                    advertising: advertising.checked
                });
            }
        });

        settingsButton.addEventListener('click', function () {
            open(true);
        });

        window.NollyflixCookieConsent = {
            current: current,
            open: function () {
                open(true);
            },
            has: function (category) {
                var consent = window.NollyflixCookieConsent.current || parseConsent(readCookie(CONSENT_COOKIE));
                return category === 'necessary' ? true : !!consent[category];
            }
        };

        emit(current);

        if (!stored) {
            syncControls(current);
            requestAnimationFrame(function () {
                setTimeout(function () {
                    open(false);
                }, 180);
            });
        } else {
            settingsButton.classList.add('nollyflix-cookie-settings-visible');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
