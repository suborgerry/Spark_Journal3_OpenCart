<?php
class ControllerSectionsHeaderTop extends Controller {
	public function index() {
		$this->load->language('sections/header_top');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/header_top', $data);
	}
}
