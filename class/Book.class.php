<?php
class Book{

    private $connection;
    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function delete($id){
        $stmt = $this->connection->prepare("UPDATE user_books SET deleted=NOW() WHERE id=? AND deleted IS NULL");
        $stmt->bind_param("i", $id);

        // kas õnnestus salvestada
        if ($stmt->execute()) {
            // õnnestus
            echo "Deleted";
        }
         $stmt->close();
    }

    function get($q, $sort, $direction){

        //mis sort ja järjekord
        $allowedSortOptions = ["id", "username", "book_author", "book_name", "book_genre"];
        //kas sort on lubatud valikute sees
        if (!in_array($sort, $allowedSortOptions)){
            $sort = "id";
        }
        //echo "Sorting: " .$sort. " ";
        
        //luban ainult 2 valikut
        $orderBy = "ASC";
        if($direction == "descending"){
            $orderBy = "DESC";
        }
        //echo "Order: " .$orderBy." ";

        if ($q == ""){
            //echo "Not searching";
            
            $stmt = $this->connection->prepare("
            SELECT id, username, book_author, book_name, book_genre
            FROM user_books
            WHERE deleted IS NULL 
            ORDER BY $sort $orderBy");
        
        } else {
            //echo "Search: " .$q;

            //teen otsisõna
            // lisan mõlemale poole %
            $searchword = "%".$q."%";

            $stmt = $this->connection->prepare("
                SELECT id, username, book_author, book_name, book_genre
                FROM user_books
                WHERE deleted IS NULL AND
                (id LIKE ? OR username LIKE ? OR book_author LIKE ? OR book_name LIKE ? OR book_genre LIKE ?)
                ORDER BY $sort $orderBy");
            $stmt->bind_param("sssss", $searchword, $searchword, $searchword, $searchword, $searchword);
        }

        $stmt->bind_result($id, $userName, $bookAuthor, $bookName, $bookGenre);
        $stmt->execute();

        //tekitan massiivi
        $result = array();

        // tee seda seni, kuni on rida andmeid
        // mis vastab select lausele
        while ($stmt->fetch()) {

            //tekitan objekti
            $book = new StdClass();

            $book->id = $id;
            $book->username = $userName;
            $book->bookAuthor = $bookAuthor;
            $book->bookName = $bookName;
            $book->bookGenre = $bookGenre;

            array_push($result, $book);
        }

        $stmt->close();
        return $result;
    }

    function getSingle($edit_id){
        
        $stmt = $this->connection->prepare("SELECT book_author, book_name, book_genre FROM user_books WHERE id=? 
            AND deleted IS NULL");

        echo $this->connection->error;
        $stmt->bind_param("i", $edit_id);
        $stmt->bind_result($bookAuthor, $bookName, $bookGenre);
        $stmt->execute();

        //tekitan objekti
        $book = new Stdclass();

        if ($stmt->fetch()) {
            $book->book_author = $bookAuthor;
            $book->book_name = $bookName;
            $book->book_genre = $bookGenre;
        } else {
            header("Location: books.php");
            exit();
        }
        $stmt->close();
        return $book;
    }

    function save($userName, $bookAuthor, $bookName, $bookGenre){

        $stmt = $this->connection->prepare("INSERT INTO user_books (username, book_author, book_name, book_genre) VALUES (?, ?, ?, ?)");
        
        $stmt->bind_param("ssss", $userName, $bookAuthor, $bookName, $bookGenre);

        echo $stmt->error;

        if ($stmt->execute()) {
            //echo "Saved!";
        } else {
            echo "ERROR " . $stmt->error;
        }
        $stmt->close();
    }


    function cleanInput($input){

        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    function update($id, $bookAuthor, $bookName, $bookGenre){
        
        $stmt = $this->connection->prepare("UPDATE user_books SET book_author=?, book_name=?, book_genre=? WHERE id=? 
            AND deleted IS NULL");

        $stmt->bind_param("sssi", $bookAuthor, $bookName, $bookGenre, $id);
        
        if ($stmt->execute()) {
            echo "Success!";
        }
        $stmt->close();

    }
}
?>
