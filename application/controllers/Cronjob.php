<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	//@todo : action pada saat gagal bayar uniplay
	protected $apiKey = '7WxeugecFL2wgn2H2OuA';
	protected $mrcId = 'digi220201';	

    public function __construct()
	{
		parent::__construct();
		$this->load->model('order_model', 'order');
	}

    public function YWPQMRJENTDS() {
		$this->load->library('uniplay');

		// pengecekan ganti status mcpayment dari menunggu pembayaran, ke success atau expired, atau cancel dll
		$invoice = $this->order->getOrdersRequest();
		if(count($invoice) > 0) {
			foreach($invoice as $order) {
				$status = $this->detailOrder($order['transaction_id']);
				$status = json_decode($status,true);
				$order['status'] = $status['status'];
				if($status['status'] != 'REQUEST') {
					$statusmcpay['status_mcpay'] = $status['status'];
					$this->order->updateStatusMCpay($order['id'], $statusmcpay);
					echo "Order invoice number " . $order['invoice'] . " are ".$statusmcpay['status_mcpay']." from mcpayment";
					echo "<br>";
				}
			}
		}
		echo "<br>";

		// jika pembayaran mc payment success maka jalankan perintah pemesanan ke uniplay
		$invoice_success = $this->order->getOrdersSuccess();
		if(count($invoice_success) > 0) {
			foreach ($invoice_success as $key => $value) {
				$order_detail = $this->order->getOrderDetail($value['id']);
				if(count($order_detail) > 0) {
					foreach ($order_detail as $key_detail => $value_detail) {
						if($value_detail['kategori'] != 1) {
							$order_detail_id = $value_detail['id'];
							$get_detail_status_uniplay = $this->order->getOrderDetailUniplay($order_detail_id);
							if($get_detail_status_uniplay == NULL) {
								// kalo statusnya gagal coba lagi
								$product_id = $value_detail['product_id'];
								$denom_id = $value_detail['denom_id'];
								$player_id = $value_detail['player_id'];
								$server_id = $value_detail['server_id'];
								$get_produk_uniplay_id = $this->order->getProductUniplayID($product_id);
								$get_denom_uniplay_id = $this->order->getDenomUniplayID($denom_id);
								if($value_detail['kategori'] == 2) {
									$set_payment_inquiry = $this->uniplay->inquiry_payment_dtu($get_produk_uniplay_id, $get_denom_uniplay_id, $player_id, $server_id);
								} elseif($value_detail['kategori'] == 3) {
									$set_payment_inquiry = $this->uniplay->inquiry_payment_voucher($get_produk_uniplay_id, $get_denom_uniplay_id);
								}

								// insert inquiry to database
								$this->db->insert('orders_detail_uniplay', array("order_detail_id" => $order_detail_id, "inquiry_id" => $set_payment_inquiry->inquiry_id));
								$insert_id = $this->db->insert_id();

								$confirm_payment = $this->uniplay->confirm_payment($set_payment_inquiry->inquiry_id);
								
								$voucher_code = "";
								if(isset($confirm_payment->trx_resp_voucher_code)) {
									// $explode_voucher_code = explode(";", $confirm_payment->trx_resp_voucher_code);
									$voucher_code = $confirm_payment->trx_resp_voucher_code;
								}
								$this->db->update('orders_detail_uniplay', array(
										"order_id" => $confirm_payment->order_id, 
										"trx_date" => $confirm_payment->order_info->trx_date_order,
										"trx_number" => $confirm_payment->order_info->trx_number,
										"trx_item" => $confirm_payment->order_info->trx_item,
										"trx_price" => $confirm_payment->order_info->trx_price,
										"status_uniplay" => $confirm_payment->order_info->trx_status,
										"code_voucher" => $voucher_code
									), ['id' => $insert_id]
								);
								echo "Pemesanan Berhasil No Transaksi" . $confirm_payment->order_info->trx_number;
								echo "<br>";
							}
						}
					}
				}
			}
		}
		// pengecekan produk kategori barang uniplay dari detail order
		// error handling status
    }

	public function test() {
		$trx_voucher_code = "Code=1ueak2udsndi;SN=34234325234";
		echo $trx_voucher_code;

		$explode = explode(";", $trx_voucher_code);
		print_rr($explode);

		echo $explode[0];
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