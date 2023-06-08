<?php
class ControllerSectionsFooterFixed extends Controller {
	public function index() {
		$this->load->language('sections/footer_fixed');

		$this->load->model('catalog/information');

		$data['informations'] = array();
		
		return $this->load->view('sections/footer_fixed', $data);
	}
}
