<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:40
 */

class PersonData {

	/**
	 * @var mixed
	 * fileds with names corresponding to table in DB
	 */
	private $person_id, $color, $website;

	public function __construct(array $data) {

		foreach($this as $k =>$v){
			if(isset($data[$k])){
				$this->$k =  $data[$k];
			}
		}
	}

	/**
	 * @param $name
	 * acces directly PersonData fields
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->$name;
	}

	/**
	 * @return array
	 * Converts object to array - includes field of type PersonData
	 */
	//TODO: reconsider making interface for objects Person and PersonData
	public function toArray() : array  {
		$result = [];
		foreach ($this as $k=>$v){
				$result[$k] = $v;
		}
		return $result;
	}
}