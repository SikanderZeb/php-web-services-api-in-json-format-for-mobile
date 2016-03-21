<?php 
// DB class will be require for the login/registeration functionality
require('db.php');
class response extends db{
	public function addUser($u_username,$u_email,$u_mobile,$u_password,$u_gender,$u_language,$u_image){
		$this->register($u_username,$u_email,$u_mobile,$u_password,$u_gender,$u_language,$u_image);
	}	
	public function loginUser($u_email,$u_mobile,$u_password){
		$this->login($u_email,$u_mobile,$u_password);
	}
}
// GETTING PARAMETER FROM REQUEST
// Will recieve requests from Mobile
$u_username 	= (isset($_REQUEST['u_username']))? (string)$_REQUEST['u_username']:'';
$u_email 		= (isset($_REQUEST['u_email']))? (string)$_REQUEST['u_email']:'';
$u_mobile 		= (isset($_REQUEST['u_mobile']))? (string)$_REQUEST['u_mobile']:'';
$u_password 	= (isset($_REQUEST['u_password']))? $_REQUEST['u_password']:'';
$u_gender 		= (isset($_REQUEST['u_gender']))? (string)$_REQUEST['u_gender']:'';
$u_language 	= (isset($_REQUEST['u_language']))? (string)$_REQUEST['u_language']:'';
$u_image 		= (isset($_REQUEST['u_image']))? $_REQUEST['u_image']:'';

// Below are the two specific parameters, function will be execute according to the parameter 
$register 		= (isset($_REQUEST['register']))? $_REQUEST['register']:'not';
$login 			= (isset($_REQUEST['login']))? $_REQUEST['login']:'not';
$response 		= new response();
// addUser function will be execute if $register have some value
if($register != 'not'){
	$response->addUser($u_username,$u_email,$u_mobile,$u_password,$u_gender,$u_language,$u_image);
}
// loginUser function will be execute if $login have some value
if($login != 'not'){
	$response->loginUser($u_email,$u_mobile,$u_password);
}
?>