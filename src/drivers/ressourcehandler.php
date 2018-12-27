<?php

	namespace Wigax\Drivers;

	class RessourceHandler {

		private $res = array();

		public function __construct () {
			
		}

		public function put ($key, $val) {
			$this->res[$key] = $val;
			return $this;
		}

		public function get ($key) {
			return isset($this->res[$key]) ? $this->res[$key] : [];
		}

		public function delete($key) {
			unset($this->res[$key]);
			return $this;
		}

	}

?>