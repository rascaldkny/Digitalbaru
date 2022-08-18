<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_uniplay extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		is_admin();
		$this->load->model('product_model', 'product');
	}
	
	public function index()
	{
    	$this->load->library('uniplay');

		$data['title']		= 'Products';

		// error handling jika API uniplay error
		
    	$get_saldo = $this->uniplay->inquiry_saldo();
    	$get_dtu = $this->uniplay->inquiry_dtu();
    	$get_voucher = $this->uniplay->inquiry_voucher();

    	if($get_saldo->status == "200") {

	    	$data['status']		= $get_saldo->status;
	    	$data['message']	= $get_saldo->message;
	    	$data['saldo']		= $get_saldo->saldo;
			$data['product']	= array_merge($get_voucher['list_product'], $get_dtu['list_product']);
			$data['page']		= 'pages/product_uniplay/index';
			$this->load->view('layouts/app', $data);

    	} else {

	    	$data['status']		= $get_saldo->status;
	    	$data['message']	= $get_saldo->message;
	    	$data['saldo']		= NULL;
			$data['product']	= array();
			$data['page']		= 'pages/product_uniplay/index';
			$this->load->view('layouts/app', $data);
    	}
	}

}

/* End of file Product.php */
