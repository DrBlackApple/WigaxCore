<?php

	namespace Wigax;

	abstract class BaseController {

		protected $templater;

		private $view_folder;

		private $events;

		public function __construct ($view_folder) {
			$this->view_folder = $view_folder . "/";
			$this->templater = new \Latte\Engine();
			$this->templater->setTempDirectory($this->view_folder . 'cache');
			$e = $_SESSION['res']->get('events');
			$this->events = empty($e) ? array() : $e;
		}

		protected function render ($file, $params = array(), $events = array()) {
			$this->addEvent($events);
			return $this->templater->renderToString($this->view_folder . $file, $params);
		}

		protected function addEvent ($events = array()) {
			foreach($events as $k => $v) {
				$e = new \Wigax\Route\Event($k, $v);
				if(!in_array($e, $this->events))
					$this->events[] = $e;
			}
			return $this;
		}

		public function getEvents () {
			return $this->events;
		}

	}
?>