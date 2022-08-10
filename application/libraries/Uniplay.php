<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uniplay {

	private $api_key = "8WREDOXJX4H5JMRLG4483INXXKJA4NBU1H1QXD";
	private $timestamp = "";
	private $upl_signature = "";

	public function __construct($rules = array()) {
		$this->ci =& get_instance();

		$this->timestamp = date("Y-m-d H:i:s");		
		$json_array = Array(
			"api_key" => $this->api_key,
			"timestamp" => $this->timestamp
		);
		$json_string = json_encode($json_array);
		$hmac_key = $this->api_key.'|'.$json_string;
		$this->upl_signature = hash_hmac('sha512', $json_string, $hmac_key);

		## Opsi 1 Curl
		// $this->ci->load->library('curl');
		// $result = $this->ci->curl->simple_get('https://api-reseller.uniplay.id/v1/access-token');
		// $this->curl->simple_post('https://api-reseller.uniplay.id/v1/access-token', array('foo'=>'bar'), array(CURLOPT_BUFFERSIZE => 10)); 

		// phpinfo();
		// die();
		// 
		## Opsi 2 Guzzle
		# guzzle client define
		// $this->ci->load->library('guzzle');
		// $client     = new GuzzleHttp\Client();

		// #This url define speific Target for guzzle
		// $url        = 'https://api-reseller.uniplay.id/v1/access-token';
		// $headers = [
		//   'UPL-SIGNATURE' => $this->upl_signature,
		//   'Content-Type' => 'application/json'
		// ];
		// $body = '{
		//   "api_key": "'.$this->api_key.'",
		//   "timestamp": "YYYY-MM-DD hh:mm:ss"
		// }';

		// #guzzle
		// try {
		// 	# guzzle post request example with form parameter
		// 	$response = $client->request( 'POST', $url, $headers, $body);
		// 	#guzzle repose for future use
		// 	echo $response->getStatusCode(); // 200
		// 	echo $response->getReasonPhrase(); // OK
		// 	echo $response->getProtocolVersion(); // 1.1
		// 	echo $response->getBody();
		// } catch (GuzzleHttp\Exception\BadResponseException $e) {
		// 	#guzzle repose for future use
		// 	$response = $e->getResponse();
		// 	$responseBodyAsString = $response->getBody()->getContents();
		// 	print_r($responseBodyAsString);
		// }

		## Opsi 3 Request Http (Running)
		// $config = array(
		//   'ssl_verify_peer'   => false,
		//   'ssl_verif_host'    => false,
		//   'request_timeout'   => 30,
		//   'response_timeout'  => 90,
		//   'accept_cookies'    => false
		// );

		// $this->ci->load->library('Http', $config);
		// $url        = 'https://api-reseller.uniplay.id/v1/access-token';
		// $headers 	= array('UPL-SIGNATURE' => $this->upl_signature);
		// $body 	= array('api_key' => $this->api_key, 'timestamp' => $this->timestamp);
		// $response = $this->ci->http->post($url, '{"api_key":"'.$this->api_key.'","timestamp":"'.$this->timestamp.'"}', $headers);
		// // $response = $this->ci->http->post($url, $body, $headers);
		// $hasil = json_decode($response);

		// // $url        = 'https://api-reseller.uniplay.id/v1/inquiry-saldo';
		// // $headers 	= array('UPL-ACCESS-TOKEN' => $hasil->access_token, 'UPL-SIGNATURE' => $this->upl_signature);
		// // $response = $this->ci->http->post($url, '{"api_key":"'.$this->api_key.'","timestamp":"'.$this->timestamp.'"}', $headers);
		// print_r($response);
		
		## Opsi 4 Curl Native
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api-reseller.uniplay.id/v1/access-token',
		  CURLOPT_RETURNTRANSFER => 1,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 90,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POST => 1,
		  CURLOPT_SSL_VERIFYPEER => 1,
		  CURLOPT_CAINFO, "/var/www-mitra/digitalbaru/application/libraries/cacert.pem",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_CONNECTTIMEOUT => 30,

		  CURLOPT_POSTFIELDS =>'{"api_key":"'.$this->api_key.'","timestamp":"'.$this->timestamp.'"}',
		  CURLOPT_HTTPHEADER => array(
		    'UPL-SIGNATURE: '.$this->upl_signature.'',
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		print_r($response);
		print_r($err);
	}

	public function test()	{
		// echo $this->upl_signature;
	}

}