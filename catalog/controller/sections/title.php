<?php
class ControllerSectionsTitle extends Controller {
	public function index() {
		$this->load->language('sections/title');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/title', $data);
	}
}
