<?php
class ControllerSectionsBannersAndCarousels extends Controller {
	public function index() {
		$this->load->language('sections/banners_and_carousels');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/banners_and_carousels', $data);
	}
}
