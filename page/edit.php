<?php
//edit.php
require("../functions.php");
require("../class/Book.class.php");
$Book = new Book($mysqli);

echo "<body style='background-color:#FFDFDF'>";

if(isset($_POST["update"])){
    $Book->update($Helper->cleanInput($_POST["id"]),$Helper->cleanInput($_POST["book_author"]),
        $Helper->cleanInput($_POST["book_name"]),$Helper->cleanInput($_POST["book_genre"]));
    header("Location: edit.php?id=".$_POST["id"]."&success=true");
    exit();
}

if(!isset($_GET["id"])) {
    header("Location: books.php");
    exit();
}

$c = $Book->getSingle($_GET["id"]);
if(isset($_GET["success"])){
    echo "Success!";
}

if(isset($_GET["delete"])){
    $Book->delete($_GET["id"]);
    header("Location: books.php");
    exit();
}
?>

<?php require("../header.php"); ?>
<div class="container">

    <div class="row">

        <div class="col-sm-3 col-sm-offset-4">
            <br>
            <a href="books.php" class="btn btn-default btn-md"> Go back </a>

            <h2>Change information</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
                <input type="hidden" name="id" value="<?=$_GET["id"];?>" >
                <label for="book_author" >Book author:</label><br>
                <input id="book_author" name="book_author" type="text" class="form-control" value="<?php echo $c->book_author;?>" ><br>
                <label for="book_name" >Book name:</label><br>
                <input id="book_name" name="book_name" type="text" class="form-control" value="<?php echo $c->book_name;?>" ><br>
                <label for="book_genre" >Book genre</label><br>
                <select name="book_genre" button class="btn btn-default btn-md dropdown-toggle">
                    <option value="Crime" <?php echo $result['genre'] == 'Crime' ? 'selected' : ''?>>Crime</option>
                    <option value="Adventure" <?php echo $result['genre'] == 'Adventure' ? 'selected' : ''?> >Adventure</option>
                    <option value="Sci-Fi" <?php echo $result['genre'] == 'Sci-Fi' ? 'selected' : ''?>>Sci-Fi</option>
                    <option value="Romance" <?php echo $result['genre'] == 'Romance' ? 'selected' : ''?>>Romance</option>
                    <option value="Horror" <?php echo $result['genre'] == 'Horror' ? 'selected' : ''?> >Horror</option>
                    <option value="Fantasy" <?php echo $result['genre'] == 'Fantasy' ? 'selected' : ''?>>Fantasy</option>
                </select><br><br><br>

                <input class="btn btn-default btn-lg" type="submit" name="update" value="Submit">
            </form>
            <br>
            <a href="?id=<?=$_GET["id"];?>&delete=true" class="btn btn-default btn-lg">Delete</a>

         </div>

    </div>

</div>
<?php require("../footer.php"); ?>