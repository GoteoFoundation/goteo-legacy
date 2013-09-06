<?php

namespace Goteo\Library\i18n {

	use Goteo\Core\Registry;
	
	require_once 'Lang.php';
	require_once 'LocaleUtils.php';
	require_once 'GettextTranslate.php';

	class Locale {

		var $engine;
		var $options;
		var $locale;
		var $locale_best_fit;

		public function __construct($cfg) {
			$this->options = $cfg;
		}

		/**
		 * Sets current locale to the best match from the installed ones
		 * (e.g. if only 'en' locale is installed but $locale is set to 'en_GB'
		 * $locale_best_fit will become 'en' instead). This allows for locale
		 * fallbacks.
		 *
		 * @param type $currentlocale normalized locale string
		 */
		public function set($currentlocale) {
			$this->locale = $currentlocale;
			$available_locales = Locale::listAvailableLocales($this->options['gettext_root']);
			//var_dump($available_locales);
			$this->locale_best_fit = LocaleUtils::lookup($available_locales, $currentlocale);
			_log("Setting locale to best available fit '{$this->locale_best_fit}'");

			// configure gettext environment
			$this->configGettext($this->options['gettext_root'],
									$this->locale_best_fit,
									$this->options['gettext_domain']);
			
			// inject translation object for current locale
			$gt = new GettextTranslate($this->options['gettext_root'], 
										$this->locale_best_fit,
										$this->options['gettext_domain']);
			Registry::set('translate', $gt);
		}

		/**
		 * Returns a list with all the installed directories containing a locale.
		 *
		 * @param type $root root directory for locales in this Goteo install
		 * @return array list of locales installed
		 */
		protected function listAvailableLocales($root) {
			$retval = array();

			foreach(glob($root.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir) {
				array_push($retval, basename($dir) );
			}

			return $retval;
		}

		protected function domainFileExists($root, $locale, $domain) {
			return \file_exists("{$root}/{$locale}/LC_MESSAGES/{$this->options['gettext_domain']}.mo");
		}

		protected function localeExists($root, $locale, $domain) {
			return \file_exists("{$root}/{$locale}/LC_MESSAGES/{$domain}.po");
		}

		static public function gettextSupported() {
			return function_exists("gettext");
		}

		/**
		 * Use the php.mo library to compile gettext .po files if the binary doesn't exist.
		 *
		 * @return true/false on success/failure
		 */
		private function compileLanguageFile($root, $locale, $domain) {
			return \phpmo_convert("{$root}/{$locale}/LC_MESSAGES/{$domain}.po");
		}

		/**
		 * bypass gettext caching by using a clever file-renaming
		 * mechanism described in http://blog.ghost3k.net/articles/php/11/gettext-caching-in-php
		 */
		protected function spawnUncachedDomain($root, $locale, $domain) {
			// path to the .MO file that we should monitor
			$filename = "{$root}/{$locale}/LC_MESSAGES/{$domain}.mo";
			$mtime = \filemtime($filename); // check its modification time
			// our new unique .MO file
			$filename_new = "{$root}/{$locale}/LC_MESSAGES/{$domain}_{$mtime}.mo";

			if (!\file_exists($filename_new)) {  // check if we have created it before
				// if not, create it now, by copying the original
				\copy($filename, $filename_new);
				_log("creating new domain {$filename_new}");
			}
			// compute the new domain name
			$domain_new = "{$domain}_{$mtime}";

			return $domain_new;
		}

		/**
		 * Set gettext configuration to be used by PHP.
		 *
		 * @param $locale the string that determines the current locale (e.g. en_GB)
		 * @param $domain the filename for the .po file used by gettext to load messages from
		 */
		public function configGettext($root, $locale, $domain) {
			if (!Locale::gettextSupported()) {
				_log(GoteoLogLevel::WARNING, "GETTEXT not supported on this server, all texts will appear in spanish");
				return;
			}

			\setlocale(\LC_TIME, $locale);
			\putenv("LANG={$locale}");
			\setlocale(LC_ALL, $locale);

			if (Locale::localeExists($root, $locale, $domain)) {
				// determine if the language binary file exists, if not try to generate it automatically
				if (!Locale::domainFileExists($root, $locale, $domain)) {
					Locale::compileLanguageFile($root, $locale, $domain);
					_log("compiling missing language file binary");
				}

				// generate a new uncached domain file if caching bypass featured is enabled
				if (true == $this->options['gettext_bypass_caching']) {
					$domain = Locale::spawnUncachedDomain($root, $locale, $domain);
					_log("bypassing gettext caching");
				}

				// configure settext domain
				\bindtextdomain($domain, $root);
				\bind_textdomain_codeset($domain, 'UTF-8');
				\textdomain($domain);
			} else {
				_log("WARNING - Locale is not installed ${locale}");
			}
		}

	} // class

} // namespace
?>
