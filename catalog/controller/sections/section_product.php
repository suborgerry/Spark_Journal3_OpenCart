<?php
class ControllerSectionsSectionProduct extends Controller {
	public function index() {
		$this->load->language('sections/section_product');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/section_product', $data);
	}
}
