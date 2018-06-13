<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 13.06.2018
 * Time: 00:57
 */

class RestApi
{

	private $request_method = '';
	private $server_endpoint = '';
	private $endpoint_args = Array();
	private $extra_var = '';



	public function __construct($request) {

		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Headers: *");
		header("Access-Control-Allow-Methods: *");
		header("Content-Type: application/json");

		$this->endpoint_args   = explode('/', rtrim($request, '/'));
		$this->server_endpoint = array_shift($this->endpoint_args);

		if ( array_key_exists(0, $this->endpoint_args) && !is_numeric($this->endpoint_args[0])) {
			$this->extra_var = array_shift($this->endpoint_args);
		}

		$this->request_method = $_SERVER['REQUEST_METHOD'];
		if ( $this->request_method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
				$this->request_method = 'DELETE';
			} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
				$this->request_method = 'PUT';
			}
		}
	}


	public function start() {
		if (method_exists($this, $this->server_endpoint)) {
			return $this->PrintResponse($this->{$this->server_endpoint}($this->endpoint_args));
		}
		return $this->PrintResponse("Error - endpoint not found: $this->server_endpoint", 404);
	}

	private function PrintResponse($data, $status = 200) {
		header("HTTP/1.1 " . $status . " " . $this->GetHttpStatus($status));
		return json_encode($data);
	}


	private function GetHttpStatus($code) {
		$status = array(
			200 => 'OK',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			500 => 'Internal Server Error',
		);
		return ($status[$code])?$status[$code]:$status[500];
	}

	/**
	 *  /person ednpoint
	 */
	protected function person() {
		$result  = [false];
		$ps = new PersonManagment();

		switch ($this->request_method){
			case 'GET':
				$this->request = test_input($_GET);
				if(empty($this->extra_var)){
					$ps->SelectAll(null);
					$result =  $ps->GetPersonAsArray();
				} else if( $this->extra_var == 'website'){
					$ps->SelectAll(null);
					$result = $ps->ListPersonValue('website');
				} else {
					$idVar = $this->GetIntID();
					$person = $ps->SelectPerson($idVar);
					if($person!=null){
						$result = $person->toArray();
					}
				}
				break;

			case 'PUT':
				$this->request = test_input($_GET);
				$idVar = $this->GetIntID();
				$result = [$ps->EditPerson($idVar, $this->request)];
				break;
			case 'DELETE':
				$idVar = $this->GetIntID();
				$result = [$ps->DeletePerson($idVar)];
				break;
		}

		return $result;
	}

	private function GetIntID(){
		$var = -1;
		if( $this->extra_var[0] == ':') {
			$v = substr($this->extra_var,1,strlen($this->extra_var));
			if(is_numeric($v)){
				$var = intval($v);
			}
		}
		return $var;
	}

}