<?php 
	function getData() {
		$monfichier = fopen('Contact.txt', 'a+');

		fseek($monfichier, feof($monfichier));
		$source = fgets($monfichier);

		$datas = explode('|', $source);

		$data = array();
		
		foreach ($datas as $value) {
			if ($value != "") {
				array_push($data, $value);
			}
		}

		$datas = $data;

		$contactes = array();
		$array1 = array();

		$i = 0;

		foreach ($datas as $contacte) {
			$contacte = explode(',', $contacte);
			
			foreach ($contacte as $value) {
				$array = explode(':', $value);
				array_push($array1, array($array[0] => $array[1]));
			}

			$contacte = $array1;
			
			$_id = $_nom = $_prenom = $_portable = $_fix = $_ville = "";

			foreach ($contacte as $value) {
				if(isset($value['Id'])){$_id = $value['Id'];}
				if(isset($value['Nom'])){$_nom = $value['Nom'];}
				if(isset($value['Prenom'])){$_prenom = $value['Prenom'];}
				if(isset($value['Portable'])){$_portable = $value['Portable'];}
				if(isset($value['Fix'])){$_fix = $value['Fix'];}
				if(isset($value['Ville'])){$_ville = $value['Ville'];}
			}		
			
			$contacte = array(
				"id" => $_id,
				"nom" => $_nom,
				"prenom" => $_prenom,
				"portable" => $_portable,
				"fix" => $_fix,
				"ville" => $_ville 
			);

			$contactes[$i] = $contacte;

			$i++;
		}

		fclose($monfichier);
		
		return $contactes;	
	}

	$contacts = getData();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="assets/css/bootstrap4.min.css">
		<link rel="stylesheet" href="assets/css/font-awesome.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE-edge">
		<meta charset="utf-8">
		<title>Tp-partie1</title>
	</head>
	<body>
		<header>
			<nav class="navbar navbar-expand-sm navbar-dark bg-dark" style="border-radius: 5px;">
			  <a class="navbar-brand" href="#">Tp-php</a>
			</nav>
		</header>
		<?php 
			session_start();

			if (isset($_POST['create'])) {
				$_SESSION['message'] = "";
				$_SESSION['time'] = 0;
				$monfichier = fopen('Contact.txt', 'a+');

				$_id = $_POST['id'];
				$_nom = $_POST['nom'];
				$_prenom = $_POST['prenom'];
				$_portable = $_POST['portable'];
				$_fix = $_POST['fix'];
				$_ville = $_POST['ville'];

				fseek($monfichier, feof($monfichier));

				$verif = true;

				foreach ($contacts as $contact) {
					if ($contact['id'] === $_id) {
						$verif = false;
					}
				}

				if($verif == false){
					$_SESSION['message'] = '<br/><p class="card-text text-warning alert alert-info text-center">Contact déjà existé !</p>';
				}

				if($verif == true){
					$result = fputs($monfichier,
						"Id:" . $_id . "," . 
						"Nom:" . $_nom . "," .
						"Prenom:" . $_prenom . "," .
						"Portable:" . $_portable . "," .
						"Fix:" . $_fix . "," .
						"Ville:" . $_ville . "|");

					if($result != false){$_SESSION['message'] = '<br/><p class="card-text text-success alert alert-info text-center">L\'ajout est terminé avec succés</p>';}
				}

				fclose($monfichier);

				$_SESSION['action'] = true;

				header('location: index.php');
			}

			if (isset($_POST['modify'])) {
				$contactes = getData();

				for ($i = 0; $i < count($contactes); $i++) {					
					if ($contactes[$i]['id'] == $_POST['id']) {
						$contactes[$i]['nom'] = $_POST['nom'];
						$contactes[$i]['prenom'] = $_POST['prenom'];
						$contactes[$i]['portable'] = $_POST['portable'];
						$contactes[$i]['fix'] = $_POST['fix'];
						$contactes[$i]['ville'] = $_POST['ville'];
					}
				}

				$monfichier = fopen('Contact.txt', 'a+');

				ftruncate($monfichier, feof($monfichier));

				foreach ($contactes as $contacte) {
					$result = fputs($monfichier,
						"Id:" . $contacte['id'] . "," . 
						"Nom:" . $contacte['nom'] . "," .
						"Prenom:" . $contacte['prenom'] . "," .
						"Portable:" . $contacte['portable'] . "," .
						"Fix:" . $contacte['fix'] . "," .
						"Ville:" . $contacte['ville'] . "|");
					fseek($monfichier, feof($monfichier));
				}

				if($result != false){$_SESSION['message'] = '<br/><p class="card-text text-success alert alert-info text-center">La modification est terminée avec succées</p>';}

				fclose($monfichier);

				$_SESSION['action'] = true;

				header('location: index.php');
			}

			if (isset($_POST['delete'])) {
				$contactes = getData();

				for ($i = 0; $i < count($contactes); $i++) {					
					if ($contactes[$i]['id'] == $_POST['id']) {
						$contactes[$i]['id'] = "";
						$contactes[$i]['nom'] = "";
						$contactes[$i]['prenom'] = "";
						$contactes[$i]['portable'] = "";
						$contactes[$i]['fix'] = "";
						$contactes[$i]['ville'] = "";
					}
				}

				$monfichier = fopen('Contact.txt', 'a+');

				ftruncate($monfichier, feof($monfichier));

				foreach ($contactes as $contacte) {
					if ($contacte['id'] != "" && $contacte['nom'] != "" && $contacte['prenom'] != "" && 
						$contacte['portable'] != "" && $contacte['fix'] != "" && $contacte['ville'] != "") {
						
						$result = fputs($monfichier,
							"Id:" . $contacte['id'] . "," . 
							"Nom:" . $contacte['nom'] . "," .
							"Prenom:" . $contacte['prenom'] . "," .
							"Portable:" . $contacte['portable'] . "," .
							"Fix:" . $contacte['fix'] . "," .
							"Ville:" . $contacte['ville'] . "|");
						fseek($monfichier, feof($monfichier));

					}
				}

				if($result != false){$_SESSION['message'] = '<br/><p class="card-text text-success alert alert-info text-center">La suppression est terminée avec succées</p>';}

				$_SESSION['action'] = true;

				fclose($monfichier);

				header('location: index.php');
			}

			if (isset($_POST['search'])) {
				$array = array();
				foreach ($contacts as $contact) {
					if ($contact['ville'] == $_POST['ville']){
						array_push($array, $contact);
					}
				}
				$contacts = $array;
			}

			if (isset($_SESSION['action']) && $_SESSION['action'] == true) {
				echo $_SESSION['message'];
				$_SESSION['time'] += 1;
			}

			if (isset($_SESSION['time']) && $_SESSION['time'] == 2) {
				$_SESSION['action'] = "";
				$_SESSION['time'] = 0;
			}

		?>
		<div class="card text-center" style="margin: 30px 70px;">
			<div class="card-header">
	    		<h5 class="card-title">Gestion des contacts</h5>
		    	<h6 class="card-subtitle mb-2 text-muted"></h6>
	  		</div>
	  		<div class="card-body">
	  			<form method="POST">
	  				<a href="index.php" class="btn btn-info btn-link float-right"><i class="fa fa-refresh"></i> Actualiser</a>
	  				<button class="btn btn-info btn-link float-right" style="margin-right: 10px;" type="submit" name="search"><i class="fa fa-search"></i> Rechercher</button>
	  				<input class="float-right" id="VILLE" type="text" name="ville" value="" style="margin-right: 10px;">
	  				<label class="float-right input" for="VILLE" style="margin-right: 10px;">Ville : </label>
	  			</form>
	  			<br><br>
				<table class="table table-hover table-bordered" >
					<thead class="thead-dark">
						<tr>
							<th>Id</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Portable</th>
							<th>Fix</th>
							<th>Ville</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($contacts as $contact) : ?>
							<form method="POST">
								<tr>
									<th scope="row">
										<input readonly="readonly" class="form-control" type="text" name="id" value="<?= $contact["id"]; ?>">
									</th>
									<td>
										<input class="form-control" type="text" name="nom" value="<?= $contact["nom"]; ?>">
									</td>
									<td>
										<input class="form-control" type="text" name="prenom" value="<?= $contact["prenom"]; ?>">
									</td>
									<td>
										<input class="form-control" type="text" name="portable" value="<?= $contact["portable"]; ?>">
									</td>
									<td>
										<input class="form-control" type="text" name="fix" value="<?= $contact["fix"]; ?>">
									</td>
									<td>
										<input class="form-control" type="text" name="ville" value="<?= $contact["ville"]; ?>">
									</td>
									<td>
										<button class="btn btn-warning btn-link" type="submit" name="modify" style="margin-bottom: 10px;">
											<i class="fa fa-edit"></i> Modifier
										</button>
										<button class="btn btn-danger btn-link" type="submit" name="delete"">
											<i class="fa fa-trash"></i> Supprimer
										</button>
									</td>
								</tr>
							</form>
						<?php endforeach;?>
					</tbody>
				</table>
				<table class="table table-hover table-bordered">
					<div class="card-header">
						<h5 class="card-title">Ajouter nouveau :</h5>
					</div>
					<thead class="thead-dark">
						<tr>
							<th>Id</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Portable</th>
							<th>Fix</th>
							<th>Ville</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<form method="POST">
							<tr>
								<th scope="row">
									<input class="form-control" type="text" name="id" value="">
								</th>
								<th scope="row">
									<input class="form-control" type="text" name="nom" value="">
								</th>
								<th scope="row">
									<input class="form-control" type="text" name="prenom" value="">
								</th>
								<td>
									<input class="form-control" type="text" name="portable" value="">
								</td>
								<td>
									<input class="form-control" type="text" name="fix" value="">
								</td>
								<td>
									<input class="form-control" type="text" name="ville" value="">
								</td>
								<td>
									<button class="btn btn-success btn-link" type="submit" name="create">
										<i class="fa fa-plus"></i> Ajouter
									</button>
								</td>
							</tr>
						</form>
					</tbody>
				</table>
			</div>
		</div>
		</script>
		<script type="text/javascript" src="assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/popper.min.js"></script>
		<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	</body>
</html>