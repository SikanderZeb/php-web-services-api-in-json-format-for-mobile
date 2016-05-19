<?php 
/**
 *  DB - A simple database class which will be register/login the users and send back response in JSON Format 
 *
 * @author		Author: Ayaz Ali Shah. (https://ae.linkedin.com/in/ayazalishah)
 * @git 		https://github.com/iamayaz/php-web-services-api-in-json-format-for-mobile.git
 * @version       	0.1
 * @Table		userTable
 */
class db{
	// Connection variable is using inside the class methods
	private $connection;
	// Connection Between db class and database
	public function __construct () {
		$host = 'localhost';
		$username = 'root';
		$password = '';
		$dbname = 'testdb';		
		$this->connection = new mysqli($host, $username, $password, $dbname);
		if ($this->connection->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
		}else{
			//echo 'Connected';	
		}
	}
	/* Register function will add only new user details*/
	final function register($u_username,$u_email,$u_mobile,$u_password,$u_gender,$u_language,$u_image){
		// For Security Purpose
		$error = 0;
		$response = array();
		$u_salt = substr( "aBcDefgHijKlMnoPqRsTuvWxYZ" ,mt_rand( 0 ,10 ) ,1 ) .substr( md5( time( ) ) ,1 ); 
		$u_password = md5(md5($u_password.$u_salt));
		// if email already exists
		if(!empty($u_email)){
			$u_email_exist = $this->isExist('u_email', $u_email, 'userTable');
			if($u_email_exist==1){
				$response[] = array('response' => $u_email . ' is already exists');
				$error = 1;
			}
		}
		// if mobile number already exists
		if(!empty($u_mobile)){
			$u_mobile_exist = $this->isExist('u_mobile', $u_mobile, 'userTable');
			if($u_mobile_exist==1){
				$response[] = array('response' => $u_mobile . ' is already exists');
				$error = 1;
			}
		}
		// Execute if there is no error*/
		if($error == 0){
			$sql = "INSERT INTO userTable (u_salt, u_username, u_email, u_mobile, u_password, u_gender, u_language, u_image, date_added) 
			VALUES ('".$u_salt."','".$u_username."', '".$u_email."', '".$u_mobile."', '".$u_password."', '".$u_gender."', '".$u_language."', '".$u_image."', '".date("Y-m-d H:i:s")."')";
			$dataInsert = mysqli_query($this->connection,$sql);
			if($dataInsert){
				$response[] = array('response' => 'success');
			}
			else{
				$response[] = array('response' => 'error');
			}
		}
		$output = array(
			'responseArray' => $response,
		);
		echo json_encode($output, JSON_PRETTY_PRINT);
	}
	// isExist will help to determine if same details already exists in table, this function will require three parameters
	final function isExist($columnName, $columnValue, $tableName){
		$columnName 	= (!empty($columnName)) ? $columnName : 'empty';
		$columnValue 	= (!empty($columnValue)) ? $columnValue : 'empty';
		$tableName 	= (!empty($tableName)) ? $tableName : 'empty';
		$exist = 0;
		if($columnName != 'empty' && $columnValue != 'empty' && $tableName != 'empty'){
			$sql = "select * from `".$tableName."` where `".$columnName."` = '".$columnValue."'";
			$isExist = mysqli_query($this->connection,$sql);
			if(mysqli_num_rows($isExist) > 0){
				$exist = 1;
				return $exist;	
			}
		}
	}
	// Login function will check the user credetional in the table and then send the response according to the status
	final function login($u_email, $u_mobile, $u_password){
		$error = 0;
		$response = array();
		$u_email 	= (!empty($u_email)) ? $u_email : 'empty';
		$u_mobile 	= (!empty($u_mobile)) ? $u_mobile : 'empty';
		$u_password 	= (!empty($u_password)) ? $u_password : 'empty';
		
		if(!empty($u_password)){
			if($u_email != 'empty'){
				$Salt = "select * from `userTable` where `u_email` = '".$u_email."'";
				$selectSalt = mysqli_query($this->connection,$Salt);
				$result = mysqli_fetch_assoc($selectSalt); 
				$userSalt = $result['u_salt'];
				$u_password = md5(md5($u_password.$userSalt));
				$user = "select * from `userTable` where `u_email` = '".$u_email."' and `u_password` = '".$u_password."'";
				$selectSalt = mysqli_query($this->connection,$user);
				if(mysqli_num_rows($selectSalt) > 0){
					$userResult = mysqli_fetch_assoc($selectSalt);
					if($userResult['u_status']==1){
						$response[] = array('response' => 'Successfully Login');
					}else{
						$response[] = array('response' => 'In-Active User');
					}
				}else{
					$response[] = array('response' => 'Invalid email or password');
				}
			}
			if($u_mobile != 'empty'){
				$Salt = "select * from `userTable` where `u_mobile` = '".$u_mobile."'";
				$selectSalt = mysqli_query($this->connection,$Salt);
				$result = mysqli_fetch_assoc($selectSalt); 
				$userSalt = $result['u_salt'];
				$u_password = md5(md5($u_password.$userSalt));
				$user = "select * from `userTable` where `u_mobile` = '".$u_mobile."' and `u_password` = '".$u_password."'";
				$selectSalt = mysqli_query($this->connection,$user);
				if(mysqli_num_rows($selectSalt) > 0){
					$userResult = mysqli_fetch_assoc($selectSalt);
					if($userResult['u_status']==1){
						$response[] = array('response' => 'Successfully Login');
					}else{
						$response[] = array('response' => 'In-Active User');
					};
				}else{
					$response[] = array('response' => 'Invalid Mobile Number or password');
				}
			}
		}else{
			$response[] = array('response' => 'Password must have some value');
			$exist = 1;
		}
		$output = array(
			'responseArray' => $response,
		);
		echo json_encode($output, JSON_PRETTY_PRINT);
	}
	
	public function delete_user($userID){
		// here you'll write delete user code
	}
}



?>
