<?php
class ControllerSectionsSliderSection extends Controller {
	public function index() {
		$this->load->language('sections/slider_section');

		$this->load->model('catalog/information');

		$data['informations'] = array();

        $data['cm_car_selector'] = CM_MSELSCT;
//        $data['cm_car_selector'] = $this->load->controller('sections/cm_car_selector');

        return $this->load->view('sections/slider_section', $data);
	}
}
