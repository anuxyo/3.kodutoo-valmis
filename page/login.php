<?php

require("/home/anusada/config.php");
require("../functions.php");
	
require("../class/User.class.php");
	$User = new User($mysqli);

if (isset($_SESSION["userId"])) {

	header("Location: books.php");
		exit();
}

echo "<body style='background-color:#FFDFDF'>";

//MUUTUJAD
$loginUsername = "";
$loginUsernameError = "";
$loginPassword = "";
$loginPasswordError = "";	
$signupUsernameError = "";
$signupEmailError = "";
$signupPasswordError = "";
$signupPassword = "";
$signupUsername = "";
$signupUsernameError = "";
$signupEmail = "";
$signupEmailError = "";
$signupGender = "";
$signupGenderError = "";
$signupAge = "";
$signupAgeError = "";

if(isset($_POST["loginUsername"])){
    if(empty($_POST["loginUsername"])){
	    $loginUsernameError = "Insert username!";
	} 
}

if(isset($_POST["loginPassword"])){
	if(empty($_POST["loginPassword"])){
	    $loginPasswordError = "Insert password!";
	} 
}

if(isset($_POST["signupUsername"])){
	if(empty($_POST["signupUsername"])){
		$signupUsernameError = "This field is required!";
	} else {
		$signupUsername = $_POST["signupUsername"];
	}
}

if ( isset ( $_POST["signupEmail"] ) ) {
	if ( empty ( $_POST["signupEmail"] ) ) {
		$signupEmailError = "This field is required!";
	} else {
		$signupEmail = $_POST["signupEmail"];
	}
}


if ( isset ( $_POST["signupPassword"] ) ) {
	if ( empty ( $_POST["signupPassword"] ) ) {
		$signupPasswordError = "This field is required!";
	} else {

	if ( strlen($_POST["signupPassword"]) < 8 ) {
		$signupPasswordError = "Password must be at leat 8 characters long!";
	}
	}
}

if ( isset ( $_POST["signupAge"] )) {
	if (empty ( $_POST["signupAge"] )) {
		$signupAgeError = "This field is required!";
    } else {
    	$signupAge = $_POST["signupAge"];
    }
}

if ( isset ( $_POST["signupGender"] ) ) {
	if (!empty ( $_POST["signupGender"] ) ) {
		$signupGender = $_POST["signupGender"];
	}
}

if ( isset($_POST["signupUsername"]) &&
    isset($_POST["signupEmail"]) &&
	isset($_POST["signupPassword"]) &&
	isset($_POST["signupAge"]) &&
	isset($_POST["signupGender"]) &&
	$signupEmailError == "" &&
	empty ($signupPasswordError)) {

	$password = hash("sha512", $_POST["signupPassword"]);

	$User->signUp($Helper->cleanInput($_POST['signupUsername']), $Helper->cleanInput($_POST['signupEmail']),
	$Helper->cleanInput ($password),$Helper->cleanInput($_POST['signupAge']), $Helper->cleanInput($_POST['signupGender']));
}

$error ="";
if (isset($_POST["loginUsername"]) &&
	isset($_POST["loginPassword"]) &&
	!empty($_POST["loginUsername"]) &&
	!empty($_POST["loginPassword"])) {

$error = $User->login($Helper->cleanInput($_POST["loginUsername"]), 
$Helper->cleanInput($_POST["loginPassword"]));
}
?>

<?php require("../header.php"); ?>

<div class="container">

    <div class="row">

        <div class="col-sm-3 col-sm-offset-2">

                <h1>Log in</h1>

                <form method="POST">

                    <p style="color:red;"><?=$error;?></p>
                    <input name="loginUsername" type="text" class="form-control" placeholder="Username" value="<?=$loginUsername;?>" >
                    <?php echo $loginUsernameError; ?>
                    <br><br>
                    <input name="loginPassword" type="password" class="form-control" placeholder="Password" value="<?=$loginPassword;?>">
                    <?php echo $loginPasswordError; ?>
                    <br><br>
                    <input class="btn btn-default btn-lg" type="submit" value="Log in">

                </form>
        </div>

        <div class="col-sm-3 col-sm-offset-2">

            <h1>Sign up</h1>
                <form method="POST">

                    <label>Username:</label><br>
                    <input name="signupUsername" type="text" class="form-control" value="<?=$signupUsername;?>">
                    <?php echo $signupUsernameError; ?>

                    <br><br>

                    <label>Email:</label><br>
                    <input name="signupEmail" type="text" class="form-control" value="<?=$signupEmail;?>">
                    <?php echo $signupEmailError; ?>

                    <br><br>

                    <label>Password:</label><br>
                    <input name="signupPassword" type="password" class="form-control" value="<?=$signupPassword;?>">
                    <?php echo $signupPasswordError; ?>

                    <br><br>

                    <label>Age:</label><br>
                    <input name="signupAge" type="number" class="form-control" value="<?=$signupAge;?>">
                    <?php echo $signupAgeError; ?>

                    <br><br>
                    <label>Gender:</label><br>

                    <?php if($signupGender == "male") { ?>
                        <input type="radio" name="signupGender" value="male" checked> Male<br>
                    <?php }else { ?>

                        <input type="radio" name="signupGender" value="male"> Male<br>
                    <?php } ?>

                    <?php if($signupGender == "female") { ?>
                        <input type="radio" name="signupGender" value="female" checked> Female<br>
                    <?php }else { ?>
                        <input type="radio" name="signupGender" value="female"> Female<br>
                    <?php } ?>

                    <?php if($signupGender == "other") { ?>
                        <input type="radio" name="signupGender" value="other" checked> Other<br>
                    <?php }else { ?>
                        <input type="radio" name="signupGender" value="other"> Other<br>
                    <?php } ?>

                    <br><br>

                    <input class="btn btn-default btn-lg" type="submit" value="Create account">
                </form>
        </div>
    </div>

</div>

<?php require("../footer.php"); ?>