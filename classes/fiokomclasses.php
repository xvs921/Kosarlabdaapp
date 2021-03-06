<?php
include("classes/adatbazisclasses.php");
class SajatFiok extends Adatbazis
{
	//FELHASZNÁLÓADATOK
	public function getFelhnev()
	{
		$this->sql = "SELECT felhasznalonev FROM felhasznalok WHERE id = '".$_SESSION["bejelentkezettAllapot"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["felhasznalonev"];
	}
	public function getEmail()
	{
		$this->sql = "SELECT email FROM felhasznalok WHERE id = '".$_SESSION["bejelentkezettAllapot"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["email"];
	}
	public function getCsapatAzon()
	{
		$this->sql = "SELECT `csapatok.id` FROM felhasznalok WHERE id = '".$_SESSION["bejelentkezettAllapot"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["csapatok.id"];
	}
	public function getCsapatnev($azon)
	{
		$this->sql = "SELECT nev FROM csapatok WHERE id = '".$azon."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		return $this->row["nev"];
	}
	//ADATMÓDOSÍTÁS
	public function setInaktiv()
	{
		$this->sql = "UPDATE felhasznalok SET aktiv=0 WHERE id = '".$_SESSION["bejelentkezettAllapot"]."'";
		$this->conn->query($this->sql);
		$_SESSION["bejelentkezettAllapot"] = "";
		?> <meta http-equiv="refresh" content="0; url = index.php"> <?php
	}
	public function setJelszo()
	{
		$this->sql = "SELECT jelszo FROM felhasznalok WHERE id ='".$_SESSION["bejelentkezettAllapot"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		if (password_verify($_POST["input_jelszo"], $this->row["jelszo"]) && strlen($_POST["input_ujJelszo"]) >= 10 && $_POST["input_jelszo"] != $_POST["input_ujJelszo"])
		{
			$this->sql = "UPDATE felhasznalok SET jelszo='".password_hash($_POST["input_ujJelszo"], PASSWORD_DEFAULT)."' WHERE id ='".$_SESSION["bejelentkezettAllapot"]."'";
			if ($this->conn->query($this->sql))
			{ 
				?> <script>alert("Sikeres jelszómódosítás!")</script> <?php
			}
			else
			{ 
				?> <script>alert("Hiba lépett fel, kérem végezze el újra!")</script> <?php
			}
		}
		else
		{
			if($_POST["input_jelszo"]==$_POST["input_ujJelszo"])
			{
				?> <script>alert("Nem változott a jelszava!")</script> <?php	
			}
			else if(!password_verify($_POST["input_jelszo"], $this->row["jelszo"]))
			{
				?> <script>alert("Helytelenül adta meg a jelenlegi jelszavát!")</script> <?php	
			}
			else if (strlen($_POST["input_ujJelszo"]) < 10)
			{
				?> <script>alert("A jelszó nem lehet 10 karakternél rövidebb!")</script> <?php	
			}
		}
	}
	public function adatModositas()
	{
		$this->sql = "SELECT felhasznalonev, email, cs.nev FROM felhasznalok f LEFT JOIN csapatok cs ON cs.id=f.`csapatok.id` WHERE f.id ='".$_SESSION["bejelentkezettAllapot"]."'";
		$this->result = $this->conn->query($this->sql);
		$this->row = $this->result->fetch_assoc();
		if($this->row["felhasznalonev"]!=$_POST["input_felhasznalonev"] || $this->row["email"]!=$_POST["input_email"] || $this->row["nev"]!=$_POST["input_csapatnev"])
		{
			$this->meglevoFelhasznalonevek = "SELECT * FROM felhasznalok WHERE felhasznalonev = '".$_POST["input_felhasznalonev"]."'";
			$this->meglevoEmailek = "SELECT * FROM felhasznalok WHERE email = '".$_POST["input_email"]."'";
			$this->meglevoCsapatnevek = "SELECT * FROM csapatok WHERE nev = '".$_POST["input_csapatnev"]."'";
			$this->felhasznalonevekLekeres = $this->conn->query($this->meglevoFelhasznalonevek);
			$this->emailekLekeres = $this->conn->query($this->meglevoEmailek);
			$this->csapatnevekLekeres = $this->conn->query($this->meglevoCsapatnevek);
			if ($this->felhasznalonevekLekeres->num_rows == 0 || $this->emailekLekeres->num_rows == 0 || $this->csapatnevekLekeres->num_rows == 0)
			{
				$this->sql = "UPDATE felhasznalok SET felhasznalonev='".$_POST["input_felhasznalonev"]."', email='".$_POST["input_email"]."' WHERE id ='".$_SESSION["bejelentkezettAllapot"]."'";
				$this->result = $this->conn->query($this->sql);
				
				$this->sql2 = "UPDATE csapatok SET nev='".$_POST["input_csapatnev"]."' WHERE id = (SELECT `csapatok.id` FROM felhasznalok WHERE id='".$_SESSION["bejelentkezettAllapot"]."')";
				$this->result = $this->conn->query($this->sql2);
				?> <meta http-equiv="refresh" content="0; url = fiokom.php"> <?php	
			}
			else
			{
				?> <script>alert("Sikertelen adatmódosítás!")</script> <?php	
			}	  
		}
		else
		{
			?> <script>alert("Nem változtak az adatai!")</script> <?php	
		}  
	}
} ?>