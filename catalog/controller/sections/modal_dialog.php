<?php
class ControllerSectionsModalDialog extends Controller {
	public function index() {
		$this->load->language('sections/modal_dialog');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/modal_dialog', $data);
	}
}
