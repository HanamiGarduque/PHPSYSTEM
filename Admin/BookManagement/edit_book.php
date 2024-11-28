<?php
require_once '../../Database/database.php';
require_once '../../Admin/Book Management/crudOperation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $book = new Books($db);
    $book->book_id = htmlspecialchars(trim($_POST['bookId']));
    $book->book_title = htmlspecialchars(trim($_POST['bookTitle']));
    $book->book_author = htmlspecialchars(trim($_POST['bookAuthor']));
    $book->book_isbn = htmlspecialchars(trim($_POST['bookIsbn']));
    $book->published_year = htmlspecialchars(trim($_POST['publishedYear']));
    $book->book_genre = htmlspecialchars(trim($_POST['bookGenre']));
    $book->book_publisher = htmlspecialchars(trim($_POST['bookPublisher']));
    $book->available_copies = htmlspecialchars(trim($_POST['availableCopies']));

    if ($book->update()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Edit Confirmation</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Book Updated!',
            text: 'The book details have been successfully updated.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../Admin/Book Management/index.php';
            }
        });
        </script>
        </body>
        </html>";
        exit;
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the book details. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../Admin/Book Management/index.php';
            }
        });
        </script>
        </body>
        </html>";
        exit;
    }
} else {
    if (isset($_GET['id'])) {
        $database = new Database();
        $db = $database->getConnect();

        $book = new Books($db);
        $book->book_id = htmlspecialchars(trim($_GET['id']));

        $stmt = $db->prepare("SELECT * FROM books WHERE book_id = :bookId");
        $stmt->bindParam(':bookId', $book->book_id);
        $stmt->execute();
        $bookDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookDetails) {
            $bookTitle = htmlspecialchars($bookDetails['Book_Title']);
            $bookAuthor = htmlspecialchars($bookDetails['Book_Author']);
            $bookIsbn = htmlspecialchars($bookDetails['Book_ISBN']);
            $publishedYear = htmlspecialchars($bookDetails['Published_Year']);
            $bookGenre = htmlspecialchars($bookDetails['Book_Genre']);
            $bookPublisher = htmlspecialchars($bookDetails['Book_Publisher']);
            $availableCopies = htmlspecialchars($bookDetails['Available_Copies']);
        } else {
            echo "<script>
            alert('Book not found!');
            window.location.href = 'index.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
        alert('Invalid request!');
        window.location.href = 'index.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h2>Edit Book</h2>
    <form action="edit_book.php" method="POST">
        <input type="hidden" name="bookId" value="<?php echo $book->book_id; ?>">
        <label for="title">Title:</label>
        <input type="text" name="bookTitle" id="title" value="<?php echo $bookTitle; ?>" required><br>
        <br>
        <label for="author">Author:</label>
        <input type="text" name="bookAuthor" id="author" value="<?php echo $bookAuthor; ?>" required><br>
        <br>
        <label for="isbn">ISBN:</label>
        <input type="text" name="bookIsbn" id="isbn" value="<?php echo $bookIsbn; ?>" required><br>
        <br>
        <label for="published_year">Published Year:</label>
        <input type="number" name="publishedYear" id="published_year" value="<?php echo $publishedYear; ?>" required><br>
        <br>
        <label for="genre">Genre:</label>
        <input type="text" name="bookGenre" id="genre" value="<?php echo $bookGenre; ?>" required><br>
        <br>
        <label for="publisher">Publisher:</label>
        <input type="text" name="bookPublisher" id="publisher" value="<?php echo $bookPublisher; ?>" required><br>
        <br>
        <label for="available_copies">Available Copies:</label>
        <input type="number" name="availableCopies" id="available_copies" value="<?php echo $availableCopies; ?>" required><br>
        <br>
        <button type="submit">Update Book</button>
    </form>
</body>
</html>
