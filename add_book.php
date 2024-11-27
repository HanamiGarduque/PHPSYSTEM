<?php
require_once '../../Database/database.php';
require_once '../../Admin/Book Management/crudOperation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $book = new Books($db);
    $book->book_title = htmlspecialchars(trim($_POST['bookTitle']));
    $book->book_author = htmlspecialchars(trim($_POST['bookAuthor']));
    $book->book_isbn = htmlspecialchars(trim($_POST['bookIsbn']));
    $book->published_year = htmlspecialchars(trim($_POST['publishedYear']));
    $book->book_genre = htmlspecialchars(trim($_POST['bookGenre']));
    $book->book_publisher = htmlspecialchars(trim($_POST['bookPublisher']));
    $book->available_copies = htmlspecialchars(trim($_POST['availableCopies']));

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
    Title: <input type="text" name="bookTitle" required>
    <br><br>
    Author: <input type="text" name="bookAuthor" required>
    <br><br>
    ISBN: <input type="text" name="bookIsbn" required>
    <br><br>
    Published Year: <input type="number" name="publishedYear" required>
    <br><br>
    Genre: <input type="text" name="bookGenre" required>
    <br><br>
    Publisher: <input type="text" name="bookPublisher" required>
    <br><br>
    Available Copies: <input type="number" name="availableCopies" required>
    <br><br>
    <input type="submit" value="Add Book">
</form>

<br>
<a href="../../Admin/Book Management/index.php">
    <button type="button">Home</button>
</a>