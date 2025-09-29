<?php
require_once("koneksi.php");

class classParent {
	protected $mysqli; 

	public function __construct(){
		$this->mysqli = 
		new mysqli(SERVER, UID, PWD, DB);

		if ($this->mysqli->connect_error) {
            echo "Koneksi Gagal: " . $this->mysqli->connect_error;
        }
	}

	function __destruct(){
		$this->mysqli->close();
	}
}
?>