<?php
// Include the database connection class
require_once "sc_db.php";

// Initialize database connection
$db = new Database();
$conn = $db->getConnect();

// Reserve function
function reserveBook($bookId, $conn) {
    // Check if the book is already reserved
    $checkQuery = "SELECT * FROM reservations WHERE book_id = ? AND status = 'Reserved'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$bookId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return "This book is already reserved.";
    }

    // Insert reservation record
    $insertQuery = "INSERT INTO reservations (book_id, status, reserved_at) VALUES (?, 'Reserved', NOW())";
    $stmt = $conn->prepare($insertQuery);

    if ($stmt->execute([$bookId])) {
        return "Book reserved successfully!";
    } else {
        return "Failed to reserve the book. Please try again.";
    }
}

// Check for reserve action
$reservationMessage = "";
if (isset($_POST['reserve'])) {
    $bookId = intval($_POST['book_id']);
    $reservationMessage = reserveBook($bookId, $conn);
}

// Initialize variables
$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";
$searchTerm = isset($_GET['query']) ? $_GET['query'] : "";
$results = [];

// Build SQL query with dynamic filters and LEFT JOIN
$sql = "SELECT books.* , description.Description 
        FROM books 
        LEFT JOIN description ON books.Book_ID = description.Book_ID 
        WHERE 1";

$params = [];

// Add search filter
if (!empty($searchTerm)) {
    $sql .= " AND (Book_Title LIKE ? OR Book_Author LIKE ? OR Book_ISBN LIKE ? OR Book_Genre LIKE ?)";
    $likeSearchTerm = '%' . $searchTerm . '%';
    $params = array_fill(0, 4, $likeSearchTerm);
}

// Add genre filter
if ($filter !== "all") {
    $sql .= " AND Book_Genre = ?";
    $params[] = $filter;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Catalog</title>
    <link rel="stylesheet" href="search_catalog.css"> <!-- Link to external CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <header>
        <h1>Blib</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Search</a></li>
                <li><a href="#">Borrow History</a></li>
                <li><a href="#">My Account</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <div class="search-container">
        <h1>Book Catalog</h1>
        <form method="GET">
            <input 
                type="text" 
                name="query" 
                placeholder="Search a book / Author / ISBN / Genre..." 
                value="<?php echo htmlspecialchars($searchTerm); ?>" 
            />
            <select name="filter">
                <option value="all" <?php echo $filter === "all" ? "selected" : ""; ?>>All Genres</option>
                <option value="Fiction" <?php echo $filter === "Fiction" ? "selected" : ""; ?>>Fiction</option>
                <option value="Dystopian Fiction" <?php echo $filter === "Dystopian Fiction" ? "selected" : ""; ?>>Dystopian Fiction</option>
                <option value="Romance" <?php echo $filter === "Romance" ? "selected" : ""; ?>>Romance</option>
                <option value="Adventure" <?php echo $filter === "Adventure" ? "selected" : ""; ?>>Adventure</option>
                <option value="Historical Fiction" <?php echo $filter === "Historical Fiction" ? "selected" : ""; ?>>Historical Fiction</option>
                <option value="Gothic Fiction" <?php echo $filter === "Gothic Fiction" ? "selected" : ""; ?>>Gothic Fiction</option>
                <option value="Fantasy" <?php echo $filter === "Fantasy" ? "selected" : ""; ?>>Fantasy</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if ($reservationMessage): ?>
        <p style="text-align: center; color: green;"><?php echo $reservationMessage; ?></p>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <table id="booksTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Publisher</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['Book_Title']); ?></td>
                        <td><?php echo htmlspecialchars($book['Book_Author']); ?></td>
                        <td><?php echo htmlspecialchars($book['Book_ISBN']); ?></td>
                        <td><?php echo htmlspecialchars($book['Book_Genre']); ?></td>
                        <td><?php echo htmlspecialchars($book['Published_Year']); ?></td>
                        <td><?php echo htmlspecialchars($book['Book_Publisher']); ?></td>
                        <td><?php echo htmlspecialchars($book['Description'] ?? 'No description available'); ?></td>
                        <td>
                        <a href="reservationForm.php?Book_IDs=<?php echo $book['Book_ID']; ?>" class="button">
                            <button type="button">Borrow</button>
                        </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No results found.</p>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            $('#booksTable').DataTable({
                paging: false, 
                searching: false 
            });
        });
    </script>
</body>
</html>
