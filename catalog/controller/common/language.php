<?php
class ControllerCommonLanguage extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

		$data['code'] = $this->session->data['language'];

		$this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}
			
			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}
		
		//CarMod
		if(defined('CM_PROLOG_INCLUDED')){
			$data['redirect'] = $_SERVER['REQUEST_URI'];
		}
		
		return $this->load->view('common/language', $data);
	}
	
	
	public function language() {
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];
		}
		
		if (isset($this->request->post['redirect'])) {
			//CarMod
			$this->response->redirect($this->request->post['redirect'].'?&ln='.$this->request->post['code']); 
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
		
		
	
	}
}