<?php
	
	namespace Wigax\Route;

	class Asset {

		private $path;

		private $real_path;

		private $type;

		public function __construct($path) {
			$this->real_path = $path;
			$this->path = preg_replace('#\\\#', '/', preg_replace("#.*\\\public#", "", $path));
			$this->type = preg_replace("#^.*\.#", "", $path);
		}

		public function getType() {
			return $this->type;
		}

		public function getPath() {
			return $this->path;
		}

		public function getRealPath() {
			return $this->real_path;
		}
	}
?>