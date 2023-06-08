<?php
class ControllerSectionsMobileNav extends Controller {
	public function index() {
		$this->load->language('sections/mobile_nav');
		$this->load->model('catalog/information');
		$data['informations'] = array();
		
		return $this->load->view('sections/mobile_nav', $data);
	}
}
