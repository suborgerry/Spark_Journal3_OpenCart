<?php
class ControllerSectionsJsScripts extends Controller {
	public function index() {
		$this->load->language('sections/js_scripts');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/js_scripts', $data);
	}
}
