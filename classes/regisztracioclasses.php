<?php
include("classes/adatbazisclasses.php");
class Regiszt extends Adatbazis
{
	public function regisztracio()
	{
		$this->meglevoFelhasznalonevek = "SELECT * FROM felhasznalok WHERE felhasznalonev = '".$_POST["input_felhasznalonev"]."'";
		$this->meglevoEmailek = "SELECT * FROM felhasznalok WHERE email = '".$_POST["input_email"]."'";
		$this->felhasznalonevekLekeres = $this->conn->query($this->meglevoFelhasznalonevek);
		$this->emailekLekeres = $this->conn->query($this->meglevoEmailek);

		if ($this->felhasznalonevekLekeres->num_rows == 0 && $this->emailekLekeres->num_rows == 0 && strlen($_POST["input_jelszo"]) >= 10 && $_POST["input_jelszo"] == $_POST["input_jelszo_ujra"] && filter_var($_POST["input_email"], FILTER_VALIDATE_EMAIL))
		{
			$this->sql = "SELECT MAX(id)+1 FROM csapatok";
			$this->result = $this->conn->query($this->sql);
			$this->row = $this->result->fetch_assoc();
			$ujCsapatId=$this->row["MAX(id)+1"];
			//CSAPAT
			$this->sql = "INSERT INTO csapatok(nev, lejatszott, nyert, vesztett) VALUES ('".$_POST["input_felhasznalonev"]."',0,0,0)";
			$this->conn->query($this->sql);
			//FELHASZNÁLÓ
			$this->sql = "INSERT INTO felhasznalok(felhasznalonev, jelszo, email, `csapatok.id`, penz, aktiv) VALUES ('".$_POST["input_felhasznalonev"]."', '".password_hash($_POST["input_jelszo"], PASSWORD_DEFAULT)."', '".$_POST["input_email"]."','".$ujCsapatId."',15000,1)";

			if ($this->conn->query($this->sql))
			{ 
				//JOGOK
				$this->sql = "INSERT INTO jogok(`felhasznalok.id`, `jogosultsagok.id`) VALUES ((SELECT MAX(id) FROM felhasznalok),3)";
				$this->conn->query($this->sql);
				//5 JÁTÉKOS A CSAPATHOZ
				$jatekosSzam = 0;
				$nemCsapattag=0;
				do {
					$this->sql = "SELECT id FROM jatekosok ORDER BY RAND() LIMIT 1";
					$this->result = $this->conn->query($this->sql);
					$this->row = $this->result->fetch_assoc();
					$ujJatekosAzon=$this->row["id"];

					$this->sql = "SELECT id FROM csapattagok WHERE `csapatok.id`='".$ujCsapatId."' AND `jatekosok.id`='".$ujJatekosAzon."'";			 
					$this->result = $this->conn->query($this->sql);
					if($this->result->num_rows == 0)
					{
						$nemCsapattag=1;
					}

					$this->sql = "SELECT MAX(sorszam)+1 FROM csapattagok WHERE `csapatok.id`='".$ujCsapatId."'";
					$this->result = $this->conn->query($this->sql);
					$this->row = $this->result->fetch_assoc();
					
					if($this->row["MAX(sorszam)+1"]>0)
					{
						$ujJatekosSorszam=$this->row["MAX(sorszam)+1"];
					}
					else
					{
						$ujJatekosSorszam=1;
					}
					
					if($nemCsapattag==1)
					{
						$this->sql = "INSERT INTO csapattagok(`csapatok.id`, `jatekosok.id`, `sorszam`, `kezdo`) VALUES ('".$ujCsapatId."', '".$ujJatekosAzon."','".$ujJatekosSorszam."',1)";
						$this->conn->query($this->sql);	
						$jatekosSzam++;
						$nemCsapattag=0;  
					}				  
				} while ($jatekosSzam<5);
				
				?> <script>alert("Sikeres regisztráció!")</script>
				<meta http-equiv="refresh" content="1; url = index.php"> <?php
			}
			else
			{ 
				?> <script>alert("Hiba lépett fel, kérem végezze el újra!")</script> <?php
			}
		}
		else
		{
			if ($_POST["input_jelszo"] != $_POST["input_jelszo_ujra"])
			{
				?> <script>alert("Nem egyezik a két jelszó!")</script> <?php	
			}
			else if (strlen($_POST["input_jelszo"]) < 10)
			{
				?> <script>alert("A jelszó nem lehet 10 karakternél rövidebb!")</script> <?php	
			}
			else if ($this->felhasznalonevekLekeres->num_rows != 0)
			{
				?> <script>alert("Ez a felhasználónév foglalt!")</script> <?php	
			}
			else if ($this->emailekLekeres->num_rows != 0)
			{
				?> <script>alert("Ez az email cím foglalt!")</script> <?php	
			}
			else if (!filter_var($_POST["input_email"], FILTER_VALIDATE_EMAIL)) 
			{
				?> <script>alert("Nem jó email címet adott meg!")</script> <?php	
			}
		}
	}
} ?>