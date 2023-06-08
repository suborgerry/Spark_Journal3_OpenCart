<?php
class ControllerSectionsBanner4 extends Controller {
	public function index() {
		$this->load->language('sections/banner_4');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/banner_4', $data);
	}
}
