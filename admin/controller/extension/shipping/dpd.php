<?php
# Разработчик: Ipol
# ipolh.com
# DPD - служба доставки

class ControllerExtensionShippingDpd extends Controller {
	private $error;
    private $extensionsLink;
    private $token;
    private $code;

    public function __construct($registry)
    {
        parent::__construct($registry);

        // Module Constants
        if (VERSION >= '3.0.0.0') {
            $this->token = sprintf('user_token=%s', $this->session->data['user_token']);
            $this->extensionsLink = 'marketplace/extension';
            $this->code = 'shipping_dpd';
        } elseif (VERSION >= '2.3.0.0') {
            $this->token = sprintf('token=%s', $this->session->data['token']);
            $this->extensionsLink = 'extension/extension';
            $this->code = 'dpd';
        }
    }

	public function index() {
        $language_array = $this->load->language('extension/shipping/dpd');

        foreach ($language_array as $language_key => $language_value) {
            $data[$language_key] = $language_value;
        }

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/shipping/dpd');
		$this->load->model('localisation/weight_class');
		$config = $this->getOpencartConfig();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting($this->code, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/shipping/dpd', $this->token . '&type=shipping', true));
		}

		# Heading_title
		$data['heading_title']		= $this->language->get('heading_title');

		# Text
		$data['text_status_info']	= $this->language->get('text_status_info');
		$data['text_edit']		= $this->language->get('text_edit');
		$data['text_main_setting']	= $this->language->get('text_main_setting');
		$data['text_main_setting_end']	= $this->language->get('text_main_setting_end');
		$data['text_dimensions']	= $this->language->get('text_dimensions');
		$data['text_dimensions_heading1']	= $this->language->get('text_dimensions_heading1');
		$data['text_dimensions_heading2']	= $this->language->get('text_dimensions_heading2');
		$data['text_dimensions_center']	= $this->language->get('text_dimensions_center');
		$data['text_dimensions_end1']	= $this->language->get('text_dimensions_end1');
		$data['text_dimensions_end2']	= $this->language->get('text_dimensions_end2');
		$data['text_dimensions_warning']	= $this->language->get('text_dimensions_warning');
		$data['text_auth_russian']	= $this->language->get('text_auth_russian');
		$data['text_auth_belarus']	= $this->language->get('text_auth_belarus');
		$data['text_auth_kazahstan']	= $this->language->get('text_auth_kazahstan');
		$data['text_h1_terminal']	= $this->language->get('text_h1_terminal');
		$data['text_h1_door']	= $this->language->get('text_h1_door');
		$data['text_enabled']	= $this->language->get('text_enabled');
		$data['text_disabled']	= $this->language->get('text_disabled');
		$data['text_percent']	= $this->language->get('text_percent');
		$data['text_fix']	= $this->language->get('text_fix');
		$data['text_non']	= $this->language->get('text_non');
		$data['text_service_procedures']	= $this->language->get('text_service_procedures');
		$data['text_step_1']	= $this->language->get('text_step_1');
		$data['text_step_2']	= $this->language->get('text_step_2');
		$data['text_step_3']	= $this->language->get('text_step_3');
		$data['text_step_4']	= $this->language->get('text_step_4');

		# Entry
		$data['entry_status_module']	= $this->language->get('entry_status_module');
		$data['entry_dpd_number']		= $this->language->get('entry_dpd_number');
		$data['entry_dpd_auth']		= $this->language->get('entry_dpd_auth');
		$data['entry_currency']		= $this->language->get('entry_currency');
		$data['entry_account_default']		= $this->language->get('entry_account_default');
		$data['entry_test']		= $this->language->get('entry_test');
		$data['entry_dpd_button']		= $this->language->get('entry_dpd_button');
		$data['entry_pvz']		= $this->language->get('entry_pvz');
		$data['entry_api_map']		= $this->language->get('entry_api_map');
		$data['entry_use_for']		= $this->language->get('entry_use_for');
		$data['entry_weight']		= $this->language->get('entry_weight');
		$data['entry_length']		= $this->language->get('entry_length');
		$data['entry_width']		= $this->language->get('entry_width');
		$data['entry_height']		= $this->language->get('entry_height');
		$data['entry_not_calculate']		= $this->language->get('entry_not_calculate');
		$data['entry_tariff_default']		= $this->language->get('entry_tariff_default');
		$data['entry_max_for_default']		= $this->language->get('entry_max_for_default');
		$data['entry_cart_equally_product']		= $this->language->get('entry_cart_equally_product');
		$data['entry_calculate_for_product']		= $this->language->get('entry_calculate_for_product');
		$data['entry_ceil']		= $this->language->get('entry_ceil');
		$data['entry_term_shipping']		= $this->language->get('entry_term_shipping');
		$data['entry_h_comission']		= $this->language->get('entry_h_comission');
		$data['entry_h_comission_info']		= $this->language->get('entry_h_comission_info');
		$data['entry_comission_for_collection']		= $this->language->get('entry_comission_for_collection');
		$data['entry_comission_for_product']		= $this->language->get('entry_comission_for_product');
		$data['entry_min_sum_comission']		= $this->language->get('entry_min_sum_comission');
		$data['entry_bind_payment']		= $this->language->get('entry_bind_payment');
		$data['entry_not_payment']		= $this->language->get('entry_not_payment');
		$data['entry_contact_face']		= $this->language->get('entry_contact_face');
		$data['entry_name_company']		= $this->language->get('entry_name_company');
		$data['entry_phone_sender']		= $this->language->get('entry_phone_sender');
		$data['entry_email_sender']		= $this->language->get('entry_email_sender');
		$data['entry_numb_r_sender']		= $this->language->get('entry_numb_r_sender');
		$data['entry_pass']		= $this->language->get('entry_pass');
		$data['entry_h_address']		= $this->language->get('entry_h_address');
		$data['entry_h_alert']		= $this->language->get('entry_h_alert');
		$data['entry_h_options']		= $this->language->get('entry_h_options');
		$data['entry_city_sender']		= $this->language->get('entry_city_sender');
		$data['entry_street_sender']	= $this->language->get('entry_street_sender');
		$data['entry_ab_street_sender']	= $this->language->get('entry_ab_street_sender');
		$data['entry_house_sender']	= $this->language->get('entry_house_sender');
		$data['entry_corp_sender']	= $this->language->get('entry_corp_sender');
		$data['entry_structure_sender']	= $this->language->get('entry_structure_sender');
		$data['entry_poss_sender']	= $this->language->get('entry_poss_sender');
		$data['entry_office_sender']	= $this->language->get('entry_office_sender');
		$data['entry_apart_sender']	= $this->language->get('entry_apart_sender');
		$data['entry_terminal_sender']	= $this->language->get('entry_terminal_sender');
		$data['entry_departure_method']	= $this->language->get('entry_departure_method');
		$data['entry_payment_method_delivery']	= $this->language->get('entry_payment_method_delivery');
		$data['entry_dpd_transit_interval']	= $this->language->get('entry_dpd_transit_interval');
		$data['entry_delivery_time_interval']	= $this->language->get('entry_delivery_time_interval');
		$data['entry_quantity_places']	= $this->language->get('entry_quantity_places');
		$data['entry_content_sender']	= $this->language->get('entry_content_sender');
		$data['entry_desc_sender_desc']	= $this->language->get('entry_desc_sender_desc');
		$data['entry_val_cargo']	= $this->language->get('entry_val_cargo');
		$data['entry_weekend_delivery']	= $this->language->get('entry_weekend_delivery');
		$data['entry_condition']	= $this->language->get('entry_condition');
		$data['entry_loading_unloading']	= $this->language->get('entry_loading_unloading');
		$data['entry_return_doc']	= $this->language->get('entry_return_doc');
		$data['entry_wait_address']	= $this->language->get('entry_wait_address');
        $data['entry_partial_purchase']	= $this->language->get('entry_partial_purchase');
		$data['entry_order_mail']	= $this->language->get('entry_order_mail');
		$data['entry_name']	= $this->language->get('entry_name');
		$data['entry_status']	= $this->language->get('entry_status');
		$data['entry_sort_order']	= $this->language->get('entry_sort_order');
		$data['entry_description']	= $this->language->get('entry_description');
		$data['entry_image']	= $this->language->get('entry_image');
		$data['entry_markup']	= $this->language->get('entry_markup');
		$data['entry_markup_type']	= $this->language->get('entry_markup_type');
		$data['entry_set_accepted']	= $this->language->get('entry_set_accepted');
		$data['entry_mark_delivery_paid']	= $this->language->get('entry_mark_delivery_paid');
		$data['entry_track_status_dpd']	= $this->language->get('entry_track_status_dpd');
		$data['entry_import']	= $this->language->get('entry_import');

		$dpd_statuses = \Ipol\DPD\DB\Order\Model::StatusList($config);
		$data['dpd_statuses'] = array();

		foreach($dpd_statuses as $k => $row){
			$data['dpd_statuses'][] = array(
				'entry'	=> $row . ':',
				'key'	=> $k,
			);
		}

		# Help
		$data['help_number']		= $this->language->get('help_number');
		$data['help_auth']		= $this->language->get('help_auth');
		$data['help_account_default']		= $this->language->get('help_account_default');
		$data['help_test']		= $this->language->get('help_test');
		$data['help_api_map']		= $this->language->get('help_api_map');
		$data['help_tariff']		= $this->language->get('help_tariff');
		$data['help_cart_equally_product']		= $this->language->get('help_cart_equally_product');
		$data['help_calculate_for_product']		= $this->language->get('help_calculate_for_product');
		$data['help_ceil']		= $this->language->get('help_ceil');
		$data['help_not_payment']		= $this->language->get('help_not_payment');
		$data['help_contact_face']		= $this->language->get('help_contact_face');
		$data['help_name_company']		= $this->language->get('help_name_company');
		$data['help_city_sender']		= $this->language->get('help_city_sender');
		$data['help_ab_street_sender']	= $this->language->get('help_ab_street_sender');
		$data['help_departure_method']	= $this->language->get('help_departure_method');
		$data['help_val_cargo']	= $this->language->get('help_val_cargo');
		$data['help_weekend_delivery']	= $this->language->get('help_weekend_delivery');
		$data['help_loading_unloading']	= $this->language->get('help_loading_unloading');
		$data['help_return_doc']	= $this->language->get('help_return_doc');
		$data['help_order_mail']	= $this->language->get('help_order_mail');
		$data['help_mark_delivery_paid']	= $this->language->get('help_mark_delivery_paid');

		# Warning
		$data['warning_weekend_delivery']	= $this->language->get('warning_weekend_delivery');

		# Tabs
		$data['tab_main']			= $this->language->get('tab_main');
		$data['tab_general']		= $this->language->get('tab_general');
		$data['tab_dimensions']		= $this->language->get('tab_dimensions');
		$data['tab_calculate_shipping']		= $this->language->get('tab_calculate_shipping');
		$data['tab_sender']			= $this->language->get('tab_sender');
		$data['tab_recipient']		= $this->language->get('tab_recipient');
		$data['tab_desc_sender']	= $this->language->get('tab_desc_sender');
		$data['tab_status']			= $this->language->get('tab_status');
		$data['tab_russian']		= $this->language->get('tab_russian');
		$data['tab_belarus']		= $this->language->get('tab_belarus');
		$data['tab_kazahstan']		= $this->language->get('tab_kazahstan');
		$data['tab_door']			= $this->language->get('tab_door');
		$data['tab_terminal']		= $this->language->get('tab_terminal');
		$data['tab_to_door']		= $this->language->get('tab_to_door');
		$data['tab_from_terminal']	= $this->language->get('tab_from_terminal');
		$data['tab_service']		= $this->language->get('tab_service');

		# Buttons
		$data['button_save']		= $this->language->get('button_save');
		$data['button_cancel']		= $this->language->get('button_cancel');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['file_exists'])) {
			$data['error_file_exists'] = $this->error['file_exists'];
		} else {
			$data['error_file_exists'] = '';
		}

		if (isset($this->error['russian_number'])) {
			$data['error_russian_number'] = $this->error['russian_number'];
		} else {
			$data['error_russian_number'] = '';
		}

		if (isset($this->error['russian_auth'])) {
			$data['error_russian_auth'] = $this->error['russian_auth'];
		} else {
			$data['error_russian_auth'] = '';
		}

		if (isset($this->error['kazahstan_number'])) {
			$data['error_kazahstan_number'] = $this->error['kazahstan_number'];
		} else {
			$data['error_kazahstan_number'] = '';
		}

		if (isset($this->error['kazahstan_auth'])) {
			$data['error_kazahstan_auth'] = $this->error['kazahstan_auth'];
		} else {
			$data['error_kazahstan_auth'] = '';
		}

		if (isset($this->error['belarus_number'])) {
			$data['error_belarus_number'] = $this->error['belarus_number'];
		} else {
			$data['error_belarus_number'] = '';
		}

		if (isset($this->error['belarus_auth'])) {
			$data['error_belarus_auth'] = $this->error['belarus_auth'];
		} else {
			$data['error_belarus_auth'] = '';
		}

		if (isset($this->error['weight'])) {
			$data['error_weight'] = $this->error['weight'];
		} else {
			$data['error_weight'] = '';
		}

		if (isset($this->error['length'])) {
			$data['error_length'] = $this->error['length'];
		} else {
			$data['error_length'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		if (isset($this->error['content'])) {
			$data['error_content'] = $this->error['content'];
		} else {
			$data['error_content'] = '';
		}

		if (isset($this->error['name_door'])) {
			$data['error_name_door'] = $this->error['name_door'];
		} else {
			$data['error_name_door'] = '';
		}

		if (isset($this->error['name_terminal'])) {
			$data['error_name_terminal'] = $this->error['name_terminal'];
		} else {
			$data['error_name_terminal'] = '';
		}

		$data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/dashboard', $this->token, true));
        $data['breadcrumbs'][] = array('text' => $this->language->get('text_extension'), 'href' => $this->url->link($this->extensionsLink, $this->token . '&type=shipping', true));
        $data['breadcrumbs'][] = array('text' => $this->language->get('heading_title') . $this->language->get('text_version'), 'href' => $this->url->link('extension/shipping/dpd', $this->token, true));

        $data['action'] = $this->url->link('extension/shipping/dpd', $this->token, true);
        $data['cancel'] = $this->url->link($this->extensionsLink, $this->token . '&type=shipping', true);

        if (VERSION >= '3.0.0.0') {
            $data['user_token'] = $this->session->data['user_token'];
        } elseif (VERSION >= '2.3.0.0') {
            $data['token'] = $this->session->data['token'];
        }

		# Currency
		$data['currencies'] = $this->model_extension_shipping_dpd->getCurrencies();

		# Array country
		$data['country_with_dpd'] = array();
		$data['country_with_dpd'][0]['value'] = 'RU';
		$data['country_with_dpd'][0]['name'] = 'Россия';
		$data['country_with_dpd'][1]['value'] = 'KZ';
		$data['country_with_dpd'][1]['name'] = 'Казахстан';
		$data['country_with_dpd'][2]['value'] = 'BY';
		$data['country_with_dpd'][2]['name'] = 'Беларусь';

		# Array dpd&buttons
		$data['buttons_with_dpd'] = array();
		$data['buttons_with_dpd'][0]['value'] = 0;
		$data['buttons_with_dpd'][0]['name'] = 'Только у заказов с доставкой DPD';
		$data['buttons_with_dpd'][1]['value'] = 1;
		$data['buttons_with_dpd'][1]['name'] = 'Всегда';

		# Array use for
		$data['use_for'] = array();
		$data['use_for'][0]['value'] = 0;
		$data['use_for'][0]['name'] = 'Для всего заказа';
		$data['use_for'][1]['value'] = 1;
		$data['use_for'][1]['name'] = 'Для товаров в заказе';

		# Array departure method
		$data['departure_method'] = array();
		$data['departure_method'][0]['value'] = 0;
		$data['departure_method'][0]['name'] = 'Мы хотим вызывать забор автоматически';
		$data['departure_method'][1]['value'] = 1;
		$data['departure_method'][1]['name'] = 'Мы будем возить заказы сами';

		# Array payment method delivery
		$data['payment_method_delivery'] = array();
		$data['payment_method_delivery'][0]['value'] = 0;
		$data['payment_method_delivery'][0]['name'] = 'У отправителя по безналичному расчёту';
		$data['payment_method_delivery'][1]['value'] = 'ОУП';
		$data['payment_method_delivery'][1]['name'] = 'Оплата у получателя наличными';
		$data['payment_method_delivery'][2]['value'] = 'ОУО';
		$data['payment_method_delivery'][2]['name'] = 'Оплата у отправителя наличными';

		# DPD Transit Time Interval
		$data['dpd_transit_interval'] = array();
		$data['dpd_transit_interval'][0]['value'] = '9-18';
		$data['dpd_transit_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$data['dpd_transit_interval'][1]['value'] = '9-13';
		$data['dpd_transit_interval'][1]['name'] = 'с 09:00 до 13:00';
		$data['dpd_transit_interval'][2]['value'] = '13-18';
		$data['dpd_transit_interval'][2]['name'] = 'с 13:00 до 18:00';

		# Delivery time interval
		$data['delivery_time_interval'] = array();
		$data['delivery_time_interval'][0]['value'] = '9-18';
		$data['delivery_time_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$data['delivery_time_interval'][1]['value'] = '9-14';
		$data['delivery_time_interval'][1]['name'] = 'с 09:00 до 14:00';
		$data['delivery_time_interval'][2]['value'] = '13-18';
		$data['delivery_time_interval'][2]['name'] = 'с 13:00 до 18:00';
		$data['delivery_time_interval'][3]['value'] = '18-22';
		$data['delivery_time_interval'][3]['name'] = 'с 18:00 да 22:00 (оплачивается дополнительно)';

		# Waiting on address
		$data['waiting_address'] = array();
		$data['waiting_address'][0]['value'] = 0;
		$data['waiting_address'][0]['name'] = '- Не установленно -';
		$data['waiting_address'][1]['value'] = 'ПРИМ';
		$data['waiting_address'][1]['name'] = 'С примеркой';
		$data['waiting_address'][2]['value'] = 'ПРОС';
		$data['waiting_address'][2]['name'] = 'Простая';
		$data['waiting_address'][3]['value'] = 'РАБТ';
		$data['waiting_address'][3]['name'] = 'С проверкой работоспособности';

        # Partial purchase
        $data['partial_purchase'] = array();
        $data['partial_purchase'][0]['value'] = 0;
        $data['partial_purchase'][0]['name'] = '- Не установленно -';
        $data['partial_purchase'][1]['value'] = 'ПРИМ';
        $data['partial_purchase'][1]['name'] = 'С примеркой';
        $data['partial_purchase'][2]['value'] = 'ПРОС';
        $data['partial_purchase'][2]['name'] = 'Простая';
        $data['partial_purchase'][3]['value'] = 'РАБТ';
        $data['partial_purchase'][3]['name'] = 'С проверкой работоспособности';

		# Array use for
		$tariffs = $this->getTariffList();


		foreach($tariffs as $key => $tariff){
			$data['not_calculate_dpd'][] = array(
				'value'	=>	$key,
				'name'	=>	$tariff
			);
		}

		# Main settings
		if (isset($this->request->post[$this->code . '_status'])) {
			$data[$this->code . '_status'] = $this->request->post[$this->code . '_status'];
		} else {
			$data[$this->code . '_status'] = $this->config->get($this->code . '_status');
		}

		if (isset($this->request->post[$this->code . '_russian_number'])) {
			$data[$this->code . '_russian_number'] = $this->request->post[$this->code . '_russian_number'];
		} else {
			$data[$this->code . '_russian_number'] = $this->config->get($this->code . '_russian_number');
		}

		if (isset($this->request->post[$this->code . '_russian_auth'])) {
			$data[$this->code . '_russian_auth'] = $this->request->post[$this->code . '_russian_auth'];
		} else {
			$data[$this->code . '_russian_auth'] = $this->config->get($this->code . '_russian_auth');
		}

		if (isset($this->request->post[$this->code . '_russian_currency'])) {
			$data[$this->code . '_russian_currency'] = $this->request->post[$this->code . '_russian_currency'];
		} else {
			$data[$this->code . '_russian_currency'] = $this->config->get($this->code . '_russian_currency');
		}

		if (isset($this->request->post[$this->code . '_kazahstan_number'])) {
			$data[$this->code . '_kazahstan_number'] = $this->request->post[$this->code . '_kazahstan_number'];
		} else {
			$data[$this->code . '_kazahstan_number'] = $this->config->get($this->code . '_kazahstan_number');
		}

		if (isset($this->request->post[$this->code . '_kazahstan_auth'])) {
			$data[$this->code . '_kazahstan_auth'] = $this->request->post[$this->code . '_kazahstan_auth'];
		} else {
			$data[$this->code . '_kazahstan_auth'] = $this->config->get($this->code . '_kazahstan_auth');
		}

		if (isset($this->request->post[$this->code . '_kazahstan_currency'])) {
			$data[$this->code . '_kazahstan_currency'] = $this->request->post[$this->code . '_kazahstan_currency'];
		} else {
			$data[$this->code . '_kazahstan_currency'] = $this->config->get($this->code . '_kazahstan_currency');
		}

		if (isset($this->request->post[$this->code . '_belarus_number'])) {
			$data[$this->code . '_belarus_number'] = $this->request->post[$this->code . '_belarus_number'];
		} else {
			$data[$this->code . '_belarus_number'] = $this->config->get($this->code . '_belarus_number');
		}

		if (isset($this->request->post[$this->code . '_belarus_auth'])) {
			$data[$this->code . '_belarus_auth'] = $this->request->post[$this->code . '_belarus_auth'];
		} else {
			$data[$this->code . '_belarus_auth'] = $this->config->get($this->code . '_belarus_auth');
		}

		if (isset($this->request->post[$this->code . '_belarus_currency'])) {
			$data[$this->code . '_belarus_currency'] = $this->request->post[$this->code . '_belarus_currency'];
		} else {
			$data[$this->code . '_belarus_currency'] = $this->config->get($this->code . '_belarus_currency');
		}

		if (isset($this->request->post[$this->code . '_kirgizstan_currency'])) {
			$data[$this->code . '_kirgizstan_currency'] = $this->request->post[$this->code . '_kirgizstan_currency'];
		} else {
			$data[$this->code . '_kirgizstan_currency'] = $this->config->get($this->code . '_kirgizstan_currency');
		}

		if (isset($this->request->post[$this->code . '_armenia_currency'])) {
			$data[$this->code . '_armenia_currency'] = $this->request->post[$this->code . '_armenia_currency'];
		} else {
			$data[$this->code . '_armenia_currency'] = $this->config->get($this->code . '_armenia_currency');
		}

		if (isset($this->request->post[$this->code . '_account_default'])) {
			$data[$this->code . '_account_default'] = $this->request->post[$this->code . '_account_default'];
		} else {
			$data[$this->code . '_account_default'] = $this->config->get($this->code . '_account_default');
		}

		if (isset($this->request->post[$this->code . '_test'])) {
			$data[$this->code . '_test'] = $this->request->post[$this->code . '_test'];
		} else {
			$data[$this->code . '_test'] = $this->config->get($this->code . '_test');
		}

		if (isset($this->request->post[$this->code . '_button'])) {
			$data[$this->code . '_button'] = $this->request->post[$this->code . '_button'];
		} else {
			$data[$this->code . '_button'] = $this->config->get($this->code . '_button');
		}

		if (isset($this->request->post[$this->code . '_pvz'])) {
			$data[$this->code . '_pvz'] = $this->request->post[$this->code . '_pvz'];
		} else {
			$data[$this->code . '_pvz'] = $this->config->get($this->code . '_pvz');
		}

        if (isset($this->request->post[$this->code . '_wsdl'])) {
            $data[$this->code . '_wsdl'] = $this->request->post[$this->code . '_wsdl'];
        } else {
            $data[$this->code . '_wsdl'] = $this->config->get($this->code . '_wsdl');
        }

		if (isset($this->request->post[$this->code . '_api_map'])) {
			$data[$this->code . '_api_map'] = $this->request->post[$this->code . '_api_map'];
		} else {
			$data[$this->code . '_api_map'] = $this->config->get($this->code . '_api_map');
		}

		if (isset($this->request->post[$this->code . '_use_for'])) {
			$data[$this->code . '_use_for'] = $this->request->post[$this->code . '_use_for'];
		} else {
			$data[$this->code . '_use_for'] = $this->config->get($this->code . '_use_for');
		}

		if (isset($this->request->post[$this->code . '_weight'])) {
			$data[$this->code . '_weight'] = $this->request->post[$this->code . '_weight'];
		} elseif($this->config->get($this->code . '_weight')) {
			$data[$this->code . '_weight'] = $this->config->get($this->code . '_weight');
		}else{
			$data[$this->code . '_weight'] = 1000;
		}

		if (isset($this->request->post[$this->code . '_length'])) {
			$data[$this->code . '_length'] = $this->request->post[$this->code . '_length'];
		} elseif($this->config->get($this->code . '_length')) {
			$data[$this->code . '_length'] = $this->config->get($this->code . '_length');
		}else{
			$data[$this->code . '_length'] = 200;
		}

		if (isset($this->request->post[$this->code . '_width'])) {
			$data[$this->code . '_width'] = $this->request->post[$this->code . '_width'];
		} elseif($this->config->get($this->code . '_width')) {
			$data[$this->code . '_width'] = $this->config->get($this->code . '_width');
		}else{
			$data[$this->code . '_width'] = 100;
		}

		if (isset($this->request->post[$this->code . '_height'])) {
			$data[$this->code . '_height'] = $this->request->post[$this->code . '_height'];
		} elseif($this->config->get($this->code . '_height')) {
			$data[$this->code . '_height'] = $this->config->get($this->code . '_height');
		}else{
			$data[$this->code . '_height'] = 200;
		}

		if (isset($this->request->post[$this->code . '_not_calculate'])) {
			$data[$this->code . '_not_calculate'] = $this->request->post[$this->code . '_not_calculate'];
		} else {
			$data[$this->code . '_not_calculate'] = $this->config->get($this->code . '_not_calculate');
		}

		if(is_array($data[$this->code . '_not_calculate'])){
			foreach($data[$this->code . '_not_calculate'] as $key => $mthd){
				$data[$this->code . '_not_calculate' . $mthd] = $mthd;
			}
		}

		if (isset($this->request->post[$this->code . '_tariff_default'])) {
			$data[$this->code . '_tariff_default'] = $this->request->post[$this->code . '_tariff_default'];
		} else {
			$data[$this->code . '_tariff_default'] = $this->config->get($this->code . '_tariff_default');
		}

		if (isset($this->request->post[$this->code . '_max_for_default'])) {
			$data[$this->code . '_max_for_default'] = $this->request->post[$this->code . '_max_for_default'];
		} else {
			$data[$this->code . '_max_for_default'] = $this->config->get($this->code . '_max_for_default');
		}

		if (isset($this->request->post[$this->code . '_cart_equally_product'])) {
			$data[$this->code . '_cart_equally_product'] = $this->request->post[$this->code . '_cart_equally_product'];
		} else {
			$data[$this->code . '_cart_equally_product'] = $this->config->get($this->code . '_cart_equally_product');
		}

		if (isset($this->request->post[$this->code . '_calculate_for_product'])) {
			$data[$this->code . '_calculate_for_product'] = $this->request->post[$this->code . '_calculate_for_product'];
		} else {
			$data[$this->code . '_calculate_for_product'] = $this->config->get($this->code . '_calculate_for_product');
		}

		if (isset($this->request->post[$this->code . '_ceil'])) {
			$data[$this->code . '_ceil'] = $this->request->post[$this->code . '_ceil'];
		} else {
			$data[$this->code . '_ceil'] = $this->config->get($this->code . '_ceil');
		}

		if (isset($this->request->post[$this->code . '_term_shipping'])) {
			$data[$this->code . '_term_shipping'] = $this->request->post[$this->code . '_term_shipping'];
		} else {
			$data[$this->code . '_term_shipping'] = $this->config->get($this->code . '_term_shipping');
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		# Способы оплаты
		$results_payment = $this->model_extension_shipping_dpd->getExtensions('payment');

        $method_data = array();

		foreach ($results_payment as $result) {
            if (VERSION >= '3.0.0.0') {
                $code = 'payment_' . $result['code'];
            } else {
                $code = $result['code'];
            }

            if ($this->config->get($code . '_status')) {
				$title = $this->getPayment($result['code']);

				$method_data[] = array(
					'code'		 => $result['code'],
					'sort_order' => $this->config->get($code . '_sort_order'),
					'title'		 => $title
				);
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		$data['payment_methods'] = $method_data;

		/* Настройки комиссии и минимальная группа по сортировке */
		foreach($data['customer_groups'] as $group){
			if (isset($this->request->post[$this->code . '_comission_for_collection_' . $group['customer_group_id']])) {
				$data[$this->code . '_comission_for_collection_'.$group['customer_group_id']] = $this->request->post[$this->code . '_comission_for_collection_'.$group['customer_group_id']];
			} else {
				$data[$this->code . '_comission_for_collection_'.$group['customer_group_id']] = $this->config->get($this->code . '_comission_for_collection_'.$group['customer_group_id']);
			}

			if (isset($this->request->post[$this->code . '_comission_for_product_' . $group['customer_group_id']])) {
				$data[$this->code . '_comission_for_product_'.$group['customer_group_id']] = $this->request->post[$this->code . '_comission_for_product_'.$group['customer_group_id']];
			} else {
				$data[$this->code . '_comission_for_product_'.$group['customer_group_id']] = $this->config->get($this->code . '_comission_for_product_'.$group['customer_group_id']);
			}

			if (isset($this->request->post[$this->code . '_min_sum_comission_' . $group['customer_group_id']])) {
				$data[$this->code . '_min_sum_comission_'.$group['customer_group_id']] = $this->request->post[$this->code . '_min_sum_comission_'.$group['customer_group_id']];
			} else {
				$data[$this->code . '_min_sum_comission_'.$group['customer_group_id']] = $this->config->get($this->code . '_min_sum_comission_'.$group['customer_group_id']);
			}

			if (isset($this->request->post[$this->code . '_bind_payment_' . $group['customer_group_id']])) {
				$data[$this->code . '_bind_payment_'.$group['customer_group_id']] = $this->request->post[$this->code . '_bind_payment_'.$group['customer_group_id']];
			} else {
				$data[$this->code . '_bind_payment_'.$group['customer_group_id']] = $this->config->get($this->code . '_bind_payment_'.$group['customer_group_id']);
			}

			if(is_array($data[$this->code . '_bind_payment_'.$group['customer_group_id']])){
				foreach($data[$this->code . '_bind_payment_'.$group['customer_group_id']] as $key => $mthd){
					$data[$this->code . '_bind_payment_'.$group['customer_group_id'] . $mthd] = $mthd;
				}
			}

			if (isset($this->request->post[$this->code . '_not_payment_' . $group['customer_group_id']])) {
				$data[$this->code . '_not_payment_'.$group['customer_group_id']] = $this->request->post[$this->code . '_not_payment_'.$group['customer_group_id']];
			} else {
				$data[$this->code . '_not_payment_'.$group['customer_group_id']] = $this->config->get($this->code . '_not_payment_'.$group['customer_group_id']);
			}

			$minimum_customer_id[] = $group['customer_group_id'];
		}

		$data['minimum_customer_id'] = min($minimum_customer_id);

		/* Настройки комиссии и минимальная группа по сортировке */

		if (isset($this->request->post[$this->code . '_contact_face'])) {
			$data[$this->code . '_contact_face'] = $this->request->post[$this->code . '_contact_face'];
		} else {
			$data[$this->code . '_contact_face'] = $this->config->get($this->code . '_contact_face');
		}

		if (isset($this->request->post[$this->code . '_name_company'])) {
			$data[$this->code . '_name_company'] = $this->request->post[$this->code . '_name_company'];
		} else {
			$data[$this->code . '_name_company'] = $this->config->get($this->code . '_name_company');
		}

		if (isset($this->request->post[$this->code . '_phone_sender'])) {
			$data[$this->code . '_phone_sender'] = $this->request->post[$this->code . '_phone_sender'];
		} else {
			$data[$this->code . '_phone_sender'] = $this->config->get($this->code . '_phone_sender');
		}

		if (isset($this->request->post[$this->code . '_email_sender'])) {
			$data[$this->code . '_email_sender'] = $this->request->post[$this->code . '_email_sender'];
		} else {
			$data[$this->code . '_email_sender'] = $this->config->get($this->code . '_email_sender');
		}

		if (isset($this->request->post[$this->code . '_numb_r_sender'])) {
			$data[$this->code . '_numb_r_sender'] = $this->request->post[$this->code . '_numb_r_sender'];
		} else {
			$data[$this->code . '_numb_r_sender'] = $this->config->get($this->code . '_numb_r_sender');
		}

		if (isset($this->request->post[$this->code . '_pass'])) {
			$data[$this->code . '_pass'] = $this->request->post[$this->code . '_pass'];
		} else {
			$data[$this->code . '_pass'] = $this->config->get($this->code . '_pass');
		}

		if (isset($this->request->post[$this->code . '_default_price'])) {
			$data[$this->code . '_default_price'] = $this->request->post[$this->code . '_default_price'];
		} else {
			$data[$this->code . '_default_price'] = $this->config->get($this->code . '_default_price');
		}

        /* Отправитель */
        if (isset($this->request->post[$this->code . '_address_sender'])) {
            $data[$this->code . '_address_sender'] = $this->request->post[$this->code . '_address_sender'];
        } elseif ($this->config->get($this->code . '_address_sender')) {
            $data[$this->code . '_address_sender'] = $this->config->get($this->code . '_address_sender');
        } else {
            $data[$this->code . '_address_sender'] = array();
        }

		/* Отправитель */

		if (isset($this->request->post[$this->code . '_pass_rec'])) {
			$data[$this->code . '_pass_rec'] = $this->request->post[$this->code . '_pass_rec'];
		} else {
			$data[$this->code . '_pass_rec'] = $this->config->get($this->code . '_pass_rec');
		}

		if (isset($this->request->post[$this->code . '_departure_method'])) {
			$data[$this->code . '_departure_method'] = $this->request->post[$this->code . '_departure_method'];
		} else {
			$data[$this->code . '_departure_method'] = $this->config->get($this->code . '_departure_method');
		}

		if (isset($this->request->post[$this->code . '_payment_method_delivery'])) {
			$data[$this->code . '_payment_method_delivery'] = $this->request->post[$this->code . '_payment_method_delivery'];
		} else {
			$data[$this->code . '_payment_method_delivery'] = $this->config->get($this->code . '_payment_method_delivery');
		}

		if (isset($this->request->post[$this->code . '_transit_interval_dpd'])) {
			$data[$this->code . '_transit_interval_dpd'] = $this->request->post[$this->code . '_transit_interval_dpd'];
		} else {
			$data[$this->code . '_transit_interval_dpd'] = $this->config->get($this->code . '_transit_interval_dpd');
		}

		if (isset($this->request->post[$this->code . '_delivery_time_interval'])) {
			$data[$this->code . '_delivery_time_interval'] = $this->request->post[$this->code . '_delivery_time_interval'];
		} else {
			$data[$this->code . '_delivery_time_interval'] = $this->config->get($this->code . '_delivery_time_interval');
		}

		if (isset($this->request->post[$this->code . '_quantity_places'])) {
			$data[$this->code . '_quantity_places'] = $this->request->post[$this->code . '_quantity_places'];
		} else {
			$data[$this->code . '_quantity_places'] = $this->config->get($this->code . '_quantity_places');
		}

		if (isset($this->request->post[$this->code . '_content_sender'])) {
			$data[$this->code . '_content_sender'] = $this->request->post[$this->code . '_content_sender'];
		} else {
			$data[$this->code . '_content_sender'] = $this->config->get($this->code . '_content_sender');
		}

		if (isset($this->request->post[$this->code . '_val_cargo'])) {
			$data[$this->code . '_val_cargo'] = $this->request->post[$this->code . '_val_cargo'];
		} else {
			$data[$this->code . '_val_cargo'] = $this->config->get($this->code . '_val_cargo');
		}

		if (isset($this->request->post[$this->code . '_weekend_delivery'])) {
			$data[$this->code . '_weekend_delivery'] = $this->request->post[$this->code . '_weekend_delivery'];
		} else {
			$data[$this->code . '_weekend_delivery'] = $this->config->get($this->code . '_weekend_delivery');
		}

		if (isset($this->request->post[$this->code . '_condition'])) {
			$data[$this->code . '_condition'] = $this->request->post[$this->code . '_condition'];
		} else {
			$data[$this->code . '_condition'] = $this->config->get($this->code . '_condition');
		}

		if (isset($this->request->post[$this->code . '_loading_unloading'])) {
			$data[$this->code . '_loading_unloading'] = $this->request->post[$this->code . '_loading_unloading'];
		} else {
			$data[$this->code . '_loading_unloading'] = $this->config->get($this->code . '_loading_unloading');
		}

		if (isset($this->request->post[$this->code . '_return_doc'])) {
			$data[$this->code . '_return_doc'] = $this->request->post[$this->code . '_return_doc'];
		} else {
			$data[$this->code . '_return_doc'] = $this->config->get($this->code . '_return_doc');
		}

		if (isset($this->request->post[$this->code . '_wait_address'])) {
			$data[$this->code . '_wait_address'] = $this->request->post[$this->code . '_wait_address'];
		} else {
			$data[$this->code . '_wait_address'] = $this->config->get($this->code . '_wait_address');
		}

        if (isset($this->request->post[$this->code . '_partial_purchase'])) {
            $data[$this->code . '_partial_purchase'] = $this->request->post[$this->code . '_partial_purchase'];
        } else {
            $data[$this->code . '_partial_purchase'] = $this->config->get($this->code . '_partial_purchase');
        }

		if (isset($this->request->post[$this->code . '_order_mail'])) {
			$data[$this->code . '_order_mail'] = $this->request->post[$this->code . '_order_mail'];
		} else {
			$data[$this->code . '_order_mail'] = $this->config->get($this->code . '_order_mail');
		}

		if (isset($this->request->post[$this->code . '_name_door'])) {
			$data[$this->code . '_name_door'] = $this->request->post[$this->code . '_name_door'];
		} elseif($this->config->get($this->code . '_name_door')){
			$data[$this->code . '_name_door'] = $this->config->get($this->code . '_name_door');
		}else{
			$data[$this->code . '_name_door'] = 'До двери';
		}

		if (isset($this->request->post[$this->code . '_door_status'])) {
			$data[$this->code . '_door_status'] = $this->request->post[$this->code . '_door_status'];
		} else {
			$data[$this->code . '_door_status'] = $this->config->get($this->code . '_door_status');
		}

		if (isset($this->request->post[$this->code . '_sort_order'])) {
			$data[$this->code . '_sort_order'] = $this->request->post[$this->code . '_sort_order'];
		} else {
			$data[$this->code . '_sort_order'] = $this->config->get($this->code . '_sort_order');
		}

		if (isset($this->request->post[$this->code . '_description_door'])) {
			$data[$this->code . '_description_door'] = $this->request->post[$this->code . '_description_door'];
		} else {
			$data[$this->code . '_description_door'] = $this->config->get($this->code . '_description_door');
		}

		# Image
		if (isset($this->request->post[$this->code . '_image_door'])) {
			$data[$this->code . '_image_door'] = $this->request->post[$this->code . '_image_door'];
		} else {
			$data[$this->code . '_image_door'] = $this->config->get($this->code . '_image_door');
		}

		$this->load->model('tool/image');

		if (isset($this->request->post[$this->code . '_image_door']) && is_file(DIR_IMAGE . $this->request->post[$this->code . '_image_door'])) {
			$data[$this->code . '_thumb_door'] = $this->model_tool_image->resize($this->request->post[$this->code . '_image_door'], 100, 100);
		} elseif($this->config->get($this->code . '_image_door') && is_file(DIR_IMAGE . $this->config->get($this->code . '_image_door'))) {
			$data[$this->code . '_thumb_door'] = $this->model_tool_image->resize($this->config->get($this->code . '_image_door'), 100, 100);
		}else{
			$data[$this->code . '_thumb_door'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post[$this->code . '_markup_door'])) {
			$data[$this->code . '_markup_door'] = $this->request->post[$this->code . '_markup_door'];
		} else {
			$data[$this->code . '_markup_door'] = $this->config->get($this->code . '_markup_door');
		}

		if (isset($this->request->post[$this->code . '_markup_type_door'])) {
			$data[$this->code . '_markup_type_door'] = $this->request->post[$this->code . '_markup_type_door'];
		} else {
			$data[$this->code . '_markup_type_door'] = $this->config->get($this->code . '_markup_type_door');
		}

		if (isset($this->request->post[$this->code . '_name_terminal'])) {
			$data[$this->code . '_name_terminal'] = $this->request->post[$this->code . '_name_terminal'];
		} elseif($this->config->get($this->code . '_name_terminal')) {
			$data[$this->code . '_name_terminal'] = $this->config->get($this->code . '_name_terminal');
		}else{
			$data[$this->code . '_name_terminal'] = 'До терминала';
		}

		if (isset($this->request->post[$this->code . '_terminal_status'])) {
			$data[$this->code . '_terminal_status'] = $this->request->post[$this->code . '_terminal_status'];
		} else {
			$data[$this->code . '_terminal_status'] = $this->config->get($this->code . '_terminal_status');
		}

		if (isset($this->request->post[$this->code . '_description_terminal'])) {
			$data[$this->code . '_description_terminal'] = $this->request->post[$this->code . '_description_terminal'];
		} else {
			$data[$this->code . '_description_terminal'] = $this->config->get($this->code . '_description_terminal');
		}

		# Image
		if (isset($this->request->post[$this->code . '_image_terminal'])) {
			$data[$this->code . '_image_terminal'] = $this->request->post[$this->code . '_image_terminal'];
		} else {
			$data[$this->code . '_image_terminal'] = $this->config->get($this->code . '_image_terminal');
		}

		if (isset($this->request->post[$this->code . '_image_terminal']) && is_file(DIR_IMAGE . $this->request->post[$this->code . '_image_terminal'])) {
			$data[$this->code . '_thumb_terminal'] = $this->model_tool_image->resize($this->request->post[$this->code . '_image_terminal'], 100, 100);
		} elseif($this->config->get($this->code . '_image_terminal') && is_file(DIR_IMAGE . $this->config->get($this->code . '_image_terminal'))) {
			$data[$this->code . '_thumb_terminal'] = $this->model_tool_image->resize($this->config->get($this->code . '_image_terminal'), 100, 100);
		}else{
			$data[$this->code . '_thumb_terminal'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post[$this->code . '_markup_terminal'])) {
			$data[$this->code . '_markup_terminal'] = $this->request->post[$this->code . '_markup_terminal'];
		} else {
			$data[$this->code . '_markup_terminal'] = $this->config->get($this->code . '_markup_terminal');
		}

		if (isset($this->request->post[$this->code . '_markup_type_terminal'])) {
			$data[$this->code . '_markup_type_terminal'] = $this->request->post[$this->code . '_markup_type_terminal'];
		} else {
			$data[$this->code . '_markup_type_terminal'] = $this->config->get($this->code . '_markup_type_terminal');
		}

		if (isset($this->request->post[$this->code . '_set_accepted'])) {
			$data[$this->code . '_set_accepted'] = $this->request->post[$this->code . '_set_accepted'];
		} else {
			$data[$this->code . '_set_accepted'] = $this->config->get($this->code . '_set_accepted');
		}

		if (isset($this->request->post[$this->code . '_mark_delivery_paid'])) {
			$data[$this->code . '_mark_delivery_paid'] = $this->request->post[$this->code . '_mark_delivery_paid'];
		} else {
			$data[$this->code . '_mark_delivery_paid'] = $this->config->get($this->code . '_mark_delivery_paid');
		}

		if (isset($this->request->post[$this->code . '_track_status_dpd'])) {
			$data[$this->code . '_track_status_dpd'] = $this->request->post[$this->code . '_track_status_dpd'];
		} else {
			$data[$this->code . '_track_status_dpd'] = $this->config->get($this->code . '_track_status_dpd');
		}

		foreach($dpd_statuses as $k => $row){
			if (isset($this->request->post[$this->code . '_status_' . $k])) {
				$data[$this->code . '_status_' . $k] = $this->request->post[$this->code . '_status_' . $k];
			} else {
				$data[$this->code . '_status_' . $k] = $this->config->get($this->code . '_status_' . $k);
			}
		}

		# Подключение таблицы
		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');

		# Получение городов
		$items_city = $table->find(['select' => 'count(*) as cnt'])->fetch();

		# Проверка импорта
		if ($items_city['cnt'] <= 0) {
			$data['filled_city'] = 'Для работы модуля необходимо выполнить процедуру синхронизации!';
		}

		# Наличие подключения
		$data['filled'] = \Ipol\DPD\API\User\User::isActiveAccount($config);

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] 	 = $this->load->controller('common/footer');

		ini_set('default_socket_timeout', 600);

		$checker = \Ipol\DPD\SettingChecker::getInstance();

		$data['setting_check_err']  = !$checker->itOkay();
		$data['setting_check_html'] = $checker->output();

		// $this->model_setting_setting->deleteSetting('dpd_setting_checker');
		// die;

		$data['show_setting_checker'] = !$this->model_setting_setting->getSettingValue('dpd_setting_checker_show_setting_checker');
		$data['show_faq_notice']      = !$this->model_setting_setting->getSettingValue('dpd_setting_checker_show_faq_notice');

		$this->model_setting_setting->editSetting('dpd_setting_checker', [
			'dpd_setting_checker_show_setting_checker' => 1,
			'dpd_setting_checker_show_setting_checker' => 1,
		]);		

		$this->response->setOutput($this->load->view('extension/shipping/dpd', $data));
	}

	public function matchRegions(){
		$this->load->model('extension/shipping/dpd');
		$json = array();

		$regions = $this->model_extension_shipping_dpd->getRegions();

		if($regions){
			foreach($regions as $row){
				$code = $this->model_extension_shipping_dpd->getRegion($row['REGION_NAME']);
				$this->model_extension_shipping_dpd->updateRegions($row['REGION_NAME'], $code);
			}

			$json['success'] = 'Сопоставление успешно завершено!';
		}else{
			$json['error'] = 'Сначала воспроизведите импорт населенных пунктов!';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Import loadAll
	public function loadAll(){
		$json = array();

		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);

		try{
			$config  = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
			$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);

			if(!isset($this->session->data['LoadAll'])){
				$this->session->data['LoadAll'][0] = 0;
				$this->session->data['LoadAll'][1] = 9000000;
			}

			while($this->session->data['LoadAll'] !== true){
				$this->session->data['LoadAll'] = $loader->LoadAll($this->session->data['LoadAll'][0]);

				$this->response->redirect($this->url->link('extension/shipping/dpd/LoadAll', $this->token . '&type=shipping', true));
			}

			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Import loadCashPay
	public function loadCashPay(){
		$json = array();

		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);

		try{
			$config  = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
			$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);

			if(!isset($this->session->data['loadCashPay'])){
				$this->session->data['loadCashPay'][0] = 0;
				$this->session->data['loadCashPay'][1] = 9000000;
			}

			while($this->session->data['loadCashPay'] !== true){
				$this->session->data['loadCashPay'] = $loader->loadCashPay($this->session->data['loadCashPay'][0]);
				$this->response->redirect($this->url->link('extension/shipping/dpd/loadCashPay', $this->token . '&type=shipping', true));
			}

			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Import loadUnlimited
	public function loadUnlimited(){
		$json = array();

		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);

		$start = microtime(true);

		$config  = $this->getOpencartConfig();
		$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
		$loader->loadUnlimited();

		$json['success'] = round(microtime(true) - $start, 0);
		$json['success'] = $json['success'].'00';

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Import loadLimited
	public function loadLimited(){
		$json = array();

		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);

		try{
			$config  = $this->getOpencartConfig();
			$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
			$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);

			if(!isset($this->session->data['loadLimited'])){
				$this->session->data['loadLimited'][0] = 0;
				$this->session->data['loadLimited'][1] = 9000000;
			}

			while($this->session->data['loadLimited'] !== true){
				$this->session->data['loadLimited'] = $loader->loadLimited($this->session->data['loadLimited'][0]);
				$this->response->redirect($this->url->link('extension/shipping/dpd/loadLimited', $this->token . '&type=shipping', true));
			}

			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Unset
	public function unsetImport(){
		unset($this->session->data['LoadAll']);
		unset($this->session->data['loadCashPay']);
		unset($this->session->data['loadLimited']);
	}

	# Выгрузка сдк
	public function getLoadLocation(){
		ini_set('default_socket_timeout', 600);
		$config  = $this->getOpencartConfig();
		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
		$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);
		$loader->loadAll();
		$loader->loadCashPay();
	}

	# Выгрузка сдк
	public function getLoadTerminal(){
		ini_set('default_socket_timeout', 600);
		$config  = $this->getOpencartConfig();
		$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);

		$loader->loadUnlimited();
		$loader->loadLimited();
	}

	# Список терминалов
	public function getTerminals(){
		$json = array();

		if(isset($this->request->post['city_id'])){
			$config  = $this->getOpencartConfig();
			$terminalTable  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');

			$json['items'] = $terminalTable->find([
				'where' => 'LOCATION_ID = "' . $this->request->post['city_id'] . '" AND SCHEDULE_SELF_PICKUP != "" ORDER BY NAME',
			])->fetchAll();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Список dpd доставок
	public function getTariffList(){

		$config  = $this->getOpencartConfig();
		$shipment = new \Ipol\DPD\Shipment($config);
		$tariffs = $shipment->calculator()->TariffList();

		return $tariffs;
	}

	# Получение списка оплат(вкл)
	public function getPayment($code){
		$this->load->language('extension/payment/' . $code);

		$title = $this->language->get('heading_title');

		return $title;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {

			$config = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');

			$results = $table->find([
				'where' => 'CITY_NAME LIKE :name',
				'limit' => '0,5',
				'order'	=> 'IS_CITY DESC,COUNTRY_NAME DESC',
				'bind'  => [
					':name' => '%' . mb_strtolower($this->request->get['filter_name']) . '%'
				]
			])->fetchAll();

			foreach ($results as $result) {

				if($result['CITY_ABBR'] == 'Город'){
					$result['CITY_ABBR'] = 'г.';
				}else{
					$result['CITY_ABBR'] = $result['CITY_ABBR'] . '.';
				}

				$json[] = array(
					'city_id' => $result['CITY_ID'],
					'value'   => $result['CITY_NAME'],
					'name'    => strip_tags(html_entity_decode($result['ORIG_NAME'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Подключение
	public function getOpencartConfig (){
		require DIR_SYSTEM . 'dpd/src/autoload.php';

		$options = array(
			'KLIENT_NUMBER'   => $this->config->get($this->code . '_russian_number'),
			'KLIENT_KEY'      => $this->config->get($this->code . '_russian_auth'),
			'KLIENT_CURRENCY' => $this->config->get($this->code . '_russian_currency'),
			'API_DEF_COUNTRY' => $this->config->get($this->code . '_account_default') ? $this->config->get($this->code . '_account_default') : 'RU',
			'IS_TEST'         => $this->config->get($this->code . '_test') ? true : false,
			'KLIENT_NUMBER_KZ' => $this->config->get($this->code . '_kazahstan_number'),
			'KLIENT_KEY_KZ'   => $this->config->get($this->code . '_kazahstan_auth'),
			'KLIENT_CURRENCY_KZ' => $this->config->get($this->code . '_kazahstan_currency'),
			'KLIENT_NUMBER_BY' => $this->config->get($this->code . '_belarus_number'),
			'KLIENT_KEY_BY'   => $this->config->get($this->code . '_belarus_auth'),
			'KLIENT_CURRENCY_BY' => $this->config->get($this->code . '_belarus_currency'),
		);

		$options = array_merge($options, [
			'DB' => [
				'DSN'      => 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE,
				'USERNAME' => DB_USERNAME,
				'PASSWORD' => DB_PASSWORD,
				'DRIVER'   => null,
				'PDO'      => null,
			]
		]);

		$config  = new \Ipol\DPD\Config\Config($options);

		return $config;
	}

	# Проверка формы
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/dpd')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!file_exists(DIR_SYSTEM . 'dpd/src/autoload.php')) {
				$this->error['file_exists'] = $this->language->get('error_file_exists');
		}

		if ($this->request->post[$this->code . '_account_default'] == 'RU'){

			if ((utf8_strlen($this->request->post[$this->code . '_russian_number']) < 1) || (utf8_strlen($this->request->post[$this->code . '_russian_number']) > 40)) {
				$this->error['russian_number'] = $this->language->get('error_russian_number');
			}

			if ((utf8_strlen($this->request->post[$this->code . '_russian_auth']) < 1) || (utf8_strlen($this->request->post[$this->code . '_russian_auth']) > 40)) {
				$this->error['russian_auth'] = $this->language->get('error_russian_auth');
			}
		}elseif($this->request->post[$this->code . '_account_default'] == 'KZ'){
			if ((utf8_strlen($this->request->post[$this->code . '_kazahstan_number']) < 1) || (utf8_strlen($this->request->post[$this->code . '_kazahstan_number']) > 40)) {
				$this->error['kazahstan_number'] = $this->language->get('error_russian_number');
			}

			if ((utf8_strlen($this->request->post[$this->code . '_kazahstan_auth']) < 1) || (utf8_strlen($this->request->post[$this->code . '_kazahstan_auth']) > 40)) {
				$this->error['kazahstan_auth'] = $this->language->get('error_russian_auth');
			}
		}elseif($this->request->post[$this->code . '_account_default'] == 'BY'){
			if ((utf8_strlen($this->request->post[$this->code . '_belarus_number']) < 1) || (utf8_strlen($this->request->post[$this->code . '_belarus_number']) > 40)) {
				$this->error['belarus_number'] = $this->language->get('error_russian_number');
			}

			if ((utf8_strlen($this->request->post[$this->code . '_belarus_auth']) < 1) || (utf8_strlen($this->request->post[$this->code . '_belarus_auth']) > 40)) {
				$this->error['belarus_auth'] = $this->language->get('error_russian_auth');
			}
		}

		if ((utf8_strlen($this->request->post[$this->code . '_weight']) < 1)) {
			$this->error['weight'] = $this->language->get('error_weight');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_length']) < 1)) {
			$this->error['length'] = $this->language->get('error_length');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_width']) < 1)) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_height']) < 1)) {
			$this->error['height'] = $this->language->get('error_height');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_content_sender']) < 1)) {
			$this->error['content'] = $this->language->get('error_content');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_name_door']) < 1)) {
			$this->error['name_door'] = $this->language->get('error_name_door');
		}

		if ((utf8_strlen($this->request->post[$this->code . '_name_terminal']) < 1)) {
			$this->error['name_terminal'] = $this->language->get('error_name_terminal');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function install() {
		$this->db->query("create table IF NOT EXISTS b_ipol_dpd_order (
			ID int not null auto_increment,

			ORDER_ID varchar(255) null,
			SHIPMENT_ID int null,

			ORDER_DATE varchar(20) null,
			ORDER_DATE_CREATE varchar(20) null,
			ORDER_DATE_CANCEL varchar(20) null,
			ORDER_DATE_STATUS varchar(20) null,
			ORDER_NUM varchar(15) null,
			ORDER_STATUS text null,
			ORDER_STATUS_CANCEL text null,
			ORDER_ERROR text null,
			
			SERVICE_CODE char(3) null,
			SERVICE_VARIANT char(2) null,

			PICKUP_DATE varchar(20) null,
			PICKUP_TIME_PERIOD char(5) null,
			DELIVERY_TIME_PERIOD char(5) null,

			DIMENSION_WIDTH  double not null default '0',
			DIMENSION_HEIGHT double not null default '0',
			DIMENSION_LENGTH double not null default '0',
			CARGO_VOLUME double not null default '0',
			CARGO_WEIGHT double not null default '0',

			CARGO_NUM_PACK double null,
			CARGO_CATEGORY varchar(255) null,

			SENDER_FIO varchar(255) null,
			SENDER_NAME varchar(255) null,
			SENDER_PHONE varchar(20) null,
			SENDER_LOCATION varchar(255) not null,
			SENDER_STREET varchar(50) null,
			SENDER_STREETABBR varchar(10) null,
			SENDER_HOUSE varchar(10) null,
			SENDER_KORPUS varchar(10) null,
			SENDER_STR varchar(10) null,
			SENDER_VLAD varchar(10) null,	
			SENDER_OFFICE varchar(10) null,
			SENDER_FLAT varchar(10) null,
			SENDER_TERMINAL_CODE char(4) null,
			
			RECEIVER_FIO varchar(255) null,
			RECEIVER_NAME varchar(255) null,
			RECEIVER_PHONE varchar(20) null,
			RECEIVER_LOCATION varchar(255) not null,
			RECEIVER_STREET varchar(50) null,
			RECEIVER_STREETABBR varchar(10) null,
			RECEIVER_HOUSE varchar(10) null,
			RECEIVER_KORPUS varchar(10) null,
			RECEIVER_STR varchar(10) null,
			RECEIVER_VLAD varchar(10) null,	
			RECEIVER_OFFICE varchar(10) null,
			RECEIVER_FLAT varchar(10) null,
			RECEIVER_TERMINAL_CODE char(4) null,
			RECEIVER_COMMENT text null,

			PRICE DOUBLE NULL,
			PRICE_DELIVERY DOUBLE NULL,
			CARGO_VALUE double null,
			NPP char(1) not null default 'N',
			SUM_NPP DOUBLE NULL,
			
			CARGO_REGISTERED char(1) not null default 'N',
			SMS varchar(25) null,
			EML varchar(50) null,
			ESD varchar(50) null,
			ESZ char(50) null,
			OGD char(4) null,
			DVD char(1) not null default 'N',
			VDO char(1) not null default 'N',
			POD varchar(50) null,
			PRD char(1) not null default 'N',
			TRM char(1) not null default 'N',
            CHST char(4) null,

			LABEL_FILE varchar(255) null,
			INVOICE_FILE varchar(255) null,
			
			ORDER_ITEMS text null,
			PAY_SYSTEM_ID int null,
			PERSONE_TYPE_ID int null,
			CURRENCY varchar(255) null,

			PAYMENT_TYPE varchar(255) null,
			SENDER_EMAIL varchar(50) DEFAULT NULL,
			RECEIVER_EMAIL varchar(50) DEFAULT NULL,
			SENDER_NEED_PASS char(1) DEFAULT 'N',
			RECEIVER_NEED_PASS char(1) DEFAULT 'N',

			UNIT_LOADS text null,
			USE_CARGO_VALUE char(1) not null default 'N',
			USE_MARKING char(1) not null default 'N',

			primary key (ID)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `b_ipol_dpd_order`");
	}

}
