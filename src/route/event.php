<?php
	
	namespace Wigax\Route;

	class Event {

		private $widget_name;

		private $action;

		private $controller;

		private $method;

		private $param;

		private $is_redirection = false;

		public function __construct($key, $val) {
			list($this->widget_name, $this->action) = explode('->', $key);
			if(preg_match("#@#", $val)){
				list($this->controller, $this->method) = explode('@', $val);
			} else {
				$this->is_redirection = true;
				if(preg_match_all('#\[(\w*)\]#', $val, $out)) {
					$this->param = $out[1][0];
					$this->controller = preg_replace('#\[\w*\]#', '', $val);
				} else 
					$this->controller = $val;
			}
		}

		public function getUrlShape() {
			return '/' . $this->widget_name . "@" . $this->action;
		}

		public function getId() {
			return $this->widget_name;
		}

		public function getAction() {
			return $this->action;
		}

		public function getController() {
			return $this->controller;
		}

		public function getParam () {
			return $this->param;
		}

		public function getMethod(){
			return $this->method;
		}

		public function isRedirection() {
			return $this->is_redirection;
		}
	}
?>