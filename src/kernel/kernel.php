<?php
	
	namespace Wigax\Kernel;

	class Kernel {

		private $config;

		private $router;

		private $loader;

		private $ressource_handler;

		private $ret_builder;

		private $root = "";

		public function __construct($root) {
			session_start();

			$this->root = $root;

			$this->config = new Config($root . "/config");
			$_SESSION['res'] = isset($_SESSION['res']) ? $_SESSION['res'] : new \Wigax\Drivers\RessourceHandler;
			$this->ressource_handler = &$_SESSION['res'];
			$this->buildRes();
			$this->router = new \Wigax\Route\Router($this->ressource_handler);
			$this->loader = new Loader($root . $this->config->getWigaxConf()['Controllers_folder'], $root . $this->config->getWigaxConf()['Views_folder']);
			$this->ret_builder = new ResponseBuilder($this->config->getWigaxConf()['Jquery'], $this->config->getWigaxConf()['WigaxJs']);
		}

		public function start() {
			$this->initDatabase();

			$call = $this->router->route();
			if($call instanceof \Wigax\Route\Route || ($call instanceof \Wigax\Route\Event && $call->isRedirection())){
				if(isset($_SESSION['back_page']) && $call != $_SESSION['back_page'])
					$_SESSION['res']->delete('events');
				$_SESSION['back_page'] = $call;
			}
			
			if(is_object($call)) {
				$ret = $this->loader->call($call, $this);
				if(is_array($ret)) {
					echo $this->ret_builder->buildAjaxEventResponse($ret);
				} else {
					if(is_object($ret)){
						$r = $this->loader->call($ret, $this);
						$this->ret_builder->addJs($r)->buildEventScript($r, $this->ressource_handler->get('events'));
						if($call instanceof \Wigax\Route\Event) {
							$ret->setParam($call->getParam());
							echo $this->ret_builder->buildAjaxRedirectionResponse($r, $ret);
						} else
							echo $r;
					} else {
						$this->ret_builder->addJs($ret)->buildEventScript($ret, $this->ressource_handler->get('events'));
						echo $ret;
					}
				}
			} else
				header('Location: /');
			//session_destroy();
		}

		public function redirectToRoute ($name) {
			return $this->router->redirectToRoute($name);
		}

		public function addRes($key, $val) {
			$this->ressource_handler->put($key, $val);
			return $this;
		}

		private function buildRes () {
			foreach (require_once($this->root . $this->config->getWigaxConf()['Routes_file']) as $key => $val) {
			 	$out[] = new \Wigax\Route\Route($key, $val);
			}
			foreach ($this->getDirContents($this->root . $this->config->getWigaxConf()['Assets_folder']) as $key => $a) {
				$out2[] = new \Wigax\Route\Asset($a);
			}
			$this->ressource_handler->put('routes', $out)->put('assets', $out2);

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

	}
	
?>