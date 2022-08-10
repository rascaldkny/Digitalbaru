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

    	$this->uniplay->test();
    }


}