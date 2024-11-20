<?php
// Include the Database class
include_once 'Database.php';

// Create an instance of the Database class
$database = new Database();
$conn = $database->getConnect();

// Check if the query parameter is set and not empty
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $query = "%" . trim($_GET['query']) . "%"; // Prepare query with wildcards

    // SQL to search books by title, author, ISBN, or genre
    $sql = "
        SELECT title, author, isbn, genre
        FROM books
        WHERE 
            title LIKE :query OR
            author LIKE :query OR
            isbn LIKE :query OR
            genre LIKE :query
        LIMIT 50;
    ";

    try {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();

        // Check if any results are found
        if ($stmt->rowCount() > 0) {
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = [
                    'title' => htmlspecialchars($row['title']),
                    'author' => htmlspecialchars($row['author']),
                    'isbn' => htmlspecialchars($row['isbn']),
                    'genre' => htmlspecialchars($row['genre']),
                ];
            }
            // Return JSON response
            echo json_encode(['data' => $results]);
        } else {
            // Return empty data set for DataTables
            echo json_encode(['data' => []]);
        }
    } catch (PDOException $e) {
        // Handle SQL errors gracefully
        error_log($e->getMessage());
        echo json_encode(['error' => 'An error occurred. Please try again later.']);
    }
} else {
    // Return error if query is not provided
    echo json_encode(['error' => 'Please enter a search query.']);
}

// Close the connection (optional with PDO)
$conn = null;
?>
