<?php
// Include the database connection class
require_once "database.php";

// Initialize database connection
$db = new Database();
$conn = $db->getConnect();

// Initialize session
session_start();

// Reserve function
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookId = intval($_POST['book_id']);
    if (isset($_SESSION['user_id'])) {
        // Perform reservation
        $userId = $_SESSION['user_id'];
        $reserveQuery = "INSERT INTO reservations (user_id, book_id, status, reserved_at) 
                         VALUES (?, ?, 'Reserved', NOW())";
        $stmt = $conn->prepare($reserveQuery);
        $stmt->execute([$userId, $bookId]);
        
        echo "<script>
                alert('Book reserved successfully!');
              </script>";
    } else {
        echo "<script>
                alert('Please log in to make a reservation.');
              </script>";
    }
}

// Search filter and display books
$sql = "SELECT * FROM books";
$stmt = $conn->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Book Catalog</title>
</head>
<body>
    <h1>Library Catalog</h1>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo htmlspecialchars($book['Book_Title']); ?></td>
                <td><?php echo htmlspecialchars($book['Book_Author']); ?></td>
                <td><?php echo htmlspecialchars($book['Book_ISBN']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="book_id" value="<?php echo $book['Book_ID']; ?>" />
                        <button type="submit">Reserve</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
