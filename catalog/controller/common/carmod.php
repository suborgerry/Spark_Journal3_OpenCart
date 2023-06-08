<?php  
class Controllercommoncarmod extends Controller {
	public function index() {
	
		//Save customer group ID for CarMod 
		$_SESSION['CM_CMS_USER_GROUP'] = intval($this->customer->getGroupId());
		$_SESSION['CM_CMS_DEFAULT_CUR'] = $this->config->get('config_currency');
		if(defined('TITLE_x')){$this->document->setTitle(TITLE_x);}
		if(defined('KEYWORDS_x')){$this->document->setKeywords(KEYWORDS_x);}
		if(defined('DESCRIPTION_x')){$this->document->setDescription(DESCRIPTION_x);}
		if (isset($this->request->get['route'])) {
			//$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		//CarMod content
		global $CarMod_Content;
		$data['content_carmod'] = $CarMod_Content;
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
//        $data['footer_fixed'] = $this->load->controller('sections/footer_fixed');
//        $data['mobile_nav'] = $this->load->controller('sections/mobile_nav');
//        $data['js_scripts'] = $this->load->controller('sections/js_scripts');
//        $data['modal_dialog'] = $this->load->controller('sections/modal_dialog');
//        $data['newsletter'] = $this->load->controller('sections/newsletter');
//        $data['cart_popup'] = $this->load->controller('sections/cart_popup');
//        $data['modal'] = $this->load->controller('sections/modal');
		$this->response->setOutput($this->load->view('common/carmod', $data));

	}
}
?>