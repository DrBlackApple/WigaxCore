<?php

	namespace Wigax\Kernel;

	class Loader {

		private $controller_folder;

		private $namespace = "App\\Controller\\";

		private $view_folder;

		private $kernel;

		public function __construct ($controller_folder, $view_folder) {
			$this->controller_folder = $controller_folder;
			$this->view_folder = $view_folder;
		}

		public function call($call, &$kernel) {
			$this->kernel = $kernel;
			if ($call instanceof \Wigax\Route\Asset) 
				return file_get_contents($call->getRealPath());
			else if ($call->isRedirection()){
				if(!($call instanceof \Wigax\Route\Event)) 
					header("HTTP/1.1 301");
				return $kernel->redirectToRoute($call->getController());
			} else if ($call instanceof \Wigax\Route\Route)
				return $this->callMethod($call->getController(), $call->getMethod(), $call);
			else if ($call instanceof \Wigax\Route\Event)
				return $this->callMethod($call->getController(), $call->getMethod(), $call);
		}

		private function callMethod($controller, $method, $params) {
			$file = $controller . '.php';
			$controller = $this->namespace . $controller;
			require_once $this->controller_folder . '/' . $file;
			$c = new $controller($this->view_folder);
			if($c) {
				$h = $c->$method($params);
				$e = $c->getEvents();
				if(!empty($e))
					$this->kernel->addRes('events', $c->getEvents());
				return $h;
			}
		}
	}

?>