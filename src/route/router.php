<?php
	
	namespace Wigax\Route;

	class Router {

		private $routes = array();

		private $events = array();

		private $assets = array();

		private $res;

		public function __construct (&$res) {
			$this->res = $res;
		}

		public function &route() {
			$this->routes = $this->res->get('routes');
			$this->events = $this->res->get('events');
			$this->assets = $this->res->get('assets');
			$type = $_SERVER['REQUEST_METHOD'];
			$url = urldecode(preg_replace("#\#(.+)#", "", preg_replace("#\?(.+)#", "", $_SERVER['REQUEST_URI'])));
			$headers = apache_request_headers();;
			if(preg_match("#^/assets/.*#", $url)) {
				foreach ($this->assets as $key => $a) {
					if($a->getPath() == $url)
						return $a;
				}
			} else if(isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest' && preg_match("#^/.*@.*$#", $url)){
				list($widget, $action) = explode('@', str_replace('/', '', $url));
				foreach ($this->events as $key => $e) {
					if($e->getUrlShape() == $url)
						return $e;
				}
			} else {
				foreach ($this->routes as $k => $r) {
					if(preg_match("#^" . $r->getUrl() . "$#", $url)){
						if(strtoupper($r->getType()) == $type || $r->getType() == "any"){
							$r->setRealUrl($url);
							return $r;
						}
					}
				}
			}

			header("HTTP/1.1 404");
			exit;
		}

		public function &redirectToRoute($route_name) {
			foreach ($this->routes as $key => $r) {
				if($r->getName() == $route_name)
					return $r;
			}
		}
	}

?>