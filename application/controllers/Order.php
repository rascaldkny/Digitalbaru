<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	protected $apiKey = '7WxeugecFL2wgn2H2OuA';
	protected $mrcId = 'digi220201';	

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('order_model', 'order');
	}
	
	public function index()
	{
		$data['title']	= 'Orders';
		$data['page']	= 'pages/order/index';
		$data['invoice']= $this->order->getOrders();
		$data['orders'] = [];
		foreach($data['invoice'] as $order){
			if($order['status_mcpay'] != "SUCCESS") {
				$status = $this->detailOrder($order['transaction_id']);
				$status = json_decode($status,true);
				$order['status'] = $status['status'];

				if($status['status'] != 'REQUEST') { 
					$statusmcpay['status_mcpay'] = $status['status'];
					$this->order->updateStatusMCpay($order['id'], $statusmcpay);
				}
			} else {
				$order['status'] = $order['status_mcpay'];
			}
			array_push($data['orders'],$order);
		}
	
		$this->load->view('layouts/app', $data);
	}
	
	public function expired()
	{
		$data['title']	= 'Orders';
		$data['page']	= 'pages/order/index';
		$data['invoice']= $this->order->getOrderExpireds();
		$data['orders'] = $data['invoice'];
	
		$this->load->view('layouts/app', $data);
	}

	public function detail($id)
	{
		$data['title']				= 'Order Detail';
		$data['page']				= 'pages/order/detail';
		$data['order'] 			= $this->order->getOrderDetailById($id);
		$data['order_detail'] 	= $this->order->getOrderDetail($id);
		$data['detailTransaksi'] = json_decode($this->detailOrder($data['order']['transaction_id']),true);
		$this->load->view('layouts/app', $data);
	}

	public function update($id)
	{
		$data['status'] = $this->input->post('status');
		$this->order->updateStatus($id, $data);
		$this->session->set_flashdata('success', 'Data updated successfully.');

		redirect(base_url("order/detail/$id"));
	}
	protected function detailOrder($id)
	{
		$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.mcpayment.id/va/transactions/'.$id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'x-api-key:'.$this->apiKey
			),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}

}

/* End of file Order.php */
