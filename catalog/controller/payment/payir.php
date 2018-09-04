<?php

class ControllerPaymentPayir extends Controller
{
	protected function index()
	{
		$this->id = 'payment';

		$this->load->language('payment/payir');
		$this->load->model('checkout/order');
		$this->load->library('encryption');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$encryption = new Encryption($this->config->get('config_encryption'));

		// if ($this->currency->getCode() != 'RLS') {

		// 	$this->currency->set('RLS');
		// }

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['return'] = $this->url->https('checkout/success');
		$this->data['cancel_return'] = $this->url->https('checkout/payment');

		$this->data['back'] = $this->url->https('checkout/payment');
		$amount =  @$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], false);
		
		if($order_info['currency'] != "RLS" && $order_info['currency'] != "IRR" && $this->currency->getCode() != 'RLS'&& $this->currency->getCode() != 'IRR'){
			$amount = $amount * 10;
		}

		if (extension_loaded('curl')) {

			$parameters = array (
				'api' => $this->config->get('payir_api'),
				'amount' =>$amount,
				'redirect' => urlencode($this->url->http('payment/payir/callback&order_id=' . $encryption->encrypt($order_info['order_id']))),
				'factorNumber' => $order_info['order_id']
			);

			$result = $this->common($this->config->get('payir_send'), $parameters);
			$result = json_decode($result);

			if (isset($result->status) && $result->status == 1) {

				$this->data['action'] = $this->config->get('payir_gateway') . $result->transId;

			} else {

				$code = isset($result->errorCode) ? $result->errorCode : 'Undefined';
				$message = isset($result->errorMessage) ? $result->errorMessage : $this->language->get('error_undefined');

				$this->data['error_warning'] = $this->language->get('error_request') . '<br/><br/>' . $this->language->get('error_code') . $code . '<br/>' . $this->language->get('error_message') . $message;
			}

		} else {

			$this->data['error_warning'] = $this->language->get('error_curl');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'payment/payir.tpl')) {

			$this->template = $this->config->get('config_template') . 'payment/payir.tpl';

		} else {

			$this->template = 'marketsaz/template/payment/payir.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function callback()
	{
		$this->data['error_warning'] = false;

		$this->load->language('payment/payir');
		$this->load->model('checkout/order');
		$this->load->library('encryption');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		

		$encryption = new Encryption($this->config->get('config_encryption'));

		// if ($this->currency->getCode() != 'RLS') {

		// 	$this->currency->set('RLS');
		// }

		if ($this->request->post['status'] && $this->request->post['transId'] && $this->request->post['factorNumber']) {

			$order_id =  $encryption->decrypt($this->request->get['order_id']);

			$status = $this->request->post['status'];
			$trans_id = $this->request->post['transId'];
			$factor_number = $this->request->post['factorNumber'];
			$message = $this->request->post['message'];

			if (isset($status) && $status == 1) {

				if ($order_id == $factor_number && $factor_number == $order_info['order_id']) {

					$parameters = array (
						'api' => $this->config->get('payir_api'),
						'transId' => $trans_id
					);

					$result = $this->common($this->config->get('payir_verify'), $parameters);
					$result = json_decode($result);

					if (isset($result->status) && $result->status == 1) {

						$amount = @$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], false);

						if($order_info['currency'] != "RLS" && $order_info['currency'] != "IRR" && $this->currency->getCode() != 'RLS'&& $this->currency->getCode() != 'IRR'){
							$amount = $amount * 10;
						}

						if ($amount == $result->amount) {

							$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('payir_order_status_id'), $trans_id);

						} else {

							$this->data['error_warning'] = $this->language->get('error_amount');
						}

					} else {

						$code = isset($result->errorCode) ? $result->errorCode : 'Undefined';
						$message = isset($result->errorMessage) ? $result->errorMessage : $this->language->get('error_undefined');

						$this->data['error_warning'] =  $this->language->get('error_request') . '<br/><br/>' . $this->language->get('error_code') . $code . '<br/>' . $this->language->get('error_message') . $message;
					}

				} else {

					$this->data['error_warning'] = $this->language->get('error_invoice');
				}

			} else {

				$this->data['error_warning'] = $this->language->get('error_payment');
			}

		} else {

			$this->data['error_warning'] = $this->language->get('error_data');
		}

		if ($this->data['error_warning'] && $this->data['error_warning'] != false) {

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {

				$this->data['base'] = HTTP_SERVER;

			} else {

				$this->data['base'] = HTTPS_SERVER;
			}

			$this->data['error_title'] = $this->language->get('error_title');
			$this->data['error_wait'] = sprintf($this->language->get('error_wait'), $this->url->https('checkout/cart'));
			$this->data['continue'] = $this->url->https('checkout/cart');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'payment/payir_failure.tpl')) {

				$this->template = $this->config->get('config_template') . 'payment/payir_failure.tpl';

			} else {

				$this->template = 'marketsaz/template/payment/payir_failure.tpl';
			}

			$this->render();

		} else {

			$this->redirect($this->url->https('checkout/success'));
		}
	}

	function common($url, $parameters)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
}
?>
