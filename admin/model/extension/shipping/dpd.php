<?php
# Разработчик: Кузнецов Богдан	
# E-mail: bogdan199210@yandex.ru
# DPD - служба доставки

class ModelExtensionShippingDpd extends Model {
	public function getCurrencies() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title");

		return $query->rows;
	}
	
	public function getRegions() {
		$query = $this->db->query("SELECT REGION_NAME FROM b_ipol_dpd_location GROUP BY REGION_NAME");

		return $query->rows;
	}
	
	public function getRegion($name) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE name LIKE '%" . $name . "%'");
		
		if($query->num_rows){
			return $query->row['code'];
		}else{
			$code = '';
			
			if($name == 'АО Ханты-Мансийский - Югра'){
				$code = 'KHM';
			}elseif($name == 'Брестская'){
				$code = 'BR';
			}elseif($name == 'Витебская'){
				$code = 'VI';
			}elseif($name == 'Гомельская'){
				$code = 'HO';
			}elseif($name == 'Кабардино-Балкарская'){
				$code = 'KB';
			}elseif($name == 'Карачаево-Черкесская'){
				$code = 'KC';
			}elseif($name == 'Саха /Якутия/'){
				$code = 'SA';
			}
			return $code;
		}		
	}
	
	public function updateRegions($region_name, $region_code){
		$this->db->query("UPDATE b_ipol_dpd_location SET REGION_CODE = '" . $region_code . "' WHERE REGION_NAME LIKE '%" . $region_name . "%'");
	}
		
	public function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
	
	public function getOrderDpd($order_id) {
		$query = $this->db->query("SELECT * FROM b_ipol_dpd_order WHERE ORDER_ID = '" . $this->db->escape($order_id) . "'");

		return $query->row;
	}
}