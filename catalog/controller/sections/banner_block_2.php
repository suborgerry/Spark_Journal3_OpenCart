<?php
class ControllerSectionsBannerBlock2 extends Controller {
	public function index() {
		$this->load->language('sections/banner_block_2');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/banner_block_2', $data);
	}
}
