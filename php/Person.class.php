<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:03
 */

class Person {

	/**
	 * @var mixed
	 * fileds with names corresponding to table in DB
	 */
	private $id, $name, $address, $birth_date;

	/**
	 * @var PersonData
	 */
	private $personData;

	public function __construct(array $data) {
		$this->Update($data);
	}

	/**
	 * @param $name
	 * Magic function to obtain directly fields in Person and associated PersonData
	 * @return mixed
	 */
	public function __get( $name ) {
		if(isset($this->$name)){
			return  $this->$name;
		} else {
			return $this->personData->$name;
		}

	}

	/**
	 * @return mixed
	 * method required to return Person birth_date without useless time part
	 */
	public function GetBirthDate(){
		$var = explode(' ',$this->birth_date);
		return $var[0];
	}

	/**
	 * @param array $data
	 * takes array params to change values in existing object fields
	 */
	public function Update(array $data) {
		foreach($this as $k =>$v){
			if(isset($data[$k])){
				$this->$k = $data[$k];
			}
		}
		$this->personData = new PersonData($data);
	}

	/**
	 * @return array
	 * Converts object to array - includes field of type PersonData
	 */
	//TODO: reconsider making interface for objects Person and PersonData
	public function toArray() : array  {
		$result = [];
		foreach ($this as $k=>$v){
			if($v instanceof PersonData){
				$result[$k] = $v->toArray();
			} else {
				$result[$k] = $v;
			}

		}
		return $result;
	}

	/**
	 * @param array $personData
	 * @param $filters
	 *  method used to filters Person by PersonManager
	 * @return bool
	 */
	public static function Filter(array $personData, $filters) : bool {

		if(is_array($filters)){
			foreach($filters as $key => $sentence){
				if(!empty($sentence) && isset($personData[$key])){
					if(stripos($personData[$key], $sentence) === false){
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$res = '';
		foreach ($this as $k =>$v){
			$res .= $k.'='.$v."\r\n";
		}
		return $res;
	}
}