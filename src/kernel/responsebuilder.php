<?php

	namespace Wigax\Kernel;

	class ResponseBuilder {

		private $start_xml = '<?xml version="1.0" encoding="UTF-8"?>';

		private $jquery_cdn = "https://code.jquery.com/jquery-3.3.1.min.js";

		private $wigax_js = "/assets/js/wigax.js";

		public function __construct ($jquery_cdn = "", $wigax_js) {
			$this->jquery_cdn = !empty($jquery_cdn) ? $jquery_cdn : $this->jquery_cdn;
			$this->wigax_js = !empty($wigax_js) ? $wigax_js : $this->wigax_js;
		}

		private function buildHead ($title = "", $url="", $meta = array()) {
			$out = empty($url) ? '<head>' : '<head url="' . $url . '">';
			$out .= empty($title) ? "" : '<title>' . $title . '</title>';
			$out .= '<metasection>';
			foreach ($meta as $key => $value)
				$out .= $val;
			$out .= '</metasection>';
			$out .= '</head>';
			return $out;
		}

		public function buildAjaxEventResponse (Array $elements) {
			$out = $this->start_xml . "<start>";
			if(isset($elements['title'])) {
				$t = $elements['title'];
				unset($elements['title']);
			} else
				$t = "";
			$out .= $this->buildHead($t);
			$out .= '<data>';
			$i = 1;
			foreach ($elements as $key => $val) {
				$out.='<block id="' . $i . '" target="' . $key . '">'; 
				$out.= $val;
				$out.='</block>';
			}
			$out .='</data></start>';

			return $out;
		}

		public function buildAjaxRedirectionResponse (&$html, $route) {
			$out = $this->start_xml;
			$html = preg_replace("#^<!.* html>#", "", $html);
			$out .= '<main url="'.$route->getRealUrl().'">' . $html . '</main>';
			return $out;
		}

		public function buildEventScript (&$html, $events) {
			$add = '<script type="text/javascript">';
			foreach ($events as $key => $e) {
				$add .= '$("#' . $e->getId() . '").on("' . $e->getAction() . '", function () {sendEvent(this, "'.$e->getAction().'")});';
			}
			$add .= '</script></body>';
			$html = preg_replace("#</body>#", $add, $html);
			return $this;
		}

		public function addJs (&$html) {
			$html = preg_replace('#</body>#', '<script src="' . $this->jquery_cdn . '"></script><script src="' . $this->wigax_js . '"></script></body>', $html);
			return $this;
		}

	}

?>