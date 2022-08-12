<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uniplay {

	private $api_key = "5JLNKF9GXN90WYKKFHIYWH3VWZUDVREC5VWDS5";
	private $timestamp = "";
	private $upl_signature = "";
	private $access_token = "";

	public function __construct($rules = array()) {
		$this->ci =& get_instance();

		date_default_timezone_set("Asia/Bangkok");

		$this->timestamp = date("Y-m-d H:i:s");		
		$json_array = Array(
			"api_key" => $this->api_key,
			"timestamp" => $this->timestamp
		);
		$json_string = json_encode($json_array);
		$hmac_key = $this->api_key.'|'.$json_string;
		$this->upl_signature = hash_hmac('sha512', $json_string, $hmac_key);
		
		$config = array(
			'ssl_verify_peer'   => false,
			'ssl_verif_host'    => false,
			'request_timeout'   => 30,
			'response_timeout'  => 90,
			'accept_cookies'    => false
		);

		$this->ci->load->library('Http', $config);

	}

	private function _object_to_array_product($obj_array = array()) {
		// build array
		$build_array = array();
		foreach (json_decode($obj_array) as $key => $value) {
			if(is_array($value)) {
				foreach ($value as $key_detail => $value_detail) {
					$build_array['list_product'][$key_detail] = (array) $value_detail;
					foreach ($value_detail->denom as $key_denom => $value_denom) {
						$build_array['list_product'][$key_detail]['denom'][$key_denom] = (array) $value_denom;
					}
				}
			} else {
				$build_array[$key] = $value;
			}
		}
		return $build_array;
	}

	private function get_access_token() {
		$url        = 'https://api-reseller.uniplay.id/v1/access-token';
		$headers 	= array('UPL-SIGNATURE' => $this->upl_signature);
		$body 	= array('api_key' => $this->api_key, 'timestamp' => $this->timestamp);
		$response = $this->ci->http->post($url, '{"api_key":"'.$this->api_key.'","timestamp":"'.$this->timestamp.'"}', $headers);
		return json_decode($response)->access_token;
	}

	public function inquiry_saldo()	{
		// get saldo

		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-saldo';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature,
		);
		$body 		= array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);
		return json_decode($response);
	}

	public function inquiry_dtu() {
		// listing product dtu
		
		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-dtu';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature,
		);
		$body 		= array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);

		$data = $this->_object_to_array_product($response);
		return $data;
	}

	public function inquiry_voucher() {
		// listing product voucher

		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-voucher';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature,
			'Content-Type' => 'application/json'
		);
		$body 		= array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);
		print_r($response);
		$data = $this->_object_to_array_product($response);
		return $data;
	}

	public function inquiry_payment_voucher($entitas_id = '', $denom_id = '') {
		// pemesanan produk voucher
		// voucher game_id, denom_id

		if($entitas_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Game ID Required")));
		} 

		if($denom_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Denom ID Required")));
		} 

		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-payment';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature,
			'Content-Type' => 'application/json'
		);

		$body 		= array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp,
			"entitas_id" => NULL,
			// "denom_id" => $denom_id,
			// "user_id" => NULL,
			// "server_id" => NULL,
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);
		return json_decode($response);
	}

	public function inquiry_payment_dtu() {
		// pemesanan produk dtu
		// dtu game_id, denom_id, user_id, server_id

		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-voucher';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature
		);
		$body 		= array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp,
			"entitas_id" => $entitas_id,
			"denom_id" => $denom_id,
			"user_id" => $user_id,
			"server_id" => $server_id
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);
		return json_decode($response);
	}

	public function check_order($order_id='') {
		// check order berdasarkan order id

		if($order_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Order ID Required")));
		} 

		$url        = 'https://api-reseller.uniplay.id/v1/check-order';
		$headers 	= array(
			'UPL-ACCESS-TOKEN' => $this->get_access_token(), 
			'UPL-SIGNATURE' => $this->upl_signature
		);
		$body = array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp, 
			"order_id" => $order_id
		);
		$body_json = json_encode($body);

		$response = $this->ci->http->post($url, $body_json, $headers);
		return json_decode($response);
	}

}