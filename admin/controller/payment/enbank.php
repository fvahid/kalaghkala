<?php
class ControllerPaymentEnbank extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/enbank');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('enbank', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['merchantID'] = $this->language->get('merchantID');
		//$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['payable'])) {
			$data['error_payable'] = $this->error['payable'];
		} else {
			$data['error_payable'] = '';
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
			'href' => $this->url->link('payment/enbank', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/enbank', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['enbank_payable'])) {
			$data['enbank_payable'] = $this->request->post['enbank_payable'];
		} else {
			$data['enbank_payable'] = $this->config->get('enbank_payable');
		}

		if (isset($this->request->post['enbank_total'])) {
			$data['enbank_total'] = $this->request->post['enbank_total'];
		} else {
			$data['enbank_total'] = $this->config->get('enbank_total');
		}

		if (isset($this->request->post['enbank_order_status_id'])) {
			$data['enbank_order_status_id'] = $this->request->post['enbank_order_status_id'];
		} else {
			$data['enbank_order_status_id'] = $this->config->get('enbank_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['enbank_geo_zone_id'])) {
			$data['enbank_geo_zone_id'] = $this->request->post['enbank_geo_zone_id'];
		} else {
			$data['enbank_geo_zone_id'] = $this->config->get('enbank_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['enbank_status'])) {
			$data['enbank_status'] = $this->request->post['enbank_status'];
		} else {
			$data['enbank_status'] = $this->config->get('enbank_status');
		}

		if (isset($this->request->post['enbank_sort_order'])) {
			$data['enbank_sort_order'] = $this->request->post['enbank_sort_order'];
		} else {
			$data['enbank_sort_order'] = $this->config->get('enbank_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/enbank.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/enbank')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['enbank_payable']) {
			$this->error['payable'] = $this->language->get('error_payable');
		}

		return !$this->error;
	}
}