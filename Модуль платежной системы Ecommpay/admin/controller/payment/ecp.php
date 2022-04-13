<?php
class ControllerPaymentEcp extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/ecp');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ecp', $this->request->post);

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

		$data['action'] = $this->url->link('payment/ecp', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['ecp_login'])) {
			$data['ecp_login'] = $this->request->post['ecp_login'];
		} else {
			$data['ecp_login'] = $this->config->get('ecp_login');
		}
		if (isset($this->request->post['ecp_password'])) {
			$data['ecp_password'] = $this->request->post['ecp_password'];
		} else {
			$data['ecp_password'] = $this->config->get('ecp_password');
		}
		if (isset($this->request->post['ecp_url'])) {
			$data['ecp_url'] = $this->request->post['ecp_url'];
		} else {
			$data['ecp_url'] = $this->config->get('ecp_url');
		}
		if (isset($this->request->post['ecp_order_status_id'])) {
			$data['ecp_order_status_id'] = $this->request->post['ecp_order_status_id'];
		} else {
			$data['ecp_order_status_id'] = $this->config->get('ecp_order_status_id');
		}
		
		if (isset($this->request->post['ecp_order_final_status_id'])) {
			$data['ecp_order_final_status_id'] = $this->request->post['ecp_order_final_status_id'];
		} else {
			$data['ecp_order_final_status_id'] = $this->config->get('ecp_order_final_status_id');
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

		if (isset($this->request->post['ecp_status'])) {
			$data['ecp_status'] = $this->request->post['ecp_status'];
		} else {
			$data['ecp_status'] = $this->config->get('ecp_status');
		}

		if (isset($this->request->post['ecp_sort_order'])) {
			$data['ecp_sort_order'] = $this->request->post['ecp_sort_order'];
		} else {
			$data['ecp_sort_order'] = $this->config->get('ecp_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/ecp.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/ecp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}