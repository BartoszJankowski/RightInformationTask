<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:13
 */

/*
 * Class for DB connections
 */
abstract class Db_Conn {



	protected $host = "localhost";
	protected $username = "bart1232";
	protected $password = "r0w3r0123";
	protected $db_name = "bart1232_task";

	protected static $CONN;


	public function __construct()
	{
		if(!isset(self::$CONN)){

			try {

				// Connect to server and select database.
				self::$CONN = new PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
				self::$CONN->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			} catch (Exception $e) {
				$this->error = $e->getMessage();
				die('Database connection error');
			}
		}
	}

}