<?php
class ControllerPaymentHg extends Controller {
	public function index() {
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/successhg');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/hg.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/hg.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/hg.tpl', $data);
		}
	}

	public function confirm() 
	{
		$result = '';
		$json = array();
		
		if ($this->session->data['payment_method']['code'] == 'hg') 
		{
			/*отправим в систему расчет новый счет*/
			$this->load->model('payment/hg');
			
			$result = $this->model_payment_hg->addBill($this->session->data['order_id']);
			
			if($result)
			{
				$this->load->model('checkout/order');
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('hg_order_status_id'));
				$json = array("message" => true);
			}
			else
			{
				$json = array("message" => "При добавлении заказа произошла ошибка. Попробуйте повторить позже");
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function check()
	{
		if(isset($this->request->get['purchaseid']))
			$purchaseId = $this->request->get['purchaseid']; //идентификатор оплаченного счета
		else
			$purchaseId = 0;
		//проверяем счет 
		if($purchaseId)
		{
			$this->load->model('payment/hg');
			
			$result = $this->model_payment_hg->checkBill($purchaseId);
			
			if($result)
			{
				$this->response->addHeader("HTTP/1.1 200 OK");
				$this->response->setOutput('OK');
			}
			else
			{
				$this->response->addHeader("HTTP/1.1 500 Internal Server Error");
				$this->response->setOutput('Error');
			}
		}
		else
		{
			$this->response->addHeader("HTTP/1.1 500 Internal Server Error");
			$this->response->setOutput('Error');
		}
		
		
		
	}
}