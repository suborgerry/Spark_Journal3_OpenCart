<?php
class ControllerSectionsNewsletter extends Controller {
	public function index() {
		$this->load->language('sections/newsletter');
		$this->load->model('catalog/information');
		$data['informations'] = array();
		
		return $this->load->view('sections/newsletter', $data);
	}
}
