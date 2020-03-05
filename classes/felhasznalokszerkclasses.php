
<?php
include("classes/adatbazisclasses.php");
class Session extends Adatbazis
{
	public function getFelhasznalokSzama()
	{
		$this->sql = "SELECT MAX(id) FROM felhasznalok";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["MAX(id)"];
	}
	public function getFelhasznalonev($azon)
	{
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["felhasznalonev"];
	}
	public function getEmail($azon)
	{
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		echo $this->row["email"];
	}
	public function getCsapatnev($azon)
	{
		$this->sql = "SELECT * FROM felhasznalok f 
		LEFT JOIN csapatok cs ON cs.id=f.`csapatok.id` WHERE f.id='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		echo $this->row["nev"];
	}
	public function getPenz($azon)
	{
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		echo $this->row["penz"];
	}
	public function getAktiv($azon)
	{
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["aktiv"];
	}
	public function getJogosultsag($azon)
	{
		$this->sql = "SELECT * FROM jogok WHERE `felhasznalok.id`='".$azon."'";
		$this->result = $this->conn->query($this->sql);
		return $this->result->num_rows;
	}
	public function setAktivitas($aktivitas)
	{
		$this->sql = "UPDATE felhasznalok SET aktiv='".$aktivitas."' WHERE id='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		?> <meta http-equiv="refresh" content="0; url = felhasznalokszerk.php"> <?php
	}
	public function felhasznaloModositas()
	{
		//FELHASZNÁLÓNÉV
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		if($this->row["felhasznalonev"]!=$_POST["input_felhasznalonev"])
		{
			$this->meglevoFelhasznalonevek = "SELECT * FROM felhasznalok WHERE felhasznalonev = '".$_POST["input_felhasznalonev"]."'";
			$this->felhasznalonevekLekeres = $this->conn->query($this->meglevoFelhasznalonevek);
			if ($this->felhasznalonevekLekeres->num_rows == 0)
			{
			$this->sql = "UPDATE felhasznalok SET felhasznalonev = '".$_POST["input_felhasznalonev"]."' WHERE id = '".$_POST["input_id"]."'";
			$this->result = $this->conn->query($this->sql);
			}
			else
			{?>
				<script>alert("Sikertelen adatmódosítás!")</script>
			<?php	
			}	  
	  	}
	//EMAIL
		$this->sql = "SELECT * FROM felhasznalok WHERE id='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		if($this->row["email"]!=$_POST["input_email"])
		{
			$this->meglevoEmailek = "SELECT * FROM felhasznalok WHERE email = '".$_POST["input_email"]."'";
			$this->emailekLekeres = $this->conn->query($this->meglevoEmailek);
			if ($this->emailekLekeres->num_rows == 0)
			{
			$this->sql = "UPDATE felhasznalok SET email = '".$_POST["input_email"]."' WHERE id = '".$_POST["input_id"]."'";
			$this->result = $this->conn->query($this->sql);
			}
			else
			{?>
				<script>alert("Sikertelen adatmódosítás!")</script>
			<?php	
			}	  
	  	}
		//CSAPATNÉV
		$this->sql = "SELECT nev FROM felhasznalok f LEFT JOIN csapatok cs ON cs.id=f.`csapatok.id` WHERE f.id='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		if($this->row["nev"]!=$_POST["input_csapatnev"])
		{
			$this->meglevoCsapatnevek = "SELECT * FROM csapatok WHERE nev = '".$_POST["input_csapatnev"]."'";
			$this->csapatnevekLekeres = $this->conn->query($this->meglevoCsapatnevek);
			if ($this->csapatnevekLekeres->num_rows == 0)
			{
			$this->sql = "UPDATE felhasznalok f LEFT JOIN csapatok cs ON cs.id=f.`csapatok.id` SET nev= '".$_POST["input_csapatnev"]."' WHERE f.id = '".$_POST["input_id"]."'";
			$this->result = $this->conn->query($this->sql);
			}
			else
			{?>
				<script>alert("Sikertelen adatmódosítás!")</script>
			<?php	
			}	  
	  	}
		//JOGOSULTSÁG
		$this->sql = "DELETE FROM jogok WHERE `felhasznalok.id`='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		
		if($_POST["input_jogosultsag"]==1)
		{
			$this->sql = "INSERT INTO jogok(`felhasznalok.id`, `jogosultsagok.id`) VALUES ('".$_POST["input_id"]."',1),('".$_POST["input_id"]."',2),('".$_POST["input_id"]."',3)";
			$this->result = $this->conn->query($this->sql);
		}
		else if($_POST["input_jogosultsag"]==2)
		{
			$this->sql = "INSERT INTO jogok(`felhasznalok.id`, `jogosultsagok.id`) VALUES ('".$_POST["input_id"]."',2),('".$_POST["input_id"]."',3)";
			$this->result = $this->conn->query($this->sql);
		}
		else if($_POST["input_jogosultsag"]==3)
		{
			$this->sql = "INSERT INTO jogok(`felhasznalok.id`, `jogosultsagok.id`) VALUES ('".$_POST["input_id"]."',3)";
			$this->result = $this->conn->query($this->sql);
		}
		
		//PÉNZ
		$this->sql = "UPDATE felhasznalok SET penz='".$_POST["input_penz"]."' WHERE id='".$_POST["input_id"]."'";
		$this->result = $this->conn->query($this->sql);
		
		//NEM VÁLTOZTAK AZ ADATOK
		if($this->row["felhasznalonev"]==$_POST["input_felhasznalonev"] and $this->row["email"]==$_POST["input_email"] and $this->row["nev"]==$_POST["input_csapatnev"] and $this->row["jogosultsag"]==$_POST["input_jogosultsag"])
		{
			?> <script>alert("Nem változtak az adatai!")</script> <?php	
		}
		?> <meta http-equiv="refresh" content="0; url = felhasznalokszerk.php"> <?php
	}
}
?>