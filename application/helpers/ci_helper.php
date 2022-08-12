<?php

function is_login(){
	$CI =& get_instance();
	
	if($CI->session->userdata('login') == FALSE){
		$CI->session->set_flashdata('error', 'Please sign in.');
		redirect('login');
	}
}
	
function is_admin()
{
	$CI =& get_instance();

	is_login();
	
	if($CI->session->userdata('role') != 1){
		redirect('errors');
	}
}

function hashEncrypt($input){

	$hash = password_hash($input, PASSWORD_DEFAULT);
	
	return $hash;
}
	
function hashEncryptVerify($input, $hash){
	
	if(password_verify($input, $hash)){
	  return true;
	}else{
	  return false;
	}
}

function dd($input) {
	var_dump($input);
	die;
}

if(!function_exists('print_rr')){
	function print_rr($array){
		$count = count($array);
		if(($count) > 0) {
			foreach($array as $key=>$value){
				if(is_array($value)){
					$id = md5(rand());
					echo '[<a href="#" onclick="return expandParent(\''.$id.'\')">'.$key.'</a>]<br />';
					echo '<div id="'.$id.'" style="display:none;margin:10px;border-left:1px solid; padding-left:5px;">';
					print_rr($value, $count);
					echo '</div>';
				} else {
					echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;$key</b>: ".htmlentities($value)."<br />";
				}
			}
			echo '
			<script language="Javascript">
				function expandParent(id){
					toggle="block";
					if(document.getElementById(id).style.display=="block"){
						toggle="none"
					}
					document.getElementById(id).style.display=toggle
					return false;
				};
			</script>
			';
		} else {
			echo "data kosong";
		}
	}
}

