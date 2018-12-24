<?php

	namespace Wigax\Drivers;

	class RessourceHandler {

		private $res = array();

		public function put ($key, $val) {
			$this->res[$key] = $val;
			return $this;
		}

		public function &get ($key) {
			return $this->res[$key];
		}

	}

?>