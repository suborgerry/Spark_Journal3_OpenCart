<?php
class ControllerSectionsCMCARSELECTOR extends Controller {
	public function index() {
		$this->load->language('sections/cm_car_selector');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/cm_car_selector', $data);
	}
}
