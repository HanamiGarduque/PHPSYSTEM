<?php
require_once '../../Database/database.php';
require_once '../../Database/crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $book = new Books($db);
    $book->Book_ID = htmlspecialchars(trim($_POST['bookId']));
    $book->Book_Title = htmlspecialchars(trim($_POST['bookTitle']));
    $book->Book_Author = htmlspecialchars(trim($_POST['bookAuthor']));
    $book->Book_ISBN = htmlspecialchars(trim($_POST['bookIsbn']));
    $book->Published_Year = htmlspecialchars(trim($_POST['publishedYear']));
    $book->Book_Genre = htmlspecialchars(trim($_POST['bookGenre']));
    $book->Book_Publisher = htmlspecialchars(trim($_POST['bookPublisher']));
    $book->Available_Copies = htmlspecialchars(trim($_POST['availableCopies']));

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
                window.location.href = '../../Admin/Book Management/bookManagement.php';
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
                window.location.href = '../../Admin/Book Management/bookManagement.php';
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
        $book->Book_ID = htmlspecialchars(trim($_GET['id']));

        $stmt = $db->prepare("SELECT * FROM books WHERE Book_ID = :bookId");
        $stmt->bindParam(':bookId', $book->Book_ID);
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
            window.location.href = 'bookManagement.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
        alert('Invalid request!');
        window.location.href = 'bookManagement.php';
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
        <input type="hidden" name="bookId" value="<?php echo $book->Book_ID; ?>">
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
