<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

		$data['header'] = $this->load->controller('common/header');


		$data['slider_section'] = $this->load->controller('sections/slider_section');
		$data['banner_block'] = $this->load->controller('sections/banner_block');
		$data['section_product'] = $this->load->controller('sections/section_product');
		$data['banner_block_2'] = $this->load->controller('sections/banner_block_2');
		$data['section_product_with_banner'] = $this->load->controller('sections/section_product_with_banner');
		$data['carousel'] = $this->load->controller('sections/carousel');
		$data['product_with_banner'] = $this->load->controller('sections/product_with_banner');
		$data['banners_and_carousels'] = $this->load->controller('sections/banners_and_carousels');
		$data['banner_4'] = $this->load->controller('sections/banner_4');
		$data['title'] = $this->load->controller('sections/title');
		$data['custom_block'] = $this->load->controller('sections/custom_block');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
