<?php
	
<<<<<<< HEAD
	namespace Wigax\Kernel;

	class Kernel {

		private $config;

		private $router;

		private $loader;

		private $ressource_handler;

		private $xml_builder;

		private $root = "";

		public function __construct($root) {
			session_start();

			$this->root = $root;

			$this->config = new Config($root . "/config");
			$_SESSION['res'] = $this->ressource_handler = isset($_SESSION['res']) ? $_SESSION['res'] : new \Wigax\Drivers\RessourceHandler;
			$this->buildRes();
			$this->router = new \Wigax\Route\Router($this->ressource_handler);
			$this->loader = new Loader($root . $this->config->getWigaxConf()['Controllers_folder'], $root . $this->config->getWigaxConf()['Views_folder']);
			$this->xml_builder = new XmlBuilder();
		}

		public function start() {
			$this->initDatabase();

			$call = $this->router->route();
			if(is_object($call)) {
				$ret = $this->loader->call($call, $this);
				if(is_array($ret)) {

				} else 
					echo $ret;
			} else
				header('Location: /');
		}

		public function redirectToRoute ($name) {
			return $this->router->redirectToRoute($name);
		}

		private function buildRes () {
			if(!isset($_SESSION['builded_res'])) {
				foreach (require_once($this->root . $this->config->getWigaxConf()['Routes_file']) as $key => $val) {
				 	$out[] = new \Wigax\Route\Route($key, $val);
				}
				foreach (require_once($this->root . $this->config->getWigaxConf()['Events_file']) as $key => $val) {
					$out1[] = new \Wigax\Route\Event($key, $val);
				}
				foreach ($this->getDirContents($this->root . $this->config->getWigaxConf()['Assets_folder']) as $key => $a) {
					$out2[] = new \Wigax\Route\Asset($a);
				}
				$this->ressource_handler->put('routes', $out)->put('events', $out1)->put('assets', $out2);
				$_SESSION['builded_res'] = true;
			}
		}

		private function getDirContents($dir, &$results = array()){
		    $files = scandir($dir);

		    foreach($files as $key => $value){
		        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
		        if(!is_dir($path)) {
		            $results[] = $path;
		        } else if($value != "." && $value != "..") {
		            $results += $this->getDirContents($path, $results);
		        }
		    }

		    return $results;
		}

		private function initDatabase () {
			class_alias('RedBeanPHP\R', '\R');	
			$db = $this->config->buildDatabaseConfig();
			if(is_array($db))
				\R::setup($db[0], $db[1], $db[2]);
			else
				\R::setup($db);
		}

=======
	namespace Wigax;

	class Kernel {

>>>>>>> 7db2f79ba7da51f3aedb4de63d238893f1182f41
	}
	
?>