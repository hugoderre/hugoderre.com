import { Controller } from 'stimulus';
import Cookies from 'js-cookie';

export default class extends Controller {
	connect() {
		this.buttonElement = this.element.querySelector( '#dark-mode-switch' );
		this.isDarkMode = this.getInitialDarkModeState();
		this.buttonElement.checked = this.isDarkMode;
		this.toggleDarkMode();
	}

	getInitialDarkModeState() {
		const darkModePreferenceCookie = Cookies.get( 'darkMode' );

		if ( !darkModePreferenceCookie ) {
			return this.getBrowserPerfersColorScheme();
		}

		return darkModePreferenceCookie === 'dark';
	}

	getBrowserPerfersColorScheme() {
		if ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ) {
			return true;
		}
		return false;
	}

	darkModeInputEvent() {
		this.isDarkMode = this.buttonElement.checked;
		Cookies.set( 'darkMode', this.isDarkMode ? 'dark' : 'light', { expires: 60 } );
		this.toggleDarkMode();
	}

	toggleDarkMode() {
		const body = document.body
		if ( this.isDarkMode ) {
			body.dataset.colorScheme = "dark"
		} else {
			body.dataset.colorScheme = "light"
		}
	}
}
