<?php
	
	namespace Wigax\Kernel;

	class Config {

		private $config_folder = "";

		private $database_conf = array();

		private $wigax_conf = array();

		private $root;

		public function __construct($config_folder) {
			$this->config_folder = $config_folder;
			$this->root = str_replace('/config', '', $config_folder);

			if(is_dir($config_folder)) {
				$this->database_conf = require_once($config_folder . "/database.php");
				$this->wigax_conf = require_once($config_folder . "/wigax.php");
			}
		}

		public function buildDatabaseConfig() {
			if($this->database_conf["driver"] == 'sqlite')
				return 'sqlite:' . $this->database_conf["file"];
			else
				return [$this->database_conf["driver"] . ":host=" . $this->database_conf["hostname"] . ";dbname=" . $this->database_conf["database"], $this->database_conf["user"], $this->database_conf["pass"]];
		}

		public function getWigaxConf() {
			return $this->wigax_conf;
		}
	}

?>