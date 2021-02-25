<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/../lib/Session.php');
include_once ($filepath . '/../lib/Database.php');
include_once ($filepath . '/../helpers/Format.php');

class User {
	private $db;
	private $fm;
	public function __construct() {
		$this->db = new Database();
		$this->fm = new Format();
	}

	public function userLogin($username, $password) {
		$username = $this->fm->validation($username);
		$password = $this->fm->validation($password);
		if ($username == "" || $password == "") {
			echo "empty";
			exit();
		} else {
			$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$data = $this->db->select($query);
			if ($data != false) {
				$value = $data->fetch_assoc();
				if ($value['status'] == '400') {
					echo "disable";
					exit();
				} else {
					Session::init();
					Session::set("login", true);
					Session::set("id", $value['id']);
					Session::set("username", $value['username']);
					Session::set("name", $value['name']);
					Session::set("nip", $value['nip']);
					Session::set("unit_id", $value['unit_id']);
					Session::set("level", $value['level']);
				}
			} else {
				echo "error";
				exit();
			}
		}
	}

	function getUser($id) {
		$id = $_GET['id'];
		$query = "SELECT users.id AS user_id, users.username, users.`password`, users.name, users.nip, users.`level`, users.unit_id, users.jabatan AS jabatan_id, users.pangkat_gol, CASE users.`status` WHEN 100 THEN 'ACTIVE' WHEN 400 THEN 'INACTIVE' ELSE 'UNKNOWN' END AS `status`, users.`status` AS status_id, unit.unit_long, CONCAT(pangkat.pangkat , ' / ', pangkat.gol) AS pangkat_golx, jabatan.jabatan FROM users INNER JOIN pangkat ON users.pangkat_gol = pangkat.id INNER JOIN jabatan ON users.jabatan = jabatan.jabatan_kode INNER JOIN unit ON users.unit_id = unit.id WHERE users.id = '$id' ";
		$result = $this->db->select($query);
		return mysqli_fetch_array($result);
	}
	
}
