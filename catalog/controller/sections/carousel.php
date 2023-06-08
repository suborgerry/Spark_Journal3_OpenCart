<?php
class ControllerSectionsCarousel extends Controller {
	public function index() {
		$this->load->language('sections/carousel');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/carousel', $data);
	}
}
