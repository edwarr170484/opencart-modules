<?php

class ModelPaymentEcp extends Model {
	
	public function assemble(array $params, $salt)
	{
		return sha1($this->parseArray($params) . ";" . $salt);
	}
	
	private function parseArray(array $params)
	{
		$paramsToSign = array();
		ksort($params);
		foreach ($params as $key => $value) {
			if ($value === "") continue;
			array_push($paramsToSign, $key . ':' . $value);
		}
		return implode(";", $paramsToSign);
	}
	
	public function getMethod($address, $total) {
		$this->load->language('payment/ecp');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ecp_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('ecp_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'ecp',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('ecp_sort_order')
			);
		}

		return $method_data;
	}
	
}
?>