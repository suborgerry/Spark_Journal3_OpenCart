<?php
class ModelExtensionShippingDpd extends Model {
    private $code;
    private $model_setting_extension_path;
    private $model_setting_extension_method;

    public function __construct($registry)
    {
        parent::__construct($registry);
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

	public function selectCustomerGroup($customer_id){
		$sql = "SELECT customer_group_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $customer_id . "'";
		$query = $this->db->query($sql);

		return $query->row['customer_group_id'];
	}

	public function getZone($region_code){
		$sql = "SELECT zone_id, country_id FROM " . DB_PREFIX . "zone WHERE code = '" . $region_code . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	function getQuote($address) {
		$this->load->language('extension/shipping/dpd');
		$this->load->model('tool/image');

		if ($this->config->get($this->code . '_status')) {
			$status = true;
		} else {
			$status = false;
		}

		require DIR_SYSTEM . 'dpd/src/autoload.php';

		if(isset($this->session->data['guest'])){
			$personeTypeId = $this->session->data['guest']['customer_group_id'];
		}elseif(isset($this->session->data['customer_id'])){
			$personeTypeId = $this->selectCustomerGroup($this->session->data['customer_id']);
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
            'USE_LOCAL_WSDL_CACHE'          => $this->config->get($this->code . '_wsdl') ? true : false,
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

		$method_data = array();

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
			$sender = $table->find($f = [
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
				$receiver = $table->find($f = [
					'where' => 'CITY_ID = :city_id',
					'limit' => '0,1',
					'bind'  => [
						'city_id' => $this->session->data['dpd']['city_id']
					]
				])->fetchAll();

				if($receiver[0]['CITY_NAME'] == $this->session->data['shipping_address']['city']){
					$receiver = $receiver;
				}else{
					$receiver = $table->find($f = [
						'where' => 'CITY_NAME = :city_name',
						'limit' => '0,1',
						'bind'  => [
							'city_name' => $this->session->data['shipping_address']['city']
						]
					])->fetchAll();
				}
			}else{
				$receiver = $table->find($f = [
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


		$this->session->data['dpd']['city_id'] = $shipment->getReceiver()['CITY_ID'];

		if(!empty($sender)){
			$shipment->setSender($sender[0]['COUNTRY_NAME'], $sender[0]['REGION_NAME'], $sender[0]['CITY_NAME']);
			$params['sender'] = $sender[0]['COUNTRY_NAME'] . $sender[0]['REGION_NAME'] . $sender[0]['CITY_NAME'];
		}else{
			$shipment->setSender('empty', 'empty', 'empty');
			$params['sender'] = 'empty';
		}

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

			$currency_price = $this->currency->format(
				$this->tax->calculate($product['price'], 0, $this->config->get('config_tax')), 
				$this->session->data['currency']
			);
			$currency_price = str_ireplace(" ", "", $currency_price);
			$currency_price = preg_replace('/[^0-9 , .]/', '', $currency_price);
			$price = (float)$currency_price;

			if($this->config->get('config_tax')) {
				$tax = $this->tax->getTax((float)$price, $product['tax_class_id']);
			}else{
				$tax = 'Без НДС';
			}

			$data['products'][] = array(
				'NAME'     => $product['name'],
				'QUANTITY' => $product['quantity'], // кол-во
				'PRICE'    => $price, // стоимость за единицу
				'VAT_RATE' => $tax, // ставка налога, процент или строка Без НДС
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

		$shipment->setPrice($productsPrice);

		$params['price'] = $shipment->getPrice();

		$params['Width'] =  $shipment->getWidth();
		$this->session->data['dpd']['width'] = $shipment->getWidth();
		$params['Height'] =  $shipment->getHeight();
		$this->session->data['dpd']['height'] = $shipment->getHeight();
		$params['Length'] =  $shipment->getLength();
		$this->session->data['dpd']['length'] = $shipment->getLength();
		$params['Weight'] =  $shipment->getWeight();
		$this->session->data['dpd']['weight'] = $shipment->getWeight();
		$this->session->data['dpd']['volume'] = $shipment->getVolume();

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

		//echo $shipment->isPaymentOnDelivery().'</br>';
		# Наложенный платёж
		$params['npp_payment'] = $shipment->isPaymentOnDelivery();
		$this->session->data['dpd']['npp_payment'] = $shipment->isPaymentOnDelivery();

		# Currency
		$params['currencyTo'] = $options['KLIENT_CURRENCY'];
		$params['currencyFrom'] = $this->session->data['currency'];

		# Total
		$this->session->data['dpd']['total'] = $shipment->getPrice();

		$converter =  new \Ipol\DPD\Currency\Converter($this->currency);

		$shipment->setCurrencyConverter($converter);
		$shipment->setCurrency($this->config->get('config_currency'));
		
		// Получаем калькулятор
		$calc = $shipment->calculator();

		// Тариф по умолчанию
		$default_tariff = $calc->setDefaultTariff($this->config->get($this->code . '_tariff_default'), $this->config->get($this->code . '_max_for_default'));

		# Округление
		if($this->config->get($this->code . '_ceil') > 0){
			$ceil = $this->config->get($this->code . '_ceil');
		}

		/*file_put_contents(
			__DIR__.'/params.log',
			print_r($shipment, true),
			FILE_APPEND
		);*/

		if ($status) {
			$quote_data = array();
			if ($this->config->get($this->code . '_door_status')) {
				try {
					// Расчёт до двери
					$shipment->setSelfDelivery(false);
					$params['SelfDelivery'] = false;

					$cache = 'dpd.shipping.calculateDoor.' . md5(implode('', $params)) .'1';
					if (!($tariff_to_door = $this->cache->get($cache)) || 1 == 1) {
						$tariff_to_door = $calc->calculate($this->config->get('config_currency'));
						$this->cache->set($cache, $tariff_to_door);
					}

                    if(!empty($tariff_to_door)){

                        # Сессия для доставки
						$this->session->data['dpd']['door'] = $tariff_to_door;

						# Наценка
						$price_to_door = $tariff_to_door['COST'];
						if(($this->config->get($this->code . '_markup_type_door') == 1) && ($this->config->get($this->code . '_markup_door') > 0)){
							$percent = $tariff_to_door['COST']*$this->config->get($this->code . '_markup_door')/100;
							$price_to_door = $percent + $tariff_to_door['COST'];
						}elseif(($this->config->get($this->code . '_markup_type_door') == 0) && ($this->config->get($this->code . '_markup_door') > 0)){
							$price_to_door = $this->config->get($this->code . '_markup_door') + $tariff_to_door['COST'];
						}

						# Округление Door
						if(isset($ceil)){
							$price_to_door = ceil($price_to_door/$ceil) * $ceil;
						}

						# Thumb
						$image_door_path = $this->config->get($this->code . '_image_door');
						if( isset($image_door_path) and $image_door_path && file_exists(DIR_IMAGE .  $image_door_path)) {
							$thumb_door = $this->model_tool_image->resize($image_door_path, 40, 30);
						}else {
							$thumb_door = '';
						}

						# Срок доставки
						$days_door = $tariff_to_door['DAYS'];
						if($this->config->get($this->code . '_term_shipping')){
							$days_door += $this->config->get($this->code . '_term_shipping');
						}

						# Image
						$image_door = '<img style="padding:0px 4px 0px 4px;" src="' . $thumb_door . '" align="middle">';

						$quote_data['dpd'.'door'] = array(
							'code'         => 'dpd.dpd'.'door',
							'title'        => $this->config->get($this->code . '_name_door'),
							'courier'      => false,
							'imag'		   => $image_door,
							'image'		   => $thumb_door,
							'description'  => html_entity_decode($this->config->get($this->code . '_description_door'), ENT_QUOTES, 'UTF-8'),
							'cost'         => $price_to_door,
							'tax_class_id' => 0,
							'text'         => $this->currency->format(
								$this->tax->calculate($price_to_door, 0, $this->config->get('config_tax')), 
								$this->session->data['currency']
							) .', '.'срок ' . $days_door . ' дн.'
						);
					}
				} catch (\Exception $e) {
//                    print_r($e->getMessage());die;
				}
			}

			if ($this->config->get($this->code . '_terminal_status')) {
				try {

					// Расчёт до терминала
					$shipment->setSelfDelivery(true);
					$params['SelfDelivery'] = true;

					$cache = 'dpd.shipping.calculateTerminal.' . md5(implode('', $params));
					if (!($tariff_to_terminal = $this->cache->get($cache)) || 1 == 1) {
						$tariff_to_terminal = $calc->calculate($this->config->get('config_currency'));
						$this->cache->set($cache, $tariff_to_terminal);
					}

					if(!empty($tariff_to_terminal)){
						$terminals = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal')
							->findModels([
								'where' => 'LOCATION_ID = :location_id AND SCHEDULE_SELF_DELIVERY != ""',
								'bind'  => ['location_id' => $shipment->getReceiver()['CITY_ID']]
							])
						;

						$terminals = array_filter($terminals, function($terminal) use ($shipment) {
							return $terminal->checkShipment($shipment);
						});

						if (count($terminals) > 0) {
							# Сессия для доставки
							$this->session->data['dpd']['terminal'] = $tariff_to_terminal;

							# Наценка
							$price_to_terminal = $tariff_to_terminal['COST'];
							if(($this->config->get($this->code . '_markup_type_terminal') == 1) && ($this->config->get($this->code . '_markup_terminal') > 0)){
								$percent = $tariff_to_terminal['COST']*$this->config->get($this->code . '_markup_terminal')/100;
								$price_to_terminal = $percent + $tariff_to_terminal['COST'];
							}elseif(($this->config->get($this->code . '_markup_type_terminal') == 0) && ($this->config->get($this->code . '_markup_terminal') > 0)){
								$price_to_terminal = $this->config->get($this->code . '_markup_terminal') + $tariff_to_terminal['COST'];
							}

							# Округление Terminal
							if(isset($ceil)){
								$price_to_terminal = ceil($price_to_terminal/$ceil) * $ceil;
							}

							# Thumb
							$image_terminal_path = $this->config->get($this->code . '_image_terminal');
							if( isset($image_terminal_path) and $image_terminal_path && file_exists(DIR_IMAGE .  $image_terminal_path)) {
								$thumb_terminal = $this->model_tool_image->resize($image_terminal_path, 40, 30);
							}else {
								$thumb_terminal = '';
							}

							# Срок доставки
							$days_terminal = $tariff_to_terminal['DAYS'];
							if($this->config->get($this->code . '_term_shipping')){
								$days_terminal += $this->config->get($this->code . '_term_shipping');
							}

							# Image
							$image_terminal ='<img style="padding:0px 4px 0px 4px;" src="' . $thumb_terminal . '" align="middle">';

							$quote_data['dpd'.'terminal'] = array(
								'code'         => 'dpd.dpd'.'terminal',
								'title'        => $this->config->get($this->code . '_name_terminal'),
								'courier'      => true,
								'imag'		   => $image_terminal,
								'image'		   => $thumb_terminal,
								'description'  => html_entity_decode($this->config->get($this->code . '_description_terminal'), ENT_QUOTES, 'UTF-8'),
								'cost'         => $price_to_terminal,
								'tax_class_id' => 0,
								'text'         => $this->currency->format(
									$this->tax->calculate($price_to_terminal, 0, $this->config->get('config_tax')), 
									$this->session->data['currency']
								) .', '.'срок ' . $days_terminal . ' дн.'
							);
						}
					}
				} catch (\Exception $e) {

				}
			}

			if(!empty($quote_data)){
				$method_data = array(
					'code'       => 'dpd',
					'title'      => 'Доставка DPD',
					'quote'      => $quote_data,
					'sort_order' => $this->config->get($this->code . '_sort_order'),
					'error'      => false
				);
			}
		}

		return $method_data;
	}
}
