<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 21:22
 */

/**
 * Commonly used function
 */

function test_input($data) {
	if(is_array($data)){
		foreach ($data as $k=>$v){
			$data[$k] = test_input($v);
		}
	} else {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
	}

	return $data;
}