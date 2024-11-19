<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_catalog";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";
$searchTerm = isset($_GET['query']) ? $_GET['query'] : "";
$results = [];

// Build SQL query with dynamic filters
$sql = "SELECT * FROM books WHERE 1";

$params = [];
$types = "";

// Search query filter
if (!empty($searchTerm)) {
    $sql .= " AND (Book_Title LIKE ? OR Book_Author LIKE ? OR Book_ISBN LIKE ? OR Book_Genre LIKE ?)";
    $likeSearchTerm = '%' . $searchTerm . '%';
    $params = array_fill(0, 4, $likeSearchTerm);
    $types .= str_repeat("s", 4);
}

// Genre filter
if ($filter !== "all") {
    $sql .= " AND Book_Genre = ?";
    $params[] = $filter;
    $types .= "s";
}

$stmt = $conn->prepare($sql);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch results into an array
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Book Catalog</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 15px;
        }

        nav ul li a {
            text-decoration: none;
        }

        .search-container {
            margin: 50px auto;
            text-align: center;
        }

        .search-container input[type="text"], 
        .search-container select, 
        .search-container button {
            padding: 10px;
            border-radius: 5px;
        }

        table {
            margin: 20px auto;
            width: 80%;
            border-collapse: collapse;
        }

        table thead th, table tbody td {
            padding: 10px;
            border: 1px solid #dddddd;
            text-align: left;
        }
    </style>
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
                    <th>Available Copies</th>
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
                        <td><?php echo htmlspecialchars($book['Available_Copies']); ?></td>
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
                paging: false, // Remove pagination
                searching: false // Disable the search bar on the right
            });
        });
    </script>
</body>
</html>
