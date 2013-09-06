<?php

namespace Goteo\Library\i18n {
	require_once 'library/PHP-Gettext/Gettext.php';

	class GettextTranslate {
		var $locale;
		var $gt;

		public function __construct($_root, $_locale, $_domain) {
			$this->locale = $_locale;
			/* directory domain locale */
			_log("GettextTranslate::ctor root={$_root}, locale={$_locale}, domain={$_domain}");
			$this->gt = new \Gettext_PHP($_root, $_domain, $_locale);
		}

		public function text($msg) {
			return $this->gt->gettext($msg);
		}
	} // class
} // ns
?>
