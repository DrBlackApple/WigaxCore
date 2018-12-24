<?php

	namespace Wigax\Route;

	class Route {

		private $url;

		private $real_url;

		private $name;

		private $type;

		private $controller;

		private $method;

		private $is_redirection = false;

		private $var_field= array();

		public function __construct($url, $contr) {
			$url = str_replace(' ', '', $url);
			if(preg_match_all("#(.+)\|(.*)>(.*)#", $url, $out)){
				$this->url = $out[1][0];
				$this->type = $out[2][0];
				$this->name = $out[3][0];
			} else {
				$this->url = $url;
				$this->type = "any";
			}

			if(preg_match_all("#\{(.*)\}#", $this->url, $out)){
				$this->url = preg_replace("#{(.*)}#", "(\w*)", $this->url);
				$this->var_fields[$out[1][0]] = $this->url;
			}
			if(preg_match_all("#(.*)@(.*)#", $contr, $out)) {
				$this->controller = $out[1][0];
				$this->method = $out[2][0];
			} else {
				$this->controller = $contr;
				$this->is_redirection = true;
			}
		}

		public function setRealUrl($url) {
			$this->real_url = $url;
		}

		public function getField($name) {
			preg_match_all('#'.$this->var_fields[$name].'#', $this->real_url, $out);
			return $out[1][0];
		}

		public function isRedirection() {
			return $this->is_redirection;
		}

		public function getUrl() {
			return $this->url;
		}

		public function getName() {
			return $this->name;
		}

		public function getType() {
			return $this->type;
		}

		public function getController() {
			return $this->controller;
		}

		public function getMethod() {
			return $this->method;
		}
	}

?>