<?php 
require_once 'check_session.php';
require_once './Database/database.php';
require_once './Database/crud.php';

$database = new Database();
$db = $database->getConnect();
$book = new Books($db); // Assuming you have a 'Books' class
$stmt = $book->read();
$num = $stmt->rowCount();

// Check if there is a notification message
if (isset($_SESSION['notification_message'])) {
    $message = $_SESSION['notification_message'];
    unset($_SESSION['notification_message']); // Clear the message after it has been shown

    // Output JavaScript to display SweetAlert
    echo "
    <script>
        Swal.fire({
            title: 'Notification',
            text: '$message',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    </script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Homepage</title>
    <link rel="stylesheet" href="./css/homepage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    
</head>
<body>
    <header class="header">
        <div class="logo"></div>
        <nav class="nav">
            <a href="homepage.php" style="color: #F7E135;">Home</a>
            <a href="search_catalog.php">Search a Book</a>
            <a href="notifications.php">Notifications</a>
            <a href="myacc.php">My Account</a>
        </nav>
    </header>

    <div class="hero">
    </div>

    <div>
        <a href=""></a>
        <a href=""></a>
        <a href=""></a>            
    </div>
<section id="Services">
    <div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="">24/7 Access to Digital Resources</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled ">
                        <li> <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-unlock-fill" viewBox="0 0 16 16">
  <path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2"/>
</svg></li>
                        <li>Unlimited access to e-books</li>
                        <li>Round-the-clock availability</li>
                        <li>Basic email support</li>
                        <li>Help center access</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="">Join Our 1,000+ Active Users</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled ">
                        <li><svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
</svg></li>
                        <li>Priority access to resources</li>
                        <li>10 GB of storage</li>
                        <li>Dedicated email support</li>
                        <li>Exclusive workshops</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header ">
                    <h3 class="">100+ New Titles Added Every Month</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled ">
                        <li><svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
  <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
</svg></li>
                        <li>dahjsdvagvdahgsd</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        <li>Monthly updates on new titles</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    <section class="featured-section">
    <div class="featured-header">
        <h1>Featured Books</h1>
    </div>
    <div class="books-grid">
        <?php
        $stmt = $book->read10Books(); // Fetch all books or use a condition for featured books
        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "
                <div class='book'>
                    <img src='Images/{$Book_Cover}' alt='{$Book_Title} Cover'> <!-- assuming the image path is stored in 'cover_image' -->
                    <h4>{$Book_Title}</h4>
                    <p>Author: {$Book_Author}<p>
                    <p>Published Year: {$Published_Year}<p>
                    <p>ISBN: {$Book_ISBN}<p>

                    <p class='borrow-btn-container'>
                        <a href='reservationForm.php?Book_ID={$Book_ID}' class='borrow-btn'>Borrow Book</a>
                    </p>
                </div>";
            }
        } else {
            echo "<p>No books found.</p>";
        }
        ?>
    </div>
</section>

    <section class="most-borrowed">
        <h2>Most Borrowed Books This Month</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>The Great Gatsby</h4>
                <p>Borrowed 156 times</p>
            </div>
            <div class="stat-card">
                <h4>1984</h4>
                <p>Borrowed 142 times</p>
            </div>
            <div class="stat-card">
                <h4>To Kill a Mockingbird</h4>
                <p>Borrowed 138 times</p>
            </div>
        </div>
    </section>

        <footer>
        <div class="container">
            <div class="company-info">
                <p><i class="fas fa-building"></i> © Blib: Library Management System</p>
            </div>

            <ul class="social-icons">
                <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </ul>
        </div>
    </footer>
</div>


</body>
</html>