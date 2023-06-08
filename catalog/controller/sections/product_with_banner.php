<?php
class ControllerSectionsProductWithBanner extends Controller {
	public function index() {
		$this->load->language('sections/product_with_banner');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/product_with_banner', $data);
	}
}
