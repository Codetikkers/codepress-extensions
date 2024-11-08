/**
 * Stores the user's cookie consent in a cookie.
 * Shows a cookie banner if the user has not yet accepted cookies.
 */
class CookieBanner extends HTMLElement {

    constructor() {
        super();
        const cookieValue = this.getCookieValue();
        if (cookieValue === '1') {
            this.dispatchCookieConsent();
            this.remove();
            return;
        }
        this.renderCookie();
    }

    /**
     * Elements can listen to the cookies-accepted event to know when the user has accepted cookies.
     * This can be used to load third-party scripts that require user consent, for example embedded videos.
     */
    dispatchCookieConsent() {
        const event = new Event('cookies-accepted', {bubbles: true, cancelable: true});
        document.dispatchEvent(event);
    }

    getCookieValue() {
        return document.cookie.replace(/(?:^|.*;\s*)cookieAccepted\s*\=\s*([^;]*).*$|^.*$/, '$1');
    }
    setAcceptCookie() {
        document.cookie = 'cookieAccepted=1;max-age=31536000;secure;samesite=strict;path=/'; // one year
    }

    setDeclineCookie() {
        document.cookie = 'cookieAccepted=0;max-age=86400;secure;samesite=strict;path=/'; // one month
    }

    renderCookie() {
        this.classList.add('is-visible');
        this.querySelector("[accept]").addEventListener('click', () => {
            this.setAcceptCookie();
            this.dispatchCookieConsent();
            this.remove()
        });

        this.querySelector("[decline]").addEventListener('click', () => {
            this.setDeclineCookie();
            this.remove()
        });
    }
}

customElements.define('cookie-banner', CookieBanner);