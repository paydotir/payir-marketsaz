<?php 

class ControllerPaymentPayir extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('payment/payir');
		$this->load->model('setting/setting');

		$this->document->title = $this->language->get('heading_title');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			$this->model_setting_setting->editSetting('payir', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_api'] = $this->language->get('entry_api');
		$this->data['entry_send'] = $this->language->get('entry_send');
		$this->data['entry_verify'] = $this->language->get('entry_verify');
		$this->data['entry_gateway'] = $this->language->get('entry_gateway');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['help_encryption'] = $this->language->get('help_encryption');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('common/home'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('extension/payment'),
			'text' => $this->language->get('text_payment'),
			'separator' => ' :: '
		);

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('payment/payir'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->https('payment/payir');
		$this->data['cancel'] = $this->url->https('extension/payment');

		if (isset($this->request->post['payir_api'])) {

			$this->data['payir_api'] = $this->request->post['payir_api'];

		} else {

			$this->data['payir_api'] = $this->config->get('payir_api');
		}

		if (isset($this->request->post['payir_send'])) {

			$this->data['payir_send'] = $this->request->post['payir_send'];

		} else {

			$this->data['payir_send'] = $this->config->get('payir_send');

			if(isset($this->data['payir_send'])){

				$this->data['payir_send'] = $this->data['payir_send'];

			} else {

				$this->data['payir_send'] = 'https://pay.ir/payment/send';
			}
		}

		if (isset($this->request->post['payir_verify'])) {

			$this->data['payir_verify'] = $this->request->post['payir_verify'];

		} else {

			$this->data['payir_verify'] = $this->config->get('payir_verify');

			if(isset($this->data['payir_verify'])){

				$this->data['payir_verify'] = $this->data['payir_verify'];

			} else {

				$this->data['payir_verify'] = 'https://pay.ir/payment/verify';
			}
		}

		if (isset($this->request->post['payir_gateway'])) {

			$this->data['payir_gateway'] = $this->request->post['payir_gateway'];

		} else {

			$this->data['payir_gateway'] = $this->config->get('payir_gateway');

			if(isset($this->data['payir_gateway'])){

				$this->data['payir_gateway'] = $this->data['payir_gateway'];

			} else {

				$this->data['payir_gateway'] = 'https://pay.ir/payment/gateway/';
			}
		}

		if (isset($this->request->post['payir_order_status_id'])) {

			$this->data['payir_order_status_id'] = $this->request->post['payir_order_status_id'];

		} else {

			$this->data['payir_order_status_id'] = $this->config->get('payir_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payir_status'])) {

			$this->data['payir_status'] = $this->request->post['payir_status'];

		} else {

			$this->data['payir_status'] = $this->config->get('payir_status');
		}

		if (isset($this->request->post['payir_sort_order'])) {

			$this->data['payir_sort_order'] = $this->request->post['payir_sort_order'];

		} else {

			$this->data['payir_sort_order'] = $this->config->get('payir_sort_order');
		}

		$this->id = 'content';
		$this->template = 'payment/payir.tpl';
		$this->layout = 'common/layout';

		$this->render();
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/payir')) {

			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!@$this->request->post['payir_api']) {

			$this->error['warning'] = $this->language->get('error_api');
		}
		
		if (!@$this->request->post['payir_send']) {

			$this->error['warning'] = $this->language->get('error_send');
		}
		
		if (!@$this->request->post['payir_verify']) {

			$this->error['warning'] = $this->language->get('error_verify');
		}
		
		if (!@$this->request->post['payir_gateway']) {

			$this->error['warning'] = $this->language->get('error_gateway');
		}

		if (!$this->error) {

			return true;

		} else {

			return false;
		}
	}
}