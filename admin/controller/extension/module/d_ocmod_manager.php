<?php
class ControllerExtensionModuleDOCMODManager extends Controller {
    
    public $route = 'extension/module/d_ocmod_manager';
    private $error = array();
    private $codename = 'd_ocmod_manager';

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model($this->route);
        $this->load->model('design/layout');
        $this->load->model('setting/setting');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/cache');
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->addScript('view/javascript/d_ocmod_manager/library/alertify.min.js');
        $this->document->addStyle('view/stylesheet/d_ocmod_manager/library/alertify/alertify.min.css');
        $this->document->addStyle('view/stylesheet/d_ocmod_manager/library/alertify/bootstrap-theme.cstm.min.css');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->d_admin_style = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_admin_style.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'.json'), true);
        $this->d_twig_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_twig_manager.json'));

    }

    public function index() {

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        $this->model_extension_d_opencart_patch_cache->clearTwig();

        if ($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getStyles('light');
        }

        $this->load->model('extension/module/d_ocmod_manager');

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['d_shopunity'] = $this->d_shopunity;
        $data['token'] = $this->model_extension_d_opencart_patch_user->getToken();
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_refresh'] = $this->language->get('text_refresh');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_replace'] = $this->language->get('entry_replace');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_author'] = $this->language->get('column_author');
        $data['column_version'] = $this->language->get('column_version');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_clear'] = $this->language->get('button_clear');
        $data['button_add'] = $this->language->get('button_insert');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_link'] = $this->language->get('button_link');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['tab_general'] = $this->language->get('tab_modification');
        $data['tab_log'] = $this->language->get('tab_log');
        $data['tab_setting'] = $this->language->get('tab_setting');

        //support
        $data['text_support'] = $this->language->get('text_support');
        $data['entry_support'] = $this->language->get('entry_support');
        $data['button_support'] = $this->language->get('button_support');
        $data['support_url'] = $this->extension['support']['url'];

        $data['text_powered_by'] = $this->language->get('text_powered_by');
        $data['setup'] = $this->isSetup();
        $data['text_setup'] = $this->language->get('text_setup');
        $data['setup_link'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/setup');

        $data['refresh'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/refresh');
        $data['clear'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/clear');
        $data['add'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/add');
        $data['delete'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/delete');
        $data['get_cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionAjax('module');
        $data['replace_link'] = str_replace('&amp;', '&', $this->model_extension_d_opencart_patch_url->link($this->route.'/replace'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
        );

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $modification_total = $this->model_extension_module_d_ocmod_manager->getTotalModifications();

        $results = $this->model_extension_module_d_ocmod_manager->getModifications($filter_data);
        $data['modifications'] = array();
        foreach ($results as $result) {
            $data['modifications'][] = array(
                'modification_id' => $result['modification_id'],
                'name'            => $result['name'],
                'author'          => $result['author'],
                'version'         => $result['version'],
                'status'          => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'link'            => $result['link'],
                'edit'          => $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/edit', '&modification_id=' . $result['modification_id'], 'SSL'),
                'enable'          => $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/enable', '&modification_id=' . $result['modification_id'], 'SSL'),
                'disable'         => $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/disable', '&modification_id=' . $result['modification_id'], 'SSL'),
                'enabled'         => $result['status'],
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        
        $data['replace'] = $this->config->get("d_ocmod_manager_replace");
    
        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', '&sort=name' . $url, 'SSL');
        $data['sort_author'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', '&sort=author' . $url, 'SSL');
        $data['sort_version'] = $this->model_extension_d_opencart_patch_url->link('extension/version', '&sort=author' . $url, 'SSL');
        $data['sort_status'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', '&sort=status' . $url, 'SSL');
        $data['sort_date_added'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', '&sort=date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $modification_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($modification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($modification_total - $this->config->get('config_limit_admin'))) ? $modification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $modification_total, ceil($modification_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        // Log
        $file = DIR_LOGS . 'ocmod.log';

        if (file_exists($file)) {
            $data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $data['log'] = '';
        }

        $data['clear_log'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/clearlog');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/module/d_ocmod_manager_list', $data));

    } 

    public function setup(){
        $this->load->config($this->codename);
        $this->load->model('setting/setting');

        $setting = array();
        $setting[$this->codename.'_setting'] = $this->config->get($this->codename.'_setting');
        $setting['d_ocmod_manager_replace'] = 1;
        $this->model_setting_setting->editSetting($this->codename, $setting, $this->store_id);
        $this->session->data['success'] = $this->language->get('success_setup');
        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route));
    }

    public function install()
    {
        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }

        $this->load->model('extension/d_opencart_patch/modification');
        $this->model_extension_d_opencart_patch_modification->setModification('d_ocmod_manager.xml', 1);
        $this->model_extension_d_opencart_patch_modification->refreshCache();
    }

    public function uninstall()
    {
        $this->load->model('extension/d_opencart_patch/modification');
        $this->model_extension_d_opencart_patch_modification->setModification('d_ocmod_manager.xml', 0);
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting($this->codename);
    }

    public function isSetup() {
        
        $this->load->model('setting/setting');

        $setting_module = $this->model_setting_setting->getSetting($this->codename);
        if (!empty($setting_module)) {
            return true;
        }else{
            return false;
        }
    }
    
    protected function getForm($code='') {
        if ($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getStyles('light');
        }
        $data['is_button_download'] =  $this->request->get['route'] != 'extension/module/d_ocmod_manager/add' ? true:false;
        
        $this->document->addScript('view/javascript/d_codemirror/lib/xml.js');
            
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
        );
        
        $url = '';
                
        if (isset($this->request->get['modification_id'])) {
            $url .= '&modification_id=' . $this->request->get['modification_id'];
        }
        
        $data['save'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/save', $url, 'SSL');
        $data['download'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager/download', $url, 'SSL');
        $data['cancel'] = $this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager',  $url, 'SSL');
                        
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_form'] = $this->language->get('text_form');
        $data['code'] = $code;
        
        $data['button_save'] = $this->language->get('button_save');
        $data['button_download'] = $this->language->get('button_download');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['token'] = $this->model_extension_d_opencart_patch_user->getUrlToken();
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
                
        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/module/d_ocmod_manager_form', $data));
    }
    
    public function add() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addScript('view/javascript/shopunity/jquery.autosize.min.js');     
        $this->document->addStyle('view/javascript/shopunity/codemirror/codemirror.css');
        $this->document->addScript('view/javascript/shopunity/codemirror/codemirror.js');
        $this->document->addScript('view/javascript/shopunity/codemirror/css.js');

        $this->load->model('extension/module/d_ocmod_manager');
        $this->config->load($this->codename);
        $this->getForm($this->config->get($this->codename."_dummy_xml"));
        
        
    }
    
    public function edit() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addScript('view/javascript/shopunity/jquery.autosize.min.js');     
        $this->document->addStyle('view/javascript/shopunity/codemirror/codemirror.css');
        $this->document->addScript('view/javascript/shopunity/codemirror/codemirror.js');
        $this->document->addScript('view/javascript/shopunity/codemirror/css.js');

        $this->load->model('extension/module/d_ocmod_manager');
        
        if (isset($this->request->get['modification_id'])) {
            $modification_id = $this->request->get['modification_id'];
            $result = $this->model_extension_module_d_ocmod_manager->getModification($modification_id);
            if(VERSION != '2.0.0.0') {
                $xml = $result['xml'];
            }else{
                $xml = $result['code'];
            } 
            $this->getForm($xml);
        }
    }
    
    public function download() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addScript('view/javascript/shopunity/jquery.autosize.min.js');     
        $this->document->addStyle('view/javascript/shopunity/codemirror/codemirror.css');
        $this->document->addScript('view/javascript/shopunity/codemirror/codemirror.js');
        $this->document->addScript('view/javascript/shopunity/codemirror/css.js');
        
        $this->load->model('extension/module/d_ocmod_manager');
        
        if (isset($this->request->get['modification_id'])) {
            $modification_id = $this->request->get['modification_id'];
            $result = $this->model_extension_module_d_ocmod_manager->getModification($modification_id); 
            $output = html_entity_decode($result['xml']);
            $this->response->addHeader('Content-Type: application/xml');
            $this->response->setOutput($output);
        }
    }
    
    public function save() {
    
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addScript('view/javascript/shopunity/jquery.autosize.min.js');     
        $this->document->addStyle('view/javascript/shopunity/codemirror/codemirror.css');
        $this->document->addScript('view/javascript/shopunity/codemirror/codemirror.js');
        $this->document->addScript('view/javascript/shopunity/codemirror/css.js');

        $this->load->model('extension/module/d_ocmod_manager');
        
        $url = '';
        $xml = '';
        $modification_id=false;
        
        if (isset($this->request->get['modification_id'])) $modification_id = $this->request->get['modification_id'];
        
        if (isset($this->request->post['code'])) $xml = html_entity_decode($this->request->post['code']);
        
        if ($this->validate()) {
        
            if ($xml) {
                try {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);

                    $name = $dom->getElementsByTagName('name')->item(0);

                    if ($name) {
                        $name = $name->nodeValue;
                    } else {
                        $name = '';
                    }

                    $author = $dom->getElementsByTagName('author')->item(0);

                    if ($author) {
                        $author = $author->nodeValue;
                    } else {
                        $author = '';
                    }

                    $code = $dom->getElementsByTagName('code')->item(0);

                    if ($code) {
                        $code = $code->nodeValue;
                    } else {
                        $code = '';
                    }

                    $version = $dom->getElementsByTagName('version')->item(0);

                    if ($version) {
                        $version = $version->nodeValue;
                    } else {
                        $version = '';
                    }

                    $link = $dom->getElementsByTagName('link')->item(0);

                    if ($link) {
                        $link = $link->nodeValue;
                    } else {
                        $link = '';
                    }

                    $modification_data = array(
                        'name'       => $name,
                        'author'     => $author,
                        'code'     => $code,
                        'version'    => $version,
                        'link'       => $link,
                        'xml'       => $xml,
                        'status'     => 1
                    );
                    
                    if ($modification_id) $this->model_extension_module_d_ocmod_manager->deleteModification($modification_id);
                    $this->model_extension_module_d_ocmod_manager->addModification($modification_data);
                    
                } catch(Exception $exception) {
                    $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
                }
            }
        
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }
        
        $this->getForm($xml);
    }
    
    public function delete() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addScript('view/javascript/shopunity/jquery.autosize.min.js');     
        $this->document->addStyle('view/javascript/shopunity/codemirror/codemirror.css');
        $this->document->addScript('view/javascript/shopunity/codemirror/codemirror.js');
        $this->document->addScript('view/javascript/shopunity/codemirror/css.js');

        $this->load->model('extension/module/d_ocmod_manager');

        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $modification_id) {
                $this->model_extension_module_d_ocmod_manager->deleteModification($modification_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    public function refresh($patch='') {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('extension/module/d_ocmod_manager');

        if ($this->validate()) {
            //Log
            $log = array();

            // Clear all modification files
            $files = glob(DIR_MODIFICATION . '{*.php,*.tpl}', GLOB_BRACE);

            if ($files) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }

            // Begin
            $xml = array();

            // Load the default modification XML
            $xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

            // Get the default modification file
            
            $results = $this->model_extension_module_d_ocmod_manager->getModifications();

            if($patch=='patch'){
                foreach ($results as $result) {
                    if($result['name']=='d_opencart_patch' || $result['name']=='d_twig_manager'){
                        $xml[] = $result['xml'];
                    }
                }
            }else{
                foreach ($results as $result) {
                    if ($result['status']) {
                        $xml[] = $result['xml'];
                    }
                }
            }
            $modification = array();

            foreach ($xml as $xml) {
                if (empty($xml)){
                    continue;
                }
                
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->loadXml($xml);

                // Log
                $log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

                // Wipe the past modification store in the backup array
                $recovery = array();

                // Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
                if (isset($modification)) {
                    $recovery = $modification;
                }

                $files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

                foreach ($files as $file) {
                    $operations = $file->getElementsByTagName('operation');

                    $files = explode('|', $file->getAttribute('path'));

                    foreach ($files as $file) {
                        $path = '';

                        // Get the full path of the files that are going to be used for modification
                        if ((substr($file, 0, 7) == 'catalog')) {
                            $path = DIR_CATALOG . substr($file, 8);
                        }

                        if ((substr($file, 0, 5) == 'admin')) {
                            $path = DIR_APPLICATION . substr($file, 6);
                        }

                        if ((substr($file, 0, 6) == 'system')) {
                            $path = DIR_SYSTEM . substr($file, 7);
                        }

                        if ($path) {
                            $files = glob($path, GLOB_BRACE);

                            if ($files) {
                                foreach ($files as $file) {
                                    // Get the key to be used for the modification cache filename.
                                    if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
                                        $key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
                                    }

                                    if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
                                        $key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
                                    }

                                    if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
                                        $key = 'system/' . substr($file, strlen(DIR_SYSTEM));
                                    }

                                    // If file contents is not already in the modification array we need to load it.
                                    if (!isset($modification[$key])) {
                                        $content = file_get_contents($file);

                                        $modification[$key] = preg_replace('~\r?\n~', "\n", $content);
                                        $original[$key] = preg_replace('~\r?\n~', "\n", $content);

                                        // Log
                                        $log[] = PHP_EOL . 'FILE: ' . $key;
                                    }

                                    foreach ($operations as $operation) {
                                        $error = $operation->getAttribute('error');

                                        // Ignoreif
                                        $ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

                                        if ($ignoreif) {
                                            if ($ignoreif->getAttribute('regex') != 'true') {
                                                if (strpos($modification[$key], $ignoreif->textContent) !== false) {
                                                    continue;
                                                }
                                            } else {
                                                if (preg_match($ignoreif->textContent, $modification[$key])) {
                                                    continue;
                                                }
                                            }
                                        }

                                        $status = false;

                                        // Search and replace
                                        if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
                                            // Search
                                            $search = $operation->getElementsByTagName('search')->item(0)->textContent;
                                            $trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
                                            $index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

                                            // Trim line if no trim attribute is set or is set to true.
                                            if (!$trim || $trim == 'true') {
                                                $search = trim($search);
                                            }

                                            // Add
                                            $add = $operation->getElementsByTagName('add')->item(0)->textContent;
                                            $trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
                                            $position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
                                            $offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

                                            if ($offset == '') {
                                                $offset = 0;
                                            }

                                            // Trim line if is set to true.
                                            if ($trim == 'true') {
                                                $add = trim($add);
                                            }

                                            // Log
                                            $log[] = 'CODE: ' . $search;

                                            // Check if using indexes
                                            if ($index !== '') {
                                                $indexes = explode(',', $index);
                                            } else {
                                                $indexes = array();
                                            }

                                            // Get all the matches
                                            $i = 0;

                                            $lines = explode("\n", $modification[$key]);

                                            for ($line_id = 0; $line_id < count($lines); $line_id++) {
                                                $line = $lines[$line_id];

                                                // Status
                                                $match = false;

                                                // Check to see if the line matches the search code.
                                                if (stripos($line, $search) !== false) {
                                                    // If indexes are not used then just set the found status to true.
                                                    if (!$indexes) {
                                                        $match = true;
                                                    } elseif (in_array($i, $indexes)) {
                                                        $match = true;
                                                    }

                                                    $i++;
                                                }

                                                // Now for replacing or adding to the matched elements
                                                if ($match) {
                                                    switch ($position) {
                                                        default:
                                                        case 'replace':
                                                            $new_lines = explode("\n", $add);

                                                            if ($offset < 0) {
                                                                array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

                                                                $line_id -= $offset;
                                                            } else {
                                                                array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
                                                            }
                                                            break;
                                                        case 'before':
                                                            $new_lines = explode("\n", $add);

                                                            array_splice($lines, $line_id - $offset, 0, $new_lines);

                                                            $line_id += count($new_lines);
                                                            break;
                                                        case 'after':
                                                            $new_lines = explode("\n", $add);

                                                            array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

                                                            $line_id += count($new_lines);
                                                            break;
                                                    }

                                                    // Log
                                                    $log[] = 'LINE: ' . $line_id;

                                                    $status = true;
                                                }
                                            }

                                            $modification[$key] = implode("\n", $lines);
                                        } else {
                                            $search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
                                            $limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
                                            $replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

                                            // Limit
                                            if (!$limit) {
                                                $limit = -1;
                                            }

                                            // Log
                                            $match = array();

                                            preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

                                            // Remove part of the the result if a limit is set.
                                            if ($limit > 0) {
                                                $match[0] = array_slice($match[0], 0, $limit);
                                            }

                                            if ($match[0]) {
                                                $log[] = 'REGEX: ' . $search;

                                                for ($i = 0; $i < count($match[0]); $i++) {
                                                    $log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
                                                }

                                                $status = true;
                                            }

                                            // Make the modification
                                            $modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
                                        }

                                        if (!$status) {
                                            // Abort applying this modification completely.
                                            if ($error == 'abort') {
                                                $modification = $recovery;
                                                // Log
                                                $log[] = 'NOT FOUND - ABORTING!';
                                                break 5;
                                            }
                                            // Skip current operation or break
                                            elseif ($error == 'skip') {
                                                // Log
                                                $log[] = 'NOT FOUND - OPERATION SKIPPED!';
                                                continue;
                                            }
                                            // Break current operations
                                            else {
                                                // Log
                                                $log[] = 'NOT FOUND - OPERATIONS ABORTED!';
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Log
                $log[] = '----------------------------------------------------------------';
            }

            // Log
            $ocmod = new Log('ocmod.log');
            $ocmod->write(implode("\n", $log));

            // Write all modification files
            foreach ($modification as $key => $value) {
                // Only create a file if there are changes
                if ($original[$key] != $value) {
                    $path = '';

                    $directories = explode('/', dirname($key));

                    foreach ($directories as $directory) {
                        $path = $path . '/' . $directory;

                        if (!is_dir(DIR_MODIFICATION . $path)) {
                            @mkdir(DIR_MODIFICATION . $path, 0777);
                        }
                    }

                    $handle = fopen(DIR_MODIFICATION . $key, 'w');

                    fwrite($handle, $value);

                    fclose($handle);
                }
            }

            // Maintance mode back to original settings
            // $this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);

            // Do not return success message if refresh() was called with $data
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    public function clear() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('extension/module/d_ocmod_manager');

        if ($this->validate()) {
            // Make path into an array
            $path = array(DIR_MODIFICATION . '*');

            // While the path array is still populated keep looping through
            while (count($path) != 0) {
                $next = array_shift($path);

                foreach (glob($next) as $file) {
                    // If directory add to path array
                    if (is_dir($file)) {
                        $path[] = $file . '/*';
                    }

                    // Add the file to the files to be deleted array
                    $files[] = $file;
                }
            }
            
            // Reverse sort the file array
            rsort($files);
            
            // Clear all modification files
            foreach ($files as $file) {
                if ($file != DIR_MODIFICATION . 'index.html') {
                    // If file just delete
                    if (is_file($file)) {
                        unlink($file);
    
                    // If directory use the remove directory function
                    } elseif (is_dir($file)) {
                        rmdir($file);
                    }
                }
            }                   
            $this->refresh('patch');
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    public function enable() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('extension/module/d_ocmod_manager');

        if (isset($this->request->get['modification_id']) && $this->validate()) {
            $this->model_extension_module_d_ocmod_manager->enableModification($this->request->get['modification_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    public function disable() {
        $this->load->language('extension/module/d_ocmod_manager');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('extension/module/d_ocmod_manager');

        if (isset($this->request->get['modification_id']) && $this->validate()) {
            $this->model_extension_module_d_ocmod_manager->disableModification($this->request->get['modification_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    public function clearlog() {
        $this->load->language('extension/module/d_ocmod_manager');

        if ($this->validate()) {
            $handle = fopen(DIR_LOGS . 'ocmod.log', 'w+');

            fclose($handle);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link('extension/module/d_ocmod_manager', $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    
    private function userPermission($permission = 'modify') {
        $this->language->load('extension/module/d_ocmod_manager');
        
        if (!$this->user->hasPermission($permission, 'extension/module/d_ocmod_manager')) {
            $this->session->data['error'] = $this->language->get('error_permission');
            return false;
        }else{
            return true;
        }
    }

    public function replace(){

        if(isset($this->request->post['d_ocmod_manager_replace'])){
            $this->load->model('setting/setting');
            $this->request->post['d_ocmod_manager_replace'] = ($this->request->post['d_ocmod_manager_replace'] == 'true') ? 1 : 0;
            $this->model_setting_setting->editSetting($this->codename, $this->request->post);
        }
        $this->response->setOutput(json_encode($this->request->post));
    }
}