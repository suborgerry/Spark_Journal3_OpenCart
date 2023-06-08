<?php
class ControllerSectionsModal extends Controller {
	public function index() {
		$this->load->language('sections/modal');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/modal', $data);
	}
}
