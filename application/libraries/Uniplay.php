<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uniplay {

	private $api_key = "5JLNKF9GXN90WYKKFHIYWH3VWZUDVREC5VWDS5";
	private $timestamp = "";
	private $upl_signature = "";
	private $access_token = "";
	private $pincode = "292929";

	public function __construct($rules = array()) {
		$this->ci =& get_instance();

		date_default_timezone_set("Asia/Bangkok");

		$this->timestamp		= date("Y-m-d H:i:s");
		
		$config = array(
			'ssl_verify_peer'   => false,
			'ssl_verif_host'    => false,
			'request_timeout'   => 30,
			'response_timeout'  => 90,
			'accept_cookies'    => false
		);

		$this->ci->load->library('Http', $config);

	}

	private function _object_to_array_product($obj_array = array(), $kategori = '', $kategori_id = 0) {
		// build array
		$build_array = array();
		foreach (json_decode($obj_array) as $key => $value) {
			if(is_array($value)) {
				foreach ($value as $key_detail => $value_detail) {
					$build_array['list_product'][$key_detail] = (array) $value_detail;
					$build_array['list_product'][$key_detail]['kategori'] = $kategori;
					$build_array['list_product'][$key_detail]['kategori_id'] = $kategori_id;
					$build_array['list_product'][$key_detail]['slug'] = createSlug($value_detail->name);
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

	private function get_signature($json_string) {
		$hmac_key 		= $this->api_key.'|'.$json_string;
		$upl_signature	= hash_hmac('sha512', $json_string, $hmac_key);
		return $upl_signature;
	}

	private function get_access_token() {
		$url			= 'https://api-reseller.uniplay.id/v1/access-token';
		$json_string 	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		));
		$headers		= array('UPL-SIGNATURE' => $this->get_signature($json_string));
		$response		= $this->ci->http->post($url, $json_string, $headers);
		return 			json_decode($response)->access_token;
	}

	public function inquiry_saldo()	{
		$url			= 'https://api-reseller.uniplay.id/v1/inquiry-saldo';
		$json_string 	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		));
		$headers		= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response		= $this->ci->http->post($url, $json_string, $headers);
		return			json_decode($response);
	}

	public function inquiry_dtu() {
		
		$url			= 'https://api-reseller.uniplay.id/v1/inquiry-dtu';
		$json_string 	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		));
		$headers		= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response		= $this->ci->http->post($url, $json_string, $headers);
		$data			= $this->_object_to_array_product($response, "Top Up Game", 2);
		return $data;
	}

	public function inquiry_voucher() {

		$url        = 'https://api-reseller.uniplay.id/v1/inquiry-voucher';
		$json_string 	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp
		));
		$headers 	= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string), 'Content-Type' => 'application/json');
		$response = $this->ci->http->post($url, $json_string, $headers);

		$data = $this->_object_to_array_product($response, "Voucher", 3);
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

		$url		= 'https://api-reseller.uniplay.id/v1/inquiry-payment';
		$json_string 		= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp,
			"entitas_id" => $entitas_id,
			"denom_id" => $denom_id,
			// "user_id" => NULL,
			// "server_id" => NULL,
		));
		$headers 	= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response = $this->ci->http->post($url, $json_string, $headers);
		return json_decode($response);
	}

	public function inquiry_payment_dtu($entitas_id = '', $denom_id = '', $player_id = '', $server_id = '') {
		// pemesanan produk voucher
		// voucher game_id, denom_id

		if($entitas_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Game ID Required")));
		} 

		if($denom_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Denom ID Required")));
		} 

		if($player_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Player ID Required")));
		} 

		$url		= 'https://api-reseller.uniplay.id/v1/inquiry-payment';
		$json_string 		= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp,
			"entitas_id" => $entitas_id,
			"denom_id" => $denom_id,
			"user_id" => $player_id,
			"server_id" => $server_id,
		));
		$headers 	= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response = $this->ci->http->post($url, $json_string, $headers);
		return json_decode($response);
	}

	public function confirm_payment($inquiry_id='') {
		// check order berdasarkan order id

		if($inquiry_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Inquiry ID Required")));
		} 
		
		$url			= 'https://api-reseller.uniplay.id/v1/confirm-payment';
		echo $json_string	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp, 
			"inquiry_id" => $inquiry_id, 
			"pincode" => $this->pincode
		));
		$headers 		= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response = $this->ci->http->post($url, $json_string, $headers);
		return json_decode($response);
	}

	public function check_order($order_id='') {
		// check order berdasarkan order id

		if($order_id == '') {
			return json_decode(json_encode(array("status" => "400", "message" => "Order ID Required")));
		} 
		
		$url			= 'https://api-reseller.uniplay.id/v1/check-order';
		echo $json_string	= json_encode(array(
			"api_key" => $this->api_key, 
			"timestamp" => $this->timestamp, 
			"order_id" => $order_id
		));
		$headers 		= array('UPL-ACCESS-TOKEN' => $this->get_access_token(), 'UPL-SIGNATURE' => $this->get_signature($json_string));
		$response = $this->ci->http->post($url, $json_string, $headers);
		return json_decode($response);
	}

	public function inquiry_payment_voucher_example($entitas_id = '', $denom_id = '') {
		// pemesanan produk voucher
		// voucher game_id, denom_id

		// if($entitas_id == '') {
		// 	return json_decode(json_encode(array("status" => "400", "message" => "Game ID Required")));
		// } 

		// if($denom_id == '') {
		// 	return json_decode(json_encode(array("status" => "400", "message" => "Denom ID Required")));
		// } 

		## Get Signature
			$timestamp = date("Y-m-d H:i:s");
			$api_key = "5JLNKF9GXN90WYKKFHIYWH3VWZUDVREC5VWDS5";	

		## Get Akses Token For List Vocher
			$body1_json 	= json_encode(array('api_key' => $api_key, 'timestamp' => $timestamp));
			$hmac_key = $api_key.'|'.$body1_json;
			$upl_signature = hash_hmac('sha512', $body1_json, $hmac_key);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api-reseller.uniplay.id/v1/access-token',
				CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POSTFIELDS => $body1_json,
				CURLOPT_HTTPHEADER => array(
					'UPL-SIGNATURE: '.$upl_signature.'',
					'Content-Type: application/json'
				),
			));
			$response1 = curl_exec($curl);
			curl_close($curl);
			$access_token = json_decode($response1)->access_token;

			$body2_json = json_encode(array("api_key" => $api_key, "timestamp" => $timestamp));
			$hmac_key = $api_key.'|'.$body2_json;
			$upl_signature = hash_hmac('sha512', $body2_json, $hmac_key);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api-reseller.uniplay.id/v1/inquiry-voucher',
				CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_SSL_VERIFYPEER => 0, 
				CURLOPT_POSTFIELDS => $body2_json,
				CURLOPT_HTTPHEADER => array(
					'UPL-ACCESS-TOKEN: '.$access_token.'',
					'UPL-SIGNATURE: '.$upl_signature.''
				),
			));
			$response2 = curl_exec($curl);
			curl_close($curl);

		## Get Akses Token For Inquiry Payment
			$body1_json 	= json_encode(array('api_key' => $api_key, 'timestamp' => $timestamp));
			$hmac_key = $api_key.'|'.$body1_json;
			$upl_signature = hash_hmac('sha512', $body1_json, $hmac_key);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api-reseller.uniplay.id/v1/access-token',
				CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POSTFIELDS => $body1_json,
				CURLOPT_HTTPHEADER => array(
					'UPL-SIGNATURE: '.$upl_signature.'',
					'Content-Type: application/json'
				),
			));
			$response1 = curl_exec($curl);
			curl_close($curl);
			$access_token = json_decode($response1)->access_token;

			$body3_json = json_encode(array("api_key" => $api_key, "timestamp" => $timestamp, "entitas_id" => json_decode($response2)->list_voucher[0]->id, "denom_id" => json_decode($response2)->list_voucher[0]->denom[0]->id));
			$hmac_key = $api_key.'|'.$body3_json;
			$upl_signature = hash_hmac('sha512', $body3_json, $hmac_key);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api-reseller.uniplay.id/v1/inquiry-payment',
			CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POSTFIELDS => $body3_json,
				CURLOPT_HTTPHEADER => array(
					'UPL-ACCESS-TOKEN: '.$access_token.'',
					'UPL-SIGNATURE: '.$upl_signature.''
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);

		print_r($response);

		// return json_decode($response);
	}

}