<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$db = new Database();
$conn = $db->getConnect();

// Initialize variables
$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";
$searchTerm = isset($_GET['query']) ? $_GET['query'] : "";
$results = [];

// Build SQL query with dynamic filters and LEFT JOIN
$sql = "SELECT books.*, description.Description 
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
<?php
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$db = new Database();
$conn = $db->getConnect();

// Initialize variables
$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";
$searchTerm = isset($_GET['query']) ? $_GET['query'] : "";
$results = [];

// Build SQL query with dynamic filters and LEFT JOIN
$sql = "SELECT books.*, description.Description 
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
    <link rel="stylesheet" href="./css/search_catalog.css"> <!-- Link to external CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo"></div>
        <nav class="nav">
            <a href="homepage.php">Home</a>
            <a href="search_catalog.php" style="color: #F7E135;">Search a Book</a>
            <a href="notifications.php">Notifications</a>
            <a href="myacc.php">My Account</a>
        </nav>
    </header>

    <div class="search-container">
        <h1>Book Catalog</h1>
        <form method="GET">
            <input type="text" name="query" placeholder="Search a book / Author / ISBN / Genre..." value="<?php echo htmlspecialchars($searchTerm); ?>" />
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
                            <a href="reservationForm.php?Book_ID=<?php echo $book['Book_ID']; ?>" class="button">Borrow</a>
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
