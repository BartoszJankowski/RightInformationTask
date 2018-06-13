<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:10
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE );

//Pull '$base_url' and '$signin_url' from this file
spl_autoload_register(function ($class_name) {
	$dbCls = explode('_',$class_name);
	if(!empty($dbCls[0]) && $dbCls[0] === 'Db'){
		include 'php/db/'.$class_name . '.php';
	} else {
		include 'php/'.$class_name . '.class.php';
	}
});

include 'php/functions.php';