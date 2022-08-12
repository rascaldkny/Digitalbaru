<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dev extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		is_admin();
	}

    public function index() {
    	$this->load->library('uniplay');

    	// $get_saldo = $this->uniplay->inquiry_saldo();
    	// print_r($get_saldo);die;

    	// $get_dtu = $this->uniplay->inquiry_dtu();
    	// print_rr($get_dtu);

    	$get_voucher = $this->uniplay->inquiry_voucher();
    	// print_r($get_voucher);

    	$game_id = $get_voucher['list_product'][0]['id'];
    	$denom_id = $get_voucher['list_product'][0]['denom'][0]['id'];
    	// $game_id = 1;
    	// $denom_id = 1;
    	// $set_payment_voucher = $this->uniplay->inquiry_payment_voucher($game_id,$denom_id);
    	// print_r($set_payment_voucher);

    	// $order_id = '0c9c733718a3d76af0e7a2fa6548986705030dfded5a85c080de0426a29831ad5c8c79e69d15415b759801bcf53b2aaeabca5faefc6c42d80177061415758a99';
    	// $check_order = $this->uniplay->check_order($order_id);
    	// print_r($check_order);
    }


}