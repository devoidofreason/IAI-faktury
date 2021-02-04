<?php
class DBModel{

	private $sql;

	private function selectQuery(){
		$connection = new mysqli('localhost', 'root', '', 'faktury');
		if($connection->connect_errno){
			echo 'Nie udało się połączyć z bazą danych';
			exit();
		}
		if($result = $connection->query($this->sql)){
			$data = array();
			while($row = mysqli_fetch_assoc($result))
        		$data[] = $row;
        	$connection->close();
        	return json_encode($data);
		}
		else{
			echo 'Nie udało się wykonać:<br>';
			echo $this->$sql;
			exit();
		}
	}

	private function execQuery(){
		$connection = new mysqli('localhost', 'root', '', 'faktury');
		if($connection->connect_errno){
			echo 'Nie udało się połączyć z bazą danych';
			exit();
		}
		$connection->query($this->sql);
		$connection->close();
	}

	function getPositions(){
		$this->sql = 'SELECT * FROM pozycje';
		$ret = $this->selectQuery();
		echo $ret;
	}

	function insertPosition($netto, $vat, $opis){
		$this->sql = 'INSERT INTO pozycje (netto, vat, opis) VALUES (\''.$netto.'\', \''.$vat.'\', \''.$opis.'\')';
		$this->execQuery();
	}

	function deletePosition($id){
		$this->sql = 'SELECT * FROM faktury_pozycje WHERE id_pozycji = '.$id;
		$faktury_pozycje = json_decode($this->selectQuery());
		if(count($faktury_pozycje) != 0){
			echo 'Pozycja zawarta w fakturze.';
			exit();
		}
		$this->sql = 'DELETE FROM pozycje WHERE id = '.$id;
		$this->execQuery();
	}

	function getFaktury(){
		$this->sql = 'SELECT * FROM faktury';
		$ret = $this->selectQuery();
		echo $ret; 
	}

	function insertFaktura($imie, $nazwisko, $adres, $kod, $miasto, $nip, $telefon, $email, $pozycje){
		$this->sql = 'INSERT INTO faktury (imie, nazwisko, adres, kod, miasto, nip, telefon, email, data) VALUES (\''.$imie.'\', \''.$nazwisko.'\', \''.$adres.'\', \''.$kod.'\', \''.$miasto.'\', \''.$nip.'\', \''.$telefon.'\', \''.$email.'\', CURRENT_DATE())';
		$this->execQuery();
		$this->sql = 'SELECT MAX(id) AS id FROM faktury';
		$id_faktury = json_decode($this->selectQuery())[0]->id;
		foreach ($pozycje as $pozycja)
			foreach ($pozycja as $key => $value) {
				$this->sql = 'INSERT INTO faktury_pozycje (id_faktury, id_pozycji, ilosc) VALUES (\''.$id_faktury.'\', \''.$key.'\', \''.$value.'\')';
				$this->execQuery();
			}
	}

	function deleteFaktura($id){
		$this->sql = 'DELETE FROM faktury_pozycje WHERE id_faktury = '.$id;
		$this->execQuery();
		$this->sql = 'DELETE FROM faktury WHERE id = '.$id;
		$this->execQuery();
	}

	function getFaktura($id){
		$ret = array('faktura', 'pozycje');
		$ret['faktura'] = array();
		$ret['pozycje'] = array();
		$this->sql = 'SELECT * FROM faktury WHERE id = '.$id;
		$customer = json_decode($this->selectQuery());
		foreach ($customer as $i) 
			$ret['faktura'][] = $i;
		$this->sql = 'SELECT * FROM faktury_pozycje JOIN pozycje ON faktury_pozycje.id_pozycji = pozycje.id WHERE faktury_pozycje.id_faktury = '.$id;
		$pozycje = json_decode($this->selectQuery());
		foreach ($pozycje as $i) 
			$ret['pozycje'][] = $i;
		return $ret;
	}
}
?>