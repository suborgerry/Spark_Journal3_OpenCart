<?php
class ControllerSectionsSectionProductWithBanner extends Controller {
	public function index() {
		$this->load->language('sections/section_product_with_banner');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/section_product_with_banner', $data);
	}
}
