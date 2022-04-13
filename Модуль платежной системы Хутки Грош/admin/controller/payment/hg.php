<?php
class ControllerPaymentHg extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/hg');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hg', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['entry_login'] = $this->language->get('entry_login');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_ID'] = $this->language->get('entry_ID');
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_final_status'] = $this->language->get('entry_order_final_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/hg', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/hg', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['hg_login'])) {
			$data['hg_login'] = $this->request->post['hg_login'];
		} else {
			$data['hg_login'] = $this->config->get('hg_login');
		}
		if (isset($this->request->post['hg_password'])) {
			$data['hg_password'] = $this->request->post['hg_password'];
		} else {
			$data['hg_password'] = $this->config->get('hg_password');
		}
		if (isset($this->request->post['hg_ID'])) {
			$data['hg_ID'] = $this->request->post['hg_ID'];
		} else {
			$data['hg_ID'] = $this->config->get('hg_ID');
		}
		if (isset($this->request->post['hg_url'])) {
			$data['hg_url'] = $this->request->post['hg_url'];
		} else {
			$data['hg_url'] = $this->config->get('hg_url');
		}
		if (isset($this->request->post['hg_order_status_id'])) {
			$data['hg_order_status_id'] = $this->request->post['hg_order_status_id'];
		} else {
			$data['hg_order_status_id'] = $this->config->get('hg_order_status_id');
		}
		
		if (isset($this->request->post['hg_order_final_status_id'])) {
			$data['hg_order_final_status_id'] = $this->request->post['hg_order_final_status_id'];
		} else {
			$data['hg_order_final_status_id'] = $this->config->get('hg_order_final_status_id');
		}
		
		if (isset($this->request->post['cod_geo_zone_id'])) {
			$data['cod_geo_zone_id'] = $this->request->post['cod_geo_zone_id'];
		} else {
			$data['cod_geo_zone_id'] = $this->config->get('cod_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['hg_status'])) {
			$data['hg_status'] = $this->request->post['hg_status'];
		} else {
			$data['hg_status'] = $this->config->get('hg_status');
		}

		if (isset($this->request->post['hg_sort_order'])) {
			$data['hg_sort_order'] = $this->request->post['hg_sort_order'];
		} else {
			$data['hg_sort_order'] = $this->config->get('hg_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/hg.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/hg')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}