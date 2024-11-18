<?php
require_once 'database.php'; // Include database connection
require_once 'crud.php'; // Include CRUD operations

// Initialize database connection
$database = new Database();
$db = $database->getConnect();

// Handle search request
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = htmlspecialchars(trim($_POST['search_term']));
    $category = $_POST['category'] ?? '';
    $author = $_POST['author'] ?? '';
    $publicationYear = $_POST['publication_year'] ?? '';
    $availability = $_POST['availability'] ?? '';
    $language = $_POST['language'] ?? '';

    // Build the query with filters
    $query = "SELECT * FROM books WHERE title LIKE :searchTerm OR author LIKE :searchTerm";
    $params = [':searchTerm' => "%$searchTerm%"];

    if ($category) {
        $query .= " AND category = :category";
        $params[':category'] = $category;
    }
    if ($author) {
        $query .= " AND author = :author";
        $params[':author'] = $author;
    }
    if ($publicationYear) {
        $query .= " AND publication_year = :publicationYear";
        $params[':publicationYear'] = $publicationYear;
    }
    if ($availability) {
        $query .= " AND availability = :availability";
        $params[':availability'] = $availability;
    }
    if ($language) {
        $query .= " AND language = :language";
        $params[':language'] = $language;
    }

    // Prepare and execute the query
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Catalog</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
<h2>Search Catalog</h2>
<form method="POST" action="">
    <input type="text" name="search_term" placeholder="Search by title, author, or ISBN" required>
    <select name="category">
        <option value="">Select Category</option>
        <option value="Fiction">Fiction</option>
        <option value="Non-Fiction">Non-Fiction</option>
        <!-- Add more categories as needed -->
    </select>
    <input type="text" name="author" placeholder="Author">
    <input type="text" name="publication_year" placeholder="Publication Year">
    <select name="availability">
        <option value="">Select Availability</option>
        <option value="Available">Available</option>
        <option value="Reserved">Reserved</option>
        <option value="Checked Out">Checked Out</option>
    </select>
    <input type="text" name="language" placeholder="Language">
    <input type="submit" value="Search">
</form>

<div class="results">
    <?php if (!empty($searchResults)): ?>
        <h3>Search Results:</h3>
        <ul>
            <?php foreach ($searchResults as $book): ?>
                <li>
                    <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <p>Availability: <?php echo htmlspecialchars($book['availability']); ?></p>
                    <p><img src="<?php echo htmlspecialchars($book['cover_thumbnail']); ?>" alt="Book Cover" style="width: 100px;"></p>
                    <p><a href="bookdetails.php?id=<?php echo $book['id']; ?>">View Details</a></p>
                    <button onclick="reserveBook(<?php echo $book['id']; ?>)">Reserve</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>

<script>
function reserveBook(bookId) {
    // Add your reservation logic here
    alert("Book with ID " + bookId + " reserved!");
}
</script>
</body>
</html>