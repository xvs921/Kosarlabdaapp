<?php
  include("classes/fiokomclasses.php");
  $session = new SajatFiok();
  $session->sessionStart();
  $adatok = new SajatFiok();
  $adatok->connect();
?>
<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Felhasználói adatok</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">	 
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
	<link href="design/fiokomstyle.css" rel="stylesheet">
  </head>
  <table class="navbar">
	<tr>
		<td></td>
		<td id="visszaGomb"><button><a href="menu.php">Vissza</a></button></td>
	</tr>
  </table>
  <body>
  <div class="container-fluid">
	  <div class="row">
        <div class="col-md-4 col-md-offset-4 col-centered">
            <div class="login-panel">
				<center><h1 class="login-panel-title">Felhasználói adatok</h1></center>
                <div class="login-panel-section">
				<form method="POST">
                    <div class="form-group">
						<center><h5>Felhasználónév</h5></center>
                        <div class="input-group margin-bottom-sm">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" type="text" name="input_felhasznalonev" value="<?php echo $adatok->getFelhnev();?>">
                        </div>
                    </div>
                    <div class="form-group">
						<center><h5>Email</h5></center>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" type="text" name="input_email" value="<?php echo $adatok->getEmail();?>">
                        </div>
                    </div>
					<div class="form-group">
						<center><h5>Csapatnév</h5></center>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" type="text" name="input_csapatnev" value="<?php echo $adatok->getCsapatNev($adatok->getCsapatAzon());?>">
                        </div>
                    </div>
					<table>
						<tr>
							<td>
									<input type="hidden" name="action" value="Modositas">
									<input type="submit" class="btn btn-default" value="Módosítás" id="loginbtn"> 
							</td>
						</tr>
					</table>
				</form>
					<br />
					<table>
						<tr>
							<td>
								<form method="POST">
									<input type="hidden" name="action" value="jelszoModositas">
									<input type="submit" class="btn btn-default" value="Jelszó módosítás" id="loginbtn"> 
								</form>
							</td>
							<td>
								<form method="POST">
									<input type="hidden" name="action" value="Inaktivalas">
									<input type="submit" class="btn btn-default" value="Inaktiválás" id="loginbtn">
								</form>
							</td>
						</tr>
					</table>	
				</div>
				<?php
					$adatok->disconnect(); 
					if(isset($_POST["action"]) && $_POST["action"] == "jelszoModositas")
					{
				?><form method="POST">
					<div class="form-group">
						<center><h5>Jelszó</h5></center>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" type="password" placeholder="Jelenlegi jelszó" name="input_jelszo">
                        </div>
						<br />
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" type="password" placeholder="Új jelszó" name="input_ujJelszo">
                        </div>
						<table>
							<tr>
								<td>
									<input type="hidden" name="action" value="jelszoValtoztatas">
									<input type="submit" class="btn btn-default" value="Jóváhagyás" id="loginbtn"> 
								</td>
							</tr>
						</table>
                    </div>
				</form>
				<?php
					}
				?>
            </div>
        </div>
      </div>
	  </div>
	  <?php
	  	if(isset($_POST["action"]) && $_POST["action"] == "Inaktivalas")
		{
  			$adatok = new SajatFiok();
			$adatok->connect();
			$adatok->setInaktiv();
			$adatok->disconnect();
		}
	  	if(isset($_POST["action"]) && $_POST["action"] == "jelszoValtoztatas")
		{
  			$adatok = new SajatFiok();
			$adatok->connect();
			$adatok->setJelszo();
			$adatok->disconnect();
		}
	  	if(isset($_POST["action"]) && $_POST["action"] == "Modositas")
		{
  			$adatok = new SajatFiok();
			$adatok->connect();
			$adatok->adatModositas();
			$adatok->disconnect();
		}
	  ?>
  </body>
</html>