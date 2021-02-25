<?php 
define("DB_HOST", "192.168.146.248");
define("DB_USER", "data_miner");
define("DB_PASS", "thel0newolf");
define("DB_NAME", "db_sadap");

	$host	= DB_HOST;
	$db_user 	= DB_USER;
	$db_pass	= DB_PASS;
	$dbname	= DB_NAME; 



	$errors = array(); 

if(isset($_POST['register'])){

	try {    
		//create PDO connection 
		$db = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
	} catch(PDOException $e) {
		//show error
		die("Terjadi masalah: " . $e->getMessage());
	}

    // filter data yang diinputkan
    $name = strtoupper(filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING));
	// $name = preg_replace("/PT./"," ",$name);
    // $name = preg_replace("/CV./"," ",$name);
	$name = trim($name);
    $nip = filter_input(INPUT_POST, 'identitas', FILTER_SANITIZE_STRING);
	$nip = trim($nip);
    $nip = preg_replace('/[^0-9a-zA-Z]/', '', $nip);

	$arr = explode(' ',trim($name));
	
	if ($arr[0] == "PT." || $arr[0] == "CV." || $arr[0] == "PT" || $arr[0] == "CV"){
		$username = $arr[1] .  rand(300, 600);
	} else {
		$username = $arr[0] .  rand(300, 600);

	}

	// $password = rand(444, 555).$username;
	$password = md5('123456');
	$password_s = '123456';
	$level =2;
	$unit_id = 0;
	$jabatan = 96;
	$pangkat_gol = 12;
	$status = 100;

	  if (empty($name)) { array_push($errors, "Nama Perusahaan is required"); }
	  if (empty($nip)) { array_push($errors, "No NPWP is required"); }

		$sql = "SELECT * FROM users WHERE username=:username OR nip=:nip OR name=:name LIMIT 1";
		$stmt = $db->prepare($sql);
		
		// bind parameter ke query
		$params = array(
			":username" => $username,
			":nip" => $nip,
			":name" => $name
		);
		$stmt->execute($params);

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($data) { // if user exists
			if ($data['name'] === $name) {
			array_push($errors, "Perusahaan already exists");
			}

			if ($data['nip'] === $nip) {
			array_push($errors, "no Identitas already exists");
			}

			if ($data['username'] === $username) {
			array_push($errors, "username already exists");
			}
		}




    // menyiapkan query input
    $sql = "INSERT INTO users (username,password, name, nip, level, unit_id, jabatan, pangkat_gol, status) VALUES (:username, :password, :name, :nip, :level, :unit_id, :jabatan, :pangkat_gol, :status)";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":username" => $username,
        ":password" => $password,
        ":name" => $name,
        ":nip" => $nip,
        ":level" => $level,
        ":unit_id" => $unit_id,
        ":jabatan" => $jabatan,
        ":pangkat_gol" => $pangkat_gol,
        ":status" => $status
    );

    // eksekusi query untuk menyimpan ke database
	if (count($errors) == 0) {
		# code...
    	$saved = $stmt->execute($params);
		if($saved){
			$sql = "SELECT username, password FROM users WHERE username=:username";
			$stmt = $db->prepare($sql);
			
			// bind parameter ke query
			$params = array(
				":username" => $username
			);
				$stmt->execute($params);

				$user = $stmt->fetch(PDO::FETCH_ASSOC);

				$_POST['nama']= '';
				$_POST['identitas']= '';


		}else{
			echo 'error';
		};
	}


}

?>

<title>ADEK - APLIKASI DOKUMEN PELENGKAP PIB</title>


<div class="">

<h1>DAFTAR AKUN ADEK</h1>
	<form method="POST">
		<label for="" style="margin-right: 10px;">Nama Perusahaan</label>
		<input type="text" name="nama">
		<br>
		<label id="identitas" for="" style="margin-right: 53px;">NO NPWP</label>
		<input type="text" name="identitas">
		<br>
		<input id="login-submit" type="submit" value="submit" name="register" >
	</form>
	
		<?php  if (count($errors) > 0) : ?>
		<div class="error">
			<?php foreach ($errors as $error) : ?>
			<p><?php echo $error ?></p>
			<?php endforeach ?>
		</div>
		<?php  endif ?>

		<?php  if (isset($user)) : ?>
<div>
<pre>
Berikut Data User anda :

<b>mohon segera ganti password anda setelah login (menu > Penguna Jasa > update password)</b>

Username : <b><?php echo $user["username"] ?></b>

Password : <b><?php echo $password_s ?></b>

Aplikasi ADEK dapat diakses secara online melalui alamat <a href="http://app.bcsoetta.org/adek">http://app.bcsoetta.org/adek</a>

Manual Aplikasi :
1. Lakukan upload dokumen via menu Pengguna Jasa > Upload Dokumen 
2. Kirim dokumen tsb via menu Pengguna Jasa > Edit Kirim Remove
3. Cek status pengiriman di menu Pengguna Jasa > Cek Status
4. Petugas akan melakukan penerimaan dokumen dan mengupload bukti
   penerimaan dokumen yang dapat diunduh di menu Cek Status
5. Untuk Instruksi Pemeriksaan (apabila jalur merah), hubungi seksi pabean.
   Aplikasi ini tidak mengcover hal tsb
6. File yang diupload maksimal 32 MB
7. Dokumen Asli tetap harus disampaikan ke petugas Pendok.

</pre>
</div>
		<?php  endif ?>

<?php  if (isset($data) AND $data == true) : ?>
<div>
<pre>

Data User sudah ada:
Username: <?php echo $data["username"] ?>

Nama: <?php echo $data["name"] ?>

Identitas: <?php echo $data["nip"] ?>

</pre>
</div>

<?php  endif ?>

</div>