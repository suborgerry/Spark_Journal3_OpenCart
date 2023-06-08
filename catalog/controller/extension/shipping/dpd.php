<?php
class ControllerExtensionShippingDpd extends Controller {
    private $code;
    private $model_setting_extension_path;
    private $model_setting_extension_method;

    public function __construct($registry)
    {
        parent::__construct($registry);
        // Module Constants
        if (VERSION >= '3.0.0.0') {
            $this->code = 'shipping_dpd';
            $this->model_setting_extension_path = 'setting/extension';
            $this->model_setting_extension_method = 'model_setting_extension';
        } elseif (VERSION >= '2.3.0.0') {
            $this->code = 'dpd';
            $this->model_setting_extension_path = 'extension/extension';
            $this->model_setting_extension_method = 'model_extension_extension';
        }
    }

	# Подключение
	public function getOpencartConfig (){
		require DIR_SYSTEM . 'dpd/src/autoload.php';

		$options = array(
			'SOURCE_NAME'	  => 'OpenCart',
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
			'DEFAULT_PRICE'					=> $this->config->get($this->code . '_default_price'),
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

	public function autocomplete() {
		$this->load->model('extension/shipping/dpd');

		$json = array();

		if (isset($this->request->get['filter_name'])) {

			$config = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');

			$results = $table->find([
				'where' => 'CITY_NAME LIKE :name',
				'limit' => '0,5',
				'order'	=> 'IS_CITY DESC,COUNTRY_NAME DESC',
				'bind'  => [
					'name' => '%' . mb_strtolower($this->request->get['filter_name']) . '%'
				]
			])->fetchAll();

			foreach ($results as $result) {

				if($result['CITY_ABBR'] == 'Город'){
					$result['CITY_ABBR'] = 'г.';
				}else{
					$result['CITY_ABBR'] = $result['CITY_ABBR'] . '.';
				}

				$region = array();

				if($result['REGION_CODE']){
					$region = $this->model_extension_shipping_dpd->getZone($result['REGION_CODE']);
				}

				$json[] = array(
					'city_id'	 => $result['CITY_ID'],
					'value'  	 => $result['CITY_NAME'],
					'country_id' => $region ? $region['country_id'] : '',
					'zone_id' 	=> $region ? $region['zone_id'] : '',
					'name'    	=> strip_tags(html_entity_decode($result['ORIG_NAME'], ENT_QUOTES, 'UTF-8'))
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

	public function save(){
		$json = array();

		if(isset($this->request->post['city_id'])){
			$this->session->data['dpd']['city_id'] = $this->request->post['city_id'];
			unset($this->session->data['dpd']['address']);
			unset($this->session->data['dpd']['id']);
		}

		if(isset($this->request->post['country_id'])){
			if(!empty($this->request->post['country_id'])){
				$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
			}
		}

		if(isset($this->request->post['zone_id'])){
			if(!empty($this->request->post['zone_id'])){
				$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
			}
		}

		if(isset($this->request->post['address_off'])){
			unset($this->session->data['dpd']['address']);
			unset($this->session->data['dpd']['id']);
		}

		if(isset($this->request->post['address'])){
			$this->session->data['dpd']['address'] = $this->request->post['address'];
		}

		if(isset($this->request->post['id'])){
			$this->session->data['dpd']['id'] = $this->request->post['id'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	# Проверка оплат
	public function checkPayment(){
		$json = array();

		$this->load->model('extension/shipping/dpd');

		if(isset($this->session->data['guest'])){
			$personeTypeId = $this->session->data['guest']['customer_group_id'];
		}elseif(isset($this->session->data['customer_id'])){
			$personeTypeId = $this->model_extension_shipping_dpd->selectCustomerGroup($this->session->data['customer_id']);
		}else{
			$personeTypeId = $this->config->get('config_customer_group_id');
		}

		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId) && $this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
			if(isset($this->request->post['payment_method'])){
				if($this->session->data['payment_method']['code'] !== $this->request->post['payment_method']){
					foreach($this->config->get($this->code . '_bind_payment_' . $personeTypeId) as $mthd){
						if($mthd == $this->session->data['payment_method']['code']){
							$before = true;
						}

						if($mthd == $this->request->post['payment_method']){
							$after = true;
						}

					}

					if(!isset($before) or !isset($after)){
						$this->session->data['payment_method']['code'] = $this->request->post['payment_method'];
						$json['success'] = 'yes';
						$json['shipping'] = $this->session->data['shipping_method']['code'];

						$shipment = $this->getTerminals2();

						if(isset($this->session->data['dpd']['id'])){
							$config  = $this->getOpencartConfig();

							$items = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal')->findModels([
								'where' => 'CODE = "' . $this->session->data['dpd']['id'] . '"',
							]);

							$items = array_filter($items, function($terminal) use ($shipment) {
								return $terminal->checkShipment($shipment);
							});

							$items = array_values($items);

							if(empty($items)){
								$json['message'] = true;
								unset($this->session->data['dpd']['address']);
								unset($this->session->data['dpd']['id']);
							}
						}
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function getTerminals2(){

		$json = array();

		$this->load->model('extension/shipping/dpd');
		require DIR_SYSTEM . 'dpd/src/autoload.php';

		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		if(isset($this->session->data['guest'])){
			$personeTypeId = $this->session->data['guest']['customer_group_id'];
		}elseif(isset($this->session->data['customer_id'])){
			$personeTypeId = $this->model_extension_shipping_dpd->selectCustomerGroup($this->session->data['customer_id']);
		}else{
			$personeTypeId = $this->config->get('config_customer_group_id');
		}

		// Платёжная система
		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
				if(is_array($this->config->get($this->code . '_bind_payment_' . $personeTypeId))){
					$i = 0;
					foreach($this->config->get($this->code . '_bind_payment_' . $personeTypeId) as $key => $mthd){
						$COMMISSION_NPP_PAYMENT[$personeTypeId][$i] = $mthd;
						$i++;
					}
				}else{
					if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
						$COMMISSION_NPP_PAYMENT[$personeTypeId][] = $this->config->get($this->code . '_bind_payment_' . $personeTypeId);
					}
				}
			}
		}else{
			$COMMISSION_NPP_PAYMENT[$personeTypeId][] = array();
		}

		if($this->config->get($this->code . '_comission_for_collection_'.$personeTypeId)){
			$COMMISSION_NPP_CHECK = [
				$personeTypeId => $this->config->get($this->code . '_comission_for_collection_'.$personeTypeId) ? true : false,
			];
		}else{
			$COMMISSION_NPP_CHECK = [];
		}

		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			$COMMISSION_NPP_PERCENT = [
				$personeTypeId => $this->config->get($this->code . '_comission_for_product_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_PERCENT = [];
		}

		if($this->config->get($this->code . '_min_sum_comission_'.$personeTypeId)){
			$COMMISSION_NPP_MINSUM = [
				$personeTypeId => $this->config->get($this->code . '_min_sum_comission_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_MINSUM = [];
		}

		if($this->config->get($this->code . '_not_payment_'.$personeTypeId)){
			$COMMISSION_NPP_DEFAULT = [
				$personeTypeId => $this->config->get($this->code . '_not_payment_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_DEFAULT = [];
		}

		$options = array(
			'KLIENT_NUMBER'   				=> $this->config->get($this->code . '_russian_number'),
			'KLIENT_KEY'      				=> $this->config->get($this->code . '_russian_auth'),
			'KLIENT_CURRENCY' 				=> $this->config->get($this->code . '_russian_currency'),
			'API_DEF_COUNTRY' 				=> $this->config->get($this->code . '_account_default') ? $this->config->get($this->code . '_account_default') : 'RU',
			'IS_TEST'         				=> $this->config->get($this->code . '_test') ? true : false,
			'KLIENT_NUMBER_KZ' 				=> $this->config->get($this->code . '_kazahstan_number'),
			'KLIENT_KEY_KZ'  				=> $this->config->get($this->code . '_kazahstan_auth'),
			'KLIENT_CURRENCY_KZ' 			=> $this->config->get($this->code . '_kazahstan_currency'),
			'KLIENT_NUMBER_BY' 				=> $this->config->get($this->code . '_belarus_number'),
			'KLIENT_KEY_BY'   				=> $this->config->get($this->code . '_belarus_auth'),
			'KLIENT_CURRENCY_BY' 			=> $this->config->get($this->code . '_belarus_currency'),
			'WEIGHT' 						=> $this->config->get($this->code . '_weight'),
			'LENGTH'						=> $this->config->get($this->code . '_length'),
			'WIDTH'  						=> $this->config->get($this->code . '_width'),
			'HEIGHT' 						=> $this->config->get($this->code . '_height'),
			'TARIFF_OFF' 					=> $this->config->get($this->code . '_not_calculate'),
			'DEFAULT_TARIFF_CODE'			=> $this->config->get($this->code . '_tariff_default'),
			'DEFAULT_TARIFF_THRESHOLD'		=> $this->config->get($this->code . '_max_for_default'),
			'DECLARED_VALUE'				=> $this->config->get($this->code . '_cart_equally_product') ? true : false,
			'DEFAULT_PRICE'					=> $this->config->get($this->code . '_default_price'),
			'COMMISSION_NPP_CHECK'      	=> $COMMISSION_NPP_CHECK,
			'COMMISSION_NPP_PERCENT'      	=> $COMMISSION_NPP_PERCENT,
			'COMMISSION_NPP_MINSUM'      	=> $COMMISSION_NPP_MINSUM,
			'COMMISSION_NPP_PAYMENT'		=> isset($COMMISSION_NPP_PAYMENT) ? $COMMISSION_NPP_PAYMENT : array(),
			'COMMISSION_NPP_DEFAULT'		=> $COMMISSION_NPP_DEFAULT,
			'CURRENCY'                      => [
				'RU' => $this->config->get($shipping_dpd . '_russian_currency'),
				'BY' => $this->config->get($shipping_dpd . '_belarus_currency'),
				'KZ' => $this->config->get($shipping_dpd . '_kazahstan_currency'),
				'KG' => $this->config->get($shipping_dpd . '_kirgizstan_currency'),
				'AM' => $this->config->get($shipping_dpd . '_armenia_currency'),
			],
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
		$shipment = new \Ipol\DPD\Shipment($config);

		$converter = new \Ipol\DPD\Currency\Converter($this->currency);
		
		$shipment->setCurrencyConverter($converter);
		$shipment->setCurrency($this->session->data['currency']);

		if($this->config->get($this->code . '_departure_method')){
			$shipment->setSelfPickup(true);
			$params['SelfPickup'] = true;
		}else{
			$shipment->setSelfPickup(false);
			$params['SelfPickup'] = false;
		}

		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');

		$params_address = array();

		if($this->config->get($this->code . '_address_sender')){
			foreach($this->config->get($this->code . '_address_sender') as $address){
				if(isset($address['default'])){
					$params_address = $address;
				}
			}
		}

		$sender = array();

		if(!empty($params_address)){
			$sender = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					'city_id' => $params_address['city_id']
				]
			])->fetchAll();
		}

		$receiver = $table->getByAddress(
			str_replace('Российская Федерация', 'Россия', $this->session->data['shipping_address']['country']),
			$this->session->data['shipping_address']['zone'],
			$this->session->data['shipping_address']['city']
		);

		if (!$receiver) {
			if(isset($this->session->data['dpd']['city_id'])){
				$receiver = $table->find([
					'where' => 'CITY_ID = :city_id',
					'limit' => '0,1',
					'bind'  => [
						'city_id' => $this->session->data['dpd']['city_id']
					]
				])->fetchAll();
			}else{
				$receiver = $table->find([
					'where' => 'CITY_NAME = :city_name',
					'limit' => '0,1',
					'bind'  => [
						'city_name' => $this->session->data['shipping_address']['city']
					]
				])->fetchAll();
			}

			$receiver = $receiver ? $receiver[0] : [];
		}

		// Указываем города отправления и назначения
		if(!empty($receiver)){
			$shipment->setReceiver($receiver['COUNTRY_NAME'], $receiver['REGION_NAME'], $receiver['CITY_NAME']);
			$params['receiver'] = $receiver['COUNTRY_NAME'] . $receiver['REGION_NAME'] . $receiver['CITY_NAME'];
		}else{
			$shipment->setReceiver('empty', 'empty', 'empty');
			$params['receiver'] = 'empty';
		}

		$shipment->setSender($sender[0]['COUNTRY_NAME'], $sender[0]['REGION_NAME'], $sender[0]['CITY_NAME']);
		$params['sender'] = $sender[0]['COUNTRY_NAME'] . $sender[0]['REGION_NAME'] . $sender[0]['CITY_NAME'];

		// Страховка
		if($this->config->get($this->code . '_cart_equally_product')){
			$shipment->setDeclaredValue(true);
		}

		$params['DeclaredValue'] = $shipment->getDeclaredValue();

		// Итоговая цена
		$productsPrice = 0;

		// список товаров входящих в отправку
		$products = $this->cart->getProducts();

		foreach ($products as $key => $product) {
			if($product['length'] > 0){
				$length = $this->length->convert($product['length'], $product['length_class_id'], 2);
			}else{
				$length = $this->config->get($this->code . '_length');
			}

			if($product['width'] > 0){
				$width = $this->length->convert($product['width'], $product['length_class_id'], 2);
			}else{
				$width = $this->config->get($this->code . '_width');
			}

			if($product['height'] > 0){
				$height = $this->length->convert($product['height'], $product['length_class_id'], 2);
			}else{
				$height = $this->config->get($this->code . '_height');
			}

			if($product['weight'] > 0){
				$weight = $this->weight->convert($product['weight'], $product['weight_class_id'], 2);
			}else{
				$weight = $this->config->get($this->code . '_weight');
			}

			$currency_price = $this->currency->format($this->tax->calculate($product['price'], 0, $this->config->get('config_tax')), $this->session->data['currency']);
			$currency_price = str_ireplace(" ", "", $currency_price);
			$currency_price = preg_replace('/[^0-9 , .]/', '', $currency_price);
			$price = (float)$currency_price;

			$data['products'][] = array(
				'NAME'     => $product['name'],
				'QUANTITY' => $product['quantity'], // кол-во
				'PRICE'    => $price, // стоимость за единицу
				'VAT_RATE' => 0, // ставка налога, процент или строка Без НДС
				'WEIGHT'   => $weight/$product['quantity'], // вес, граммы,
				'DIMENSIONS' => [
				'LENGTH' => $length, // длина, мм,
				'WIDTH'  => $width, // ширина, мм,
				'HEIGHT' => $height, // высота, мм,
				]
			);
			$productsPrice += ($price*$product['quantity']);
		}

		$shipment->setItems($data['products'], $productsPrice);

		$params['price'] = $shipment->getPrice();

		$params['Width'] =  $shipment->getWidth();
		$params['Height'] =  $shipment->getHeight();
		$params['Length'] =  $shipment->getLength();
		$params['Weight'] =  $shipment->getWeight();

		// Платёжная система
		$payment_dt = array();

		$total = $this->cart->getTotal();

		$this->load->model($this->model_setting_extension_path);

		$results_pay = $this->{$this->model_setting_extension_method}->getExtensions('payment');

		$recurring = $this->cart->hasRecurringProducts();

		foreach ($results_pay as $result) {
            if (VERSION >= '3.0.0.0') {
                $code = 'payment_' . $result['code'];
            } else {
                $code = $result['code'];
            }

            if ($this->config->get($code . '_status')) {
				$this->load->model('extension/payment/' . $result['code']);

				$method_pay = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

				if ($method_pay) {
					if ($recurring) {
						if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
							$payment_dt[$result['code']] = $method_pay;
						}
					} else {
						$payment_dt[$result['code']] = $method_pay;
					}
				}
			}
		}

		$sort_order_pay = array();

		foreach ($payment_dt as $key => $value) {
			$sort_order_pay[$key] = $value['sort_order'];
		}

		array_multisort($sort_order_pay, SORT_ASC, $payment_dt);

		$first_payment = array_shift($payment_dt);

		if(!isset($this->session->data['payment_method']['code'])){
			$this->session->data['payment_method']['code']  = $first_payment['code'];
		}

		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			if(isset($this->request->post['payment_method'])){
				$payment_code = $this->request->post['payment_method'];
			}elseif(isset($this->session->data['payment_method']['code'])){
				$payment_code = $this->session->data['payment_method']['code'];
			}
			if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
				if(is_array($this->config->get($this->code . '_bind_payment_' . $personeTypeId))){
					foreach($this->config->get($this->code . '_bind_payment_' . $personeTypeId) as $mthd){
						if($mthd == $payment_code){
							$paySystemId = $payment_code;
							$shipment->setPaymentMethod($personeTypeId, $paySystemId);
						}
					}
				}else{
					if($this->config->get($this->code . '_bind_payment_' . $personeTypeId) == $payment_code){
						$paySystemId = $payment_code;
						$shipment->setPaymentMethod($personeTypeId, $paySystemId);
					}
				}
			}
		}

		# Наложенный платёж
		$params['npp_payment'] = $shipment->isPaymentOnDelivery();

		$converter =  new \Ipol\DPD\Currency\Converter($this->currency);

		$shipment->setCurrencyConverter($converter);
		$shipment->setCurrency($this->session->data['currency']);

		// Получаем калькулятор
		$calc = $shipment->calculator();
		// $calc->setCurrencyConverter($converter);

		$params['currencyTo'] = $options['KLIENT_CURRENCY'];
		$params['currencyFrom'] = $this->session->data['currency'];

		try{
			$params['SelfDelivery'] = true;
			$shipment->setSelfDelivery(true);
			$cache_terminal = 'dpd.shipping.calculateTerminal.' . md5(implode('', $params));
			if (! $json['tariffs']['pickup'] = $this->cache->get($cache_terminal)) {
				$json['tariffs']['pickup'] = $calc->calculate($params['currencyFrom']);
				$this->cache->set($cache_terminal, $json['tariffs']['pickup']);
			}

			$params['SelfDelivery'] = false;
			$shipment->setSelfDelivery(false);
			$cache_door = 'dpd.shipping.calculateDoor.' . md5(implode('', $params));
			if (! $json['tariffs']['courier'] = $this->cache->get($cache_door)) {
				$json['tariffs']['courier'] = $calc->calculate($params['currencyFrom']);
				$this->cache->set($cache_door, $json['tariffs']['courier']);
			}


			if($this->config->get($this->code . '_wait_address') != 0){
			$ogd = '|ОЖД_'.$this->config->get($this->code . '_wait_address').'|';
			}else{
				$ogd = '';
			}

			$json['terminals'] = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal')->findModels([
				'where' => 'LOCATION_ID = :location AND SERVICES LIKE :ogd',
				'bind'  =>
				[
				'location' => $shipment->getReceiver()['CITY_ID'],
				'ogd' => '%'.$ogd.'%'
				],

			]);

			$json['terminals'] = array_filter($json['terminals'], function($terminal) use ($shipment) {
				return $terminal->checkShipment($shipment);
			});

			$json['terminals'] = array_values($json['terminals']);

		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}

		return $shipment;
	}

	public function getTerminals(){

		$json = array();

		$this->load->model('extension/shipping/dpd');
		require DIR_SYSTEM . 'dpd/src/autoload.php';

		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		if(isset($this->session->data['guest'])){
			$personeTypeId = $this->session->data['guest']['customer_group_id'];
		}elseif(isset($this->session->data['customer_id'])){
			$personeTypeId = $this->model_extension_shipping_dpd->selectCustomerGroup($this->session->data['customer_id']);
		}else{
			$personeTypeId = $this->config->get('config_customer_group_id');
		}

		// Платёжная система
		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
				if(is_array($this->config->get($this->code . '_bind_payment_' . $personeTypeId))){
					$i = 0;
					foreach($this->config->get($this->code . '_bind_payment_' . $personeTypeId) as $key => $mthd){
						$COMMISSION_NPP_PAYMENT[$personeTypeId][$i] = $mthd;
						$i++;
					}
				}else{
					if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
						$COMMISSION_NPP_PAYMENT[$personeTypeId][] = $this->config->get($this->code . '_bind_payment_' . $personeTypeId);
					}
				}
			}
		}else{
			$COMMISSION_NPP_PAYMENT[$personeTypeId][] = array();
		}

		if($this->config->get($this->code . '_comission_for_collection_'.$personeTypeId)){
			$COMMISSION_NPP_CHECK = [
				$personeTypeId => $this->config->get($this->code . '_comission_for_collection_'.$personeTypeId) ? true : false,
			];
		}else{
			$COMMISSION_NPP_CHECK = [];
		}

		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			$COMMISSION_NPP_PERCENT = [
				$personeTypeId => $this->config->get($this->code . '_comission_for_product_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_PERCENT = [];
		}

		if($this->config->get($this->code . '_min_sum_comission_'.$personeTypeId)){
			$COMMISSION_NPP_MINSUM = [
				$personeTypeId => $this->config->get($this->code . '_min_sum_comission_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_MINSUM = [];
		}

		if($this->config->get($this->code . '_not_payment_'.$personeTypeId)){
			$COMMISSION_NPP_DEFAULT = [
				$personeTypeId => $this->config->get($this->code . '_not_payment_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_DEFAULT = [];
		}

		$options = array(
			'KLIENT_NUMBER'   				=> $this->config->get($this->code . '_russian_number'),
			'KLIENT_KEY'      				=> $this->config->get($this->code . '_russian_auth'),
			'KLIENT_CURRENCY' 				=> $this->config->get($this->code . '_russian_currency'),
			'API_DEF_COUNTRY' 				=> $this->config->get($this->code . '_account_default') ? $this->config->get($this->code . '_account_default') : 'RU',
			'IS_TEST'         				=> $this->config->get($this->code . '_test') ? true : false,
			'KLIENT_NUMBER_KZ' 				=> $this->config->get($this->code . '_kazahstan_number'),
			'KLIENT_KEY_KZ'  				=> $this->config->get($this->code . '_kazahstan_auth'),
			'KLIENT_CURRENCY_KZ' 			=> $this->config->get($this->code . '_kazahstan_currency'),
			'KLIENT_NUMBER_BY' 				=> $this->config->get($this->code . '_belarus_number'),
			'KLIENT_KEY_BY'   				=> $this->config->get($this->code . '_belarus_auth'),
			'KLIENT_CURRENCY_BY' 			=> $this->config->get($this->code . '_belarus_currency'),
			'WEIGHT' 						=> $this->config->get($this->code . '_weight'),
			'LENGTH'						=> $this->config->get($this->code . '_length'),
			'WIDTH'  						=> $this->config->get($this->code . '_width'),
			'HEIGHT' 						=> $this->config->get($this->code . '_height'),
			'TARIFF_OFF' 					=> $this->config->get($this->code . '_not_calculate'),
			'DEFAULT_TARIFF_CODE'			=> $this->config->get($this->code . '_tariff_default'),
			'DEFAULT_TARIFF_THRESHOLD'		=> $this->config->get($this->code . '_max_for_default'),
			'DECLARED_VALUE'				=> $this->config->get($this->code . '_cart_equally_product') ? true : false,
			'DEFAULT_PRICE'					=> $this->config->get($this->code . '_default_price'),
			'COMMISSION_NPP_CHECK'      	=> $COMMISSION_NPP_CHECK,
			'COMMISSION_NPP_PERCENT'      	=> $COMMISSION_NPP_PERCENT,
			'COMMISSION_NPP_MINSUM'      	=> $COMMISSION_NPP_MINSUM,
			'COMMISSION_NPP_PAYMENT'		=> isset($COMMISSION_NPP_PAYMENT) ? $COMMISSION_NPP_PAYMENT : array(),
			'COMMISSION_NPP_DEFAULT'		=> $COMMISSION_NPP_DEFAULT,
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
		$shipment = new \Ipol\DPD\Shipment($config);

		if($this->config->get($this->code . '_departure_method')){
			$shipment->setSelfPickup(true);
			$params['SelfPickup'] = true;
		}else{
			$shipment->setSelfPickup(false);
			$params['SelfPickup'] = false;
		}

		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');

		$params_address = array();

		if($this->config->get($this->code . '_address_sender')){
			foreach($this->config->get($this->code . '_address_sender') as $address){
				if(isset($address['default'])){
					$params_address = $address;
				}
			}
		}

		$sender = array();

		if(!empty($params_address)){
			$sender = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					'city_id' => $params_address['city_id']
				]
			])->fetchAll();
		}

		$receiver = $table->getByAddress(
			str_replace('Российская Федерация', 'Россия', $this->session->data['shipping_address']['country']),
			$this->session->data['shipping_address']['zone'],
			$this->session->data['shipping_address']['city']
		);

		if (!$receiver) {
			if(isset($this->session->data['dpd']['city_id'])){
				$receiver = $table->find([
					'where' => 'CITY_ID = :city_id',
					'limit' => '0,1',
					'bind'  => [
						'city_id' => $this->session->data['dpd']['city_id']
					]
				])->fetchAll();
			}else{
				$receiver = $table->find([
					'where' => 'CITY_NAME = :city_name',
					'limit' => '0,1',
					'bind'  => [
						'city_name' => $this->session->data['shipping_address']['city']
					]
				])->fetchAll();
			}

			$receiver = $receiver ? $receiver[0] : [];
		}

		// Указываем города отправления и назначения
		if(!empty($receiver)){
			$shipment->setReceiver($receiver['COUNTRY_NAME'], $receiver['REGION_NAME'], $receiver['CITY_NAME']);
			$params['receiver'] = $receiver['COUNTRY_NAME'] . $receiver['REGION_NAME'] . $receiver['CITY_NAME'];
		}else{
			$shipment->setReceiver('empty', 'empty', 'empty');
			$params['receiver'] = 'empty';
		}

		$shipment->setSender($sender[0]['COUNTRY_NAME'], $sender[0]['REGION_NAME'], $sender[0]['CITY_NAME']);
		$params['sender'] = $sender[0]['COUNTRY_NAME'] . $sender[0]['REGION_NAME'] . $sender[0]['CITY_NAME'];

		// Страховка
		if($this->config->get($this->code . '_cart_equally_product')){
			$shipment->setDeclaredValue(true);
		}

		$params['DeclaredValue'] = $shipment->getDeclaredValue();

		// Итоговая цена
		$productsPrice = 0;

		// список товаров входящих в отправку
		$products = $this->cart->getProducts();

		foreach ($products as $key => $product) {
			if($product['length'] > 0){
				$length = $product['length'];
			}else{
				$length = $this->config->get($this->code . '_length');
			}

			if($product['width'] > 0){
				$width = $product['width'];
			}else{
				$width = $this->config->get($this->code . '_width');
			}

			if($product['height'] > 0){
				$height = $product['height'];
			}else{
				$height = $this->config->get($this->code . '_height');
			}

			if($product['weight'] > 0){
				$weight = $this->weight->convert($product['weight'], $product['weight_class_id'], 2);
			}else{
				$weight = $this->config->get($this->code . '_weight');
			}

			$currency_price = $this->currency->format($this->tax->calculate($product['price'], 0, $this->config->get('config_tax')), $this->session->data['currency']);
			$currency_price = str_ireplace(" ", "", $currency_price);
			$currency_price = preg_replace('/[^0-9 , .]/', '', $currency_price);
			$price = (float)$currency_price;

			$data['products'][] = array(
				'NAME'     => $product['name'],
				'QUANTITY' => $product['quantity'], // кол-во
				'PRICE'    => $price, // стоимость за единицу
				'VAT_RATE' => 0, // ставка налога, процент или строка Без НДС
				'WEIGHT'   => $weight/$product['quantity'], // вес, граммы,
				'DIMENSIONS' => [
				'LENGTH' => $length, // длина, мм,
				'WIDTH'  => $width, // ширина, мм,
				'HEIGHT' => $height, // высота, мм,
				]
			);
			$productsPrice += ($price*$product['quantity']);
		}

		$shipment->setItems($data['products'], $productsPrice);

		$params['price'] = $shipment->getPrice();

		$params['Width'] =  $shipment->getWidth();
		$params['Height'] =  $shipment->getHeight();
		$params['Length'] =  $shipment->getLength();
		$params['Weight'] =  $shipment->getWeight();

		// Платёжная система
		$payment_dt = array();

		$total = $this->cart->getTotal();

		$this->load->model($this->model_setting_extension_path);

		$results_pay = $this->{$this->model_setting_extension_method}->getExtensions('payment');

		$recurring = $this->cart->hasRecurringProducts();

		if(!empty($results_pay)){
			foreach ($results_pay as $result) {
                if (VERSION >= '3.0.0.0') {
                    $code = 'payment_' . $result['code'];
                } else {
                    $code = $result['code'];
                }

                if ($this->config->get($code . '_status')) {
					$this->load->model('extension/payment/' . $result['code']);

					$method_pay = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method_pay) {
						if ($recurring) {
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$payment_dt[$result['code']] = $method_pay;
							}
						} else {
							$payment_dt[$result['code']] = $method_pay;
						}
					}
				}
			}
		}

		$sort_order_pay = array();

		if(!empty($payment_dt)){
			if(isset($payment_dt['webpay'])){
				foreach ($payment_dt['webpay'] as $key => $value) {
					$sort_order_pay[$key] = $value['sort_order'];
				}

				array_multisort($sort_order_pay, SORT_ASC, $payment_dt['webpay']);

				$first_payment = array_shift($payment_dt['webpay']);

				if(!isset($this->session->data['payment_method']['code'])){
					$this->session->data['payment_method']['code']  = $first_payment['code'];
				}
			}else{
				foreach ($payment_dt as $key => $value) {
					$sort_order_pay[$key] = $value['sort_order'];
				}

				array_multisort($sort_order_pay, SORT_ASC, $payment_dt);

				$first_payment = array_shift($payment_dt);

				if(!isset($this->session->data['payment_method']['code'])){
					$this->session->data['payment_method']['code']  = $first_payment['code'];
				}
			}
		}

		if($this->config->get($this->code . '_comission_for_product_'.$personeTypeId)){
			if(isset($this->request->post['payment_method'])){
				$payment_code = $this->request->post['payment_method'];
			}elseif(isset($this->session->data['payment_method']['code'])){
				$payment_code = $this->session->data['payment_method']['code'];
			}
			$payment_code = $this->session->data['payment_method']['code'];
			if($this->config->get($this->code . '_bind_payment_' . $personeTypeId)){
				if(is_array($this->config->get($this->code . '_bind_payment_' . $personeTypeId))){
					foreach($this->config->get($this->code . '_bind_payment_' . $personeTypeId) as $mthd){
						if($mthd == $payment_code){
							$paySystemId = $payment_code;
							$shipment->setPaymentMethod($personeTypeId, $paySystemId);
						}
					}
				}else{
					if($this->config->get($this->code . '_bind_payment_' . $personeTypeId) == $payment_code){
						$paySystemId = $payment_code;
						$shipment->setPaymentMethod($personeTypeId, $paySystemId);
					}
				}
			}
		}

		# Наложенный платёж
		$params['npp_payment']  = $shipment->isPaymentOnDelivery();
		$params['currencyTo']   = $options['KLIENT_CURRENCY'];
		$params['currencyFrom'] = $this->session->data['currency'];

		$converter =  new \Ipol\DPD\Currency\Converter($this->currency);

		$shipment->setCurrencyConverter($converter);
		$shipment->setCurrency($this->session->data['currency']);

		// Получаем калькулятор
		$calc = $shipment->calculator();
		// $calc->setCurrencyConverter($converter);

		# Округление
		if($this->config->get($this->code . '_ceil') > 0){
			$ceil = $this->config->get($this->code . '_ceil');
		}

		try{
			$params['SelfDelivery'] = true;
			$shipment->setSelfDelivery(true);
			$cache_terminal = 'dpd.shipping.calculateTerminal.' . md5(implode('', $params));
			if (! $json['tariffs']['pickup'] = $this->cache->get($cache_terminal)) {
				$json['tariffs']['pickup'] = $calc->calculate($params['currencyFrom']);
				$this->cache->set($cache_terminal, $json['tariffs']['pickup']);
			}

			# Наценка
			$json['tariffs']['pickup']['COST'] = $json['tariffs']['pickup']['COST'];
			if(($this->config->get($this->code . '_markup_type_door') == 1) && ($this->config->get($this->code . '_markup_door') > 0)){
				$percent = $json['tariffs']['pickup']['COST']*$this->config->get($this->code . '_markup_door')/100;
				$json['tariffs']['pickup']['COST'] = $percent + $json['tariffs']['pickup']['COST'];
			}elseif(($this->config->get($this->code . '_markup_type_door') == 0) && ($this->config->get($this->code . '_markup_door') > 0)){
				$json['tariffs']['pickup']['COST'] = $this->config->get($this->code . '_markup_door') + $json['tariffs']['pickup']['COST'];
			}

			$query_c = $this->db->query("SELECT decimal_place FROM " . DB_PREFIX . "currency WHERE code = '" . $this->session->data['currency'] . "'");
			$decimal_place = (int)$query_c->row['decimal_place'];

			$json['tariffs']['pickup']['COST'] = round($json['tariffs']['pickup']['COST'], $decimal_place);

			# Округление Door
			if(isset($ceil)){
				$json['tariffs']['pickup']['COST'] = ceil($json['tariffs']['pickup']['COST']/$ceil) * $ceil;
			}

			$params['SelfDelivery'] = false;
			$shipment->setSelfDelivery(false);
			$cache_door = 'dpd.shipping.calculateDoor.' . md5(implode('', $params));
			if (! $json['tariffs']['courier'] = $this->cache->get($cache_door)) {
				$json['tariffs']['courier'] = $calc->calculate($params['currencyFrom']);
				$this->cache->set($cache_door, $json['tariffs']['courier']);
			}

			# Наценка
			$json['tariffs']['courier']['COST'] = $json['tariffs']['courier']['COST'];
			if(($this->config->get($this->code . '_markup_type_terminal') == 1) && ($this->config->get($this->code . '_markup_terminal') > 0)){
				$percent = $json['tariffs']['courier']['COST']*$this->config->get($this->code . '_markup_terminal')/100;
				$json['tariffs']['courier']['COST'] = $percent + $json['tariffs']['courier']['COST'];
			}elseif(($this->config->get($this->code . '_markup_type_terminal') == 0) && ($this->config->get($this->code . '_markup_terminal') > 0)){
				$json['tariffs']['courier']['COST'] = $this->config->get($this->code . '_markup_terminal') + $json['tariffs']['courier']['COST'];
			}

			$json['tariffs']['courier']['COST'] = round($json['tariffs']['pickup']['COST'], $decimal_place);

			# Округление Terminal
			if(isset($ceil)){
				$json['tariffs']['courier']['COST'] = ceil($json['tariffs']['courier']['COST']/$ceil) * $ceil;
			}


			if($this->config->get($this->code . '_wait_address') != 0){
			$ogd = '|ОЖД_'.$this->config->get($this->code . '_wait_address').'|';
			}else{
				$ogd = '';
			}

			$json['terminals'] = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal')->findModels([
				'where' => 'LOCATION_ID = :location AND SERVICES LIKE :ogd',
				'bind'  =>
				[
				'location' => $shipment->getReceiver()['CITY_ID'],
				'ogd' => '%'.$ogd.'%'
				],

			]);

			$json['terminals'] = array_filter($json['terminals'], function($terminal) use ($shipment) {
				return $terminal->checkShipment($shipment);
			});

			$json['terminals'] = array_values($json['terminals']);

		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function checkStatus() {

		$config = $this->getOpencartConfig();
		$config->set('STATUS_ORDER_CHECK', true);

		#cheked orders
		try{
			$upload  = \Ipol\DPD\Agents::checkOrderStatus($config);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		#get orders
		$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order');
		$orders = $table->find()->fetchAll();
		$dpd_statuses = \Ipol\DPD\DB\Order\Model::StatusList($config);

		foreach($orders as $order){
			//$this->db->query("UPDATE " . DB_PREFIX . "dpd_order SET status_dpd = '" . $order['ORDER_STATUS'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");

			if($this->config->get($this->code . '_track_status_dpd')){
				foreach($dpd_statuses as $k => $row){
					if($order['ORDER_STATUS'] == $k && $this->config->get($this->code . '_status_' . $k) !== 'non'){
						$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order WHERE order_id = '" . $order['ORDER_ID'] . "'");
						if($query->row['order_status_id'] != $this->config->get($this->code . '_status_' . $k)){
							$this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $this->config->get($this->code . '_status_' . $k) . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
							$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_status_id = '" . $this->config->get($this->code . '_status_' . $k) . "', order_id = '" . $order['ORDER_ID'] . "', comment = 'Автоматическое обновление ROBOKASSA', date_added = NOW()");
						}
					}
				}
			}
		}
	}

	public function import() {
		$config = $this->getOpencartConfig();

		// устанавливаем позицию импорта
		if(isset($this->session->data['dpd']['import'])){
			if(($this->session->data['dpd']['import']['step'] != 'LOAD_FINISH')){
				$config->set('LOAD_EXTERNAL_DATA_STEP', $this->session->data['dpd']['import']['step']);
				$config->set('LOAD_EXTERNAL_DATA_POSITION', $this->session->data['dpd']['import']['position'][0]);
			}else{
				print_r($this->session->data['dpd']['import']);
				unset($this->session->data['dpd']['import']);
				exit("Импорт завершён!");
			}
		}else{
			$config->set('LOAD_EXTERNAL_DATA_STEP', 'LOAD_LOCATION_ALL');
			$config->set('LOAD_EXTERNAL_DATA_POSITION', 0);
		}

		try{
			$loader = \Ipol\DPD\Agents::loadExternalData($config);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		$this->session->data['dpd']['import']['step'] = $config->get('LOAD_EXTERNAL_DATA_STEP');

		if(is_array($config->get('LOAD_EXTERNAL_DATA_POSITION'))){
			$this->session->data['dpd']['import']['position'] = $config->get('LOAD_EXTERNAL_DATA_POSITION');

			if($this->session->data['dpd']['import']['step'] == 'DELETE_TERMINALS'){
				header("refresh: 1; url=" . $this->url->link('extension/shipping/dpd/loadUnlimited'));
			}
		}else{
			if(($this->session->data['dpd']['import']['step'] == 'LOAD_LOCATION_ALL') && !empty($config->get('LOAD_EXTERNAL_DATA_POSITION'))){
				$this->session->data['dpd']['import']['position'] = array();
				$this->session->data['dpd']['import']['position'][0] = 0;
				$this->session->data['dpd']['import']['step'] = 'LOAD_LOCATION_CASH_PAY';
			}elseif($this->session->data['dpd']['import']['step'] == 'LOAD_LOCATION_CASH_PAY'){
				$this->session->data['dpd']['import']['position'] = array();
				$this->session->data['dpd']['import']['position'][0] = 'RU:0';
				$this->session->data['dpd']['import']['step'] = 'DELETE_TERMINALS';
			}elseif($this->session->data['dpd']['import']['step'] == 'DELETE_TERMINALS'){
				header("refresh: 1; url=" . $this->url->link('extension/shipping/dpd/loadUnlimited'));
			}
		}

		if($this->session->data['dpd']['import']['step'] == 'DELETE_TERMINALS'){
			header("refresh: 1; url=" . $this->url->link('extension/shipping/dpd/loadUnlimited'));
		}else{
			header("refresh: 3; url=" . $this->url->link('extension/shipping/dpd/import'));
		}

		echo "Загрузка городов</br>";

		if(isset($this->session->data['dpd']['import']['position'][0]) && isset($this->session->data['dpd']['import']['position'][1])){
			echo "Загруженно ";
			print_r($this->session->data['dpd']['import']['position'][0]);
			echo " из ";
			print_r($this->session->data['dpd']['import']['position'][1]);
		}
	}

	# Import loadUnlimited
	public function loadUnlimited(){
		ini_set('default_socket_timeout', 600);

		try{
			$config  = $this->getOpencartConfig();
			$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
			$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
			$loader->loadUnlimited();

			header("refresh: 2; url=" . $this->url->link('extension/shipping/dpd/loadLimited'));
		} catch (\Exception $e) {
			header("refresh: 2; url=" . $this->url->link('extension/shipping/dpd/loadUnlimited'));
		}

		echo "Загрузка терминалов без лимитов</br>";
	}

	# Import loadLimited
	public function loadLimited(){
		ini_set('default_socket_timeout', 600);

		try{
			$config  = $this->getOpencartConfig();
			$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
			$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);

			if(isset($this->session->data['loadLimited']) && $this->session->data['loadLimited'] !== true){
				$this->session->data['loadLimited'] = $loader->loadLimited($this->session->data['loadLimited'][0]);
				header("refresh: 2; url=" . $this->url->link('extension/shipping/dpd/loadLimited'));
			}else{
				$this->session->data['loadLimited'] = $loader->loadLimited('RU:0');
				header("refresh: 2; url=" . $this->url->link('extension/shipping/dpd/loadLimited'));
			}

			header("refresh: 2; url=" . $this->url->link('extension/shipping/dpd/unsetImport'));
		} catch (\Exception $e) {
			die($e->getMessage());
		}

		echo "Загрузка терминалов с лимитами</br>";
	}

	# Unset
	public function unsetImport(){
		unset($this->session->data['dpd']['import']);
		unset($this->session->data['loadLimited']);

		exit("Импорт завершён!");
	}

}