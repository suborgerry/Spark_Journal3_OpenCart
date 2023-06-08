<?php
class ControllerSectionsQuickSearch extends Controller {
	public function index() {
		$this->load->language('sections/quick_search');

		$this->load->model('catalog/information');

		$data['informations'] = array();
        $data['cm_search'] = CM_SEARCH;

        $data['tags'] = ['Headlight','Fog Light','Mirror','Radiator','Bumper'];

		return $this->load->view('sections/quick_search', $data);
	}
}
