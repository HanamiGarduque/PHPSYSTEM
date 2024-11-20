<?php
require_once 'dbConnection.php';
require_once 'crudOperation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $book = new Book($db);
    $book->Book_ID = htmlspecialchars(trim($_POST['id']));
    $book->Book_Title = htmlspecialchars(trim($_POST['title']));
    $book->Book_Author = htmlspecialchars(trim($_POST['author']));
    $book->Book_ISBN = htmlspecialchars(trim($_POST['isbn']));
    $book->Published_Year = htmlspecialchars(trim($_POST['published_year']));
    $book->Book_Genre = htmlspecialchars(trim($_POST['genre']));
    $book->Book_Publisher = htmlspecialchars(trim($_POST['publisher']));
    $book->Available_Copies = htmlspecialchars(trim($_POST['available_copies']));

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
                window.location.href = 'index.php';
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
                window.location.href = 'index.php';
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

        $book = new Book($db);
        $book->Book_ID = htmlspecialchars(trim($_GET['id']));

        $stmt = $db->prepare("SELECT * FROM books WHERE Book_ID = :id");
        $stmt->bindParam(':id', $book->Book_ID);
        $stmt->execute();
        $bookDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookDetails) {
            $title = htmlspecialchars($bookDetails['Book_Title']);
            $author = htmlspecialchars($bookDetails['Book_Author']);
            $isbn = htmlspecialchars($bookDetails['Book_ISBN']);
            $published_year = htmlspecialchars($bookDetails['Published_Year']);
            $genre = htmlspecialchars($bookDetails['Book_Genre']);
            $publisher = htmlspecialchars($bookDetails['Book_Publisher']);
            $available_copies = htmlspecialchars($bookDetails['Available_Copies']);
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
        <input type="hidden" name="id" value="<?php echo $book->Book_ID; ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $title; ?>" required><br>
        <br>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" value="<?php echo $author; ?>" required><br>
        <br>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" value="<?php echo $isbn; ?>" required><br>
        <br>
        <label for="published_year">Published Year:</label>
        <input type="number" name="published_year" id="published_year" value="<?php echo $published_year; ?>" required><br>
        <br>
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" value="<?php echo $genre; ?>" required><br>
        <br>
        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher" id="publisher" value="<?php echo $publisher; ?>" required><br>
        <br>
        <label for="available_copies">Available Copies:</label>
        <input type="number" name="available_copies" id="available_copies" value="<?php echo $available_copies; ?>" required><br>
        <br>
        <button type="submit">Update Book</button>
    </form>
</body>
</html>
