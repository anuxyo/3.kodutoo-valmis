<?php

	require("../functions.php");
	require("../class/Book.class.php");
	$Book = new Book($mysqli);

	echo "<body style='background-color:#FFDFDF'>";

	//MUUTUJAD
	$Username = "";
	$bookAuthor = "";
	$bookName = "";
	$bookGenre = "";

	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){

		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}


	//kui on ?logout aadressireal siis log out
	if (isset($_GET["logout"])) {

		session_destroy();
		header("Location: login.php");
		exit();
	}

	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		unset($_SESSION["message"]);
	}

	if (isset($_POST["bookAuthor"]) &&
		!empty ($_POST["bookAuthor"])) {
			$bookAuthor = $Helper->cleanInput($_POST["bookAuthor"]);
		}

	if (isset($_POST["bookName"]) &&
		!empty ($_POST["bookName"])) {
			$bookName = $Helper->cleanInput($_POST["bookName"]);
			}

	if (isset($_POST["bookGenre"]) &&
		!empty ($_POST["bookGenre"])) {
			$bookGenre = $Helper->cleanInput($_POST["bookGenre"]);
		}

$error= "";

	if(isset($_POST["bookAuthor"]) &&
		isset($_POST["bookName"]) &&
		isset($_POST["bookGenre"]) &&
		!empty($_POST["bookAuthor"]) &&
		!empty($_POST["bookName"]) &&
		!empty($_POST["bookGenre"])) {

		$Book->save($Helper->cleanInput($_SESSION["userName"]), $Helper->cleanInput($_POST["bookAuthor"]),
		$Helper->cleanInput($_POST["bookName"]), $Helper->cleanInput($_POST["bookGenre"]));

	}
	elseif(isset($_POST["bookAuthor"]) &&
			isset($_POST["bookName"]) &&
			isset($_POST["bookGenre"]) &&
			empty($_POST["bookAuthor"]) &&
			empty($_POST["bookName"]) &&
			empty($_POST["bookGenre"])) {

			$error = "Fill all fields!";
	}

	echo $error;
	
		//sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	} else {
		//kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
		
	}
	
	//kas otsib
	if(isset($_GET["q"])){
		
		$q = $Helper->cleanInput($_GET["q"]);
		
		$bookData = $Book->get($q, $sort, $direction);
	
	} else {
		$q = "";
		$bookData = $Book->get($q, $sort, $direction);
	
	}
	

?>


<?php require("../header.php"); ?>

<div class="container">

	<div class="row">

		<div class="col-sm-3">

			<h2><p>
				Welcome <?=$_SESSION["userName"];?>!
			</p></h2>
			<p><a href="?logout=1" class="btn btn-default btn-md">Log out</a></p>
			<br><br>

			<h2> Add books</h2>
			<form method="POST">

				<label>Book author:</label><br>
				<input name="bookAuthor" type="text" class="form-control" value="<?=$bookAuthor;?>">

				<br>
				<label>Book name:</label><br>
				<input name="bookName" type="text" class="form-control" value="<?=$bookName;?>">

				<br>

				<label>Book genre:</label><br>
				<select name="bookGenre" button class="btn btn-default btn-md dropdown-toggle" type="button">
					<option value="" <?php echo $result['genre'] == '' ? 'selected' : ''?> >Genre</option>
					<option value="Crime" <?php echo $result['genre'] == 'Crime' ? 'selected' : ''?>>Crime</option>
					<option value="Adventure" <?php echo $result['genre'] == 'Adventure' ? 'selected' : ''?> >Adventure</option>
					<option value="Sci-Fi" <?php echo $result['genre'] == 'Sci-Fi' ? 'selected' : ''?>>Sci-Fi</option>
					<option value="Romance" <?php echo $result['genre'] == 'Romance' ? 'selected' : ''?>>Romance</option>
					<option value="Horror" <?php echo $result['genre'] == 'Horror' ? 'selected' : ''?> >Horror</option>
					<option value="Fantasy" <?php echo $result['genre'] == 'Fantasy' ? 'selected' : ''?>>Fantasy</option>
				</select>
				<br><br><br>
				<input class="btn btn-default btn-lg" type="submit" value="Submit">

			</form>

		</div>

	</div>



	<div class="row">

			<div class="col-sm-3">
			<br><br>
				<h2>Search for books</h2>
				<form>
					<input type="search" class="form-control" name="q">
					<input class="btn btn-default btn-md" type="submit" value="Search">
				</form>
			</div>
	</div>

<br>

	<?php

		$direction = "ascending";
		if(isset($_GET["direction"])){
			if ($_GET["direction"] == "ascending"){
				$direction = "descending";
			}

		}

		$html = "<table class='table table-hover table-bordered'>";

		$html .= "<tr>";
			$html .= "<th><a href=?q=".$q."&sort=id&direction=".$direction."'>id</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=username&direction=".$direction."'>username</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=author&direction=".$direction."'>author</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=book&direction=".$direction."'>book</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=genre&direction=".$direction."'>genre</a></th>";
		$html .= "</tr>";

		foreach($bookData as $i){
			$html .= "<tr>";
				$html .= "<td>".$i->id."</td>";
				$html .= "<td>".$i->username."</td>";
				$html .= "<td>".$i->bookAuthor."</td>";
				$html .= "<td>".$i->bookName."</td>";
				$html .= "<td>".$i->bookGenre."</td>";
				$html .= "<td>
							<a href='edit.php?id=".$i->id."'<span class='glyphicon glyphicon-pencil'> </a></td>";

			$html .= "</tr>";
		}

		$html .= "</table>";

		echo $html;
	?>
</div>


<?php require("../footer.php"); ?>