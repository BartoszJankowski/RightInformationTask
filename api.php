<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 13.06.2018
 * Time: 00:40
 */

include 'php/config.php';

/**
 * Endpoint REST API
 */

try {
	$rest = new RestApi($_REQUEST['request']);
	echo $rest->start();
} catch (Exception $e) {
	echo json_encode(Array('error' => $e->getMessage()));
}