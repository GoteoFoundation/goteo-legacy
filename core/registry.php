<?php

namespace Goteo\Core {

	class Registry {

		private static $instance = null;

		public static function get($key){
			return self::getInstance()->$key;
		}

		public static function set ($key, $val){
			if (!is_string($key)){
				return false;
			}
			self::getInstance()->$key = $val;
		}

		public static function delete($key){
			$return = false;
			if (self::exist($key)){
				unset ( self::getInstance()->$key );
				$return = true;
			}
			return $return;
		}

		public static function exist($key) {
			$return = false;
			if (self::getInstance()->$key){
				$return = true;
			}
			return $return;
		}

		public function __set($key, $val){
			$this->$key = $val;
		}

		public function __get($key){
			return $this->$key;
		}

		protected static function getInstance() {
			if (!( self::$instance instanceof Registry ) || self::$instance == null) {
				self::$instance = new Registry();
			}
			return self::$instance;
		}

		private function __construct() {}
		private function __clone() {}

	} // class

} // ns