<?php
class ControllerSectionsBannerBlock extends Controller {
	public function index() {
		$this->load->language('sections/banner_block');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/banner_block', $data);
	}
}
