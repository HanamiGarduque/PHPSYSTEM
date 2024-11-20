<?php
require_once 'dbConnection.php';
require_once 'crudOperation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $book = new Book($db);
    $book->Book_Title = htmlspecialchars(trim($_POST['title']));
    $book->Book_Author = htmlspecialchars(trim($_POST['author']));
    $book->Book_ISBN = htmlspecialchars(trim($_POST['isbn']));
    $book->Published_Year = htmlspecialchars(trim($_POST['published_year']));
    $book->Book_Genre = htmlspecialchars(trim($_POST['genre']));
    $book->Book_Publisher = htmlspecialchars(trim($_POST['publisher']));
    $book->Available_Copies = htmlspecialchars(trim($_POST['available_copies']));

    if ($book->create()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Book Added!',
            text: 'The book has been successfully added to the collection.',
            icon: 'success'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });
        </script>
        </body>
        </html>";
        exit;
    } else {
        echo "An error was encountered while adding the book.";
    }
}
?>

<h2>Add Book</h2>
<form method="POST" action="add_book.php">
    Title: <input type="text" name="title" required>
    <br><br>
    Author: <input type="text" name="author" required>
    <br><br>
    ISBN: <input type="text" name="isbn" required>
    <br><br>
    Published Year: <input type="number" name="published_year" required>
    <br><br>
    Genre: <input type="text" name="genre" required>
    <br><br>
    Publisher: <input type="text" name="publisher" required>
    <br><br>
    Available Copies: <input type="number" name="available_copies" required>
    <br><br>
    <input type="submit" value="Add Book">
</form>

<br>
<a href="index.php">
    <button type="button">Home</button>
</a>