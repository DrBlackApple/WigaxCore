<?php

	namespace Wigax;

	abstract class BaseController {

		private $templater;

		private $view_folder;

		public function __construct ($view_folder) {
			$this->view_folder = $view_folder . "/";
			$this->templater = new \Latte\Engine();
		}

		protected function render ($file, $params) {
			return $this->templater->renderToString($this->view_folder . $file, $params);
		}

	}
?>