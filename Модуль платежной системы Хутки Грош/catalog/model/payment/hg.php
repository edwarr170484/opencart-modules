<?php
/*define(HG_URL, 'https://trial.hgrosh.by/API/v1/');*/

class ModelPaymentHG extends Model {
	var $headers;
	var $user_agent;
	var $cookie_file;
	
	private function cURL($cookies=TRUE) 
	{
		$this->headers[] = 'Accept: application/xml';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/xml';
		$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
		$this->cookies = $cookies;
	}
	
	private function get($url, $cookie) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($this->cookies == TRUE) 
			curl_setopt($process, CURLOPT_COOKIE, $cookie);
		
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	
	private function post($url,$data, $cookie) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($this->cookies == TRUE) 
			curl_setopt($process, CURLOPT_COOKIE, $cookie);
		
		$return = curl_exec($process);
		
		$answer = array();
		
		preg_match_all("/Set-Cookie: (.*?)=(.*?);/i", $return, $res);
		$cookie = '';
		
		foreach ($res[1] as $key => $value) 
		{
			$cookie .= $value.'='.$res[2][$key].'; ';
		};

		
		$answer['cookie'] = $cookie;
		
		if (preg_match('/\\r\\n\\r\\n(.*)/', $return, $body) == 1)
		{
			$answer['body'] = $body[1];
		}
		$answer['responce'] = $return;
		curl_close($process);
		return $answer;
	}
	
	public function getMethod($address, $total) {
		$this->load->language('payment/hg');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('hg_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('hg_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'hg',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('hg_sort_order')
			);
		}

		return $method_data;
	}
	
	public function login()
	{
		$this->cURL(TRUE);
		
		$credentials = '<Credentials xmlns="http://www.hutkigrosh.by/api"><user>'.$this->config->get('hg_login').'</user><pwd>'.$this->config->get('hg_password').'</pwd></Credentials>';
		
		$result = $this->post($this->config->get('hg_url').'Security/LogIn',$credentials,'');
		
		$right = new SimpleXMLElement($result['body']);
		
		if((string)$right == 'false')
			return false;
		
		return $result['cookie'];
	}
	
	public function addBill($order_id)
	{
		$cookie = $this -> login();
		
		$products = '';
		
		if($cookie)
		{
			$this->load->model('checkout/order');
			$order_data = $this->model_checkout_order->getOrder($order_id);
			
			if($order_data)
			{
				$order_products = $this->getOrderProducts($order_id);
				
				if($order_products)
				{
					foreach($order_products as $order_product)
					{
						$products .= '<ProductInfo><invItemId>'.$order_product['model'].'</invItemId>
									  <desc>'.$order_product['name'].'</desc>
								      <count>'.$order_product['quantity'].'</count>
								      <amt>'.$order_product['price'].'</amt></ProductInfo>';
					}
				}
			}
			
			$dataXML = '<Bill xmlns="http://www.hutkigrosh.by/api/invoicing">
							  <eripId>'.$this->config->get('hg_ID').'</eripId>
							  <invId>'.$order_data['order_id'].'</invId>
							  <dueDt>'.date("c",strtotime("+48 hours")).'</dueDt>
							  <addedDt>'.date("c").'</addedDt>
							  <fullName>'.$order_data['lastname'].' '.$order_data['firstname'].'</fullName>
							  <mobilePhone>'.$order_data['telephone'].'</mobilePhone>
							  <notifyByMobilePhone>true</notifyByMobilePhone>
							  <email>'.$order_data['email'].'</email>
							  <notifyByEMail>true</notifyByEMail>
							  <fullAddress>'.$order_data['payment_postcode'].','.$order_data['payment_country'].','.$order_data['payment_city'].','.$order_data['payment_address_1'].'</fullAddress>
							  <amt>'.$order_data['total'].'</amt>
							  <curr>'.$order_data['currency_code'].'</curr>
							  <statusEnum>NotSet</statusEnum>
							  <products>
								'.$products.'
							  </products>
							</Bill>';
			
			$result = $this->post($this->config->get('hg_url').'Invoicing/Bill',$dataXML,$cookie);
			$right = new SimpleXMLElement($result['body']);
			
			$this -> logout($cookie);
			
			if((int)$right->billID > 0)
			{
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	public function checkBill($billID)
	{
		$cookie = $this -> login();
		
		if($cookie)
		{
			$bill = $this->get($this->config->get('hg_url').'Invoicing/Bill('.$billID.')',$cookie);
			
			$billInfo = new SimpleXMLElement($bill);
			
			$this -> logout($cookie);
			
			if($billInfo)
			{
				$order_id = (int)$billInfo->bill[0]->invId;
				$billStatus = (int)$billInfo->status;
				
				if($order_id > 0)
				{
					$this->load->model('checkout/order');
					$order_data = $this->model_checkout_order->getOrder($order_id);
					
					if($order_data)
					{
						 $this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id='".$this->config->get('hg_order_final_status_id')."' WHERE order_id = '" . (int)$order_id . "'");
						return true;
					}
					else
					{
						return false;
					}
				}
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	private function logout($cookie)
	{
		$result = $this->post($this->config->get('hg_url').'Security/LogOut','',$cookie);
		
		return $result['body'];
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
}
?>