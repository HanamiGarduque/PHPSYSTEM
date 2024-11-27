<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN: Book Management</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#bookTable').DataTable();
        });
    </script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="bookCatalog.php">Book Catalog</a></li> 
            <li><a href="account.php">My Account</a></li> 
            <li><a href="history.php">Borrow History</a></li> 
            <li><a href="contact.php">Contact Us</a></li> 
            <li><a href="reservationForm.php">Reservation Form</a></li> 
        </ul>
    </nav>
<h2> Books List </h2>
    <table id="bookTable" class="display">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Published Year</th>
                <th>Genre</th>
                <th>Publisher</th>
                <th>Available Copies</th>
                <th>Actions</th> <!-- Optional actions for Edit -->
            </tr>
        </thead>

        <tbody>

            <?php
            require_once './Database/database.php';
            require_once './Database/crud.php';
            
            $database = new Database();
            $db = $database->getConnect();

            $book = new Books($db); // Assuming you have a 'Books' class
            $stmt = $book->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" . (isset($row['Book_Title']) ? htmlspecialchars($row['Book_Title']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Author']) ? htmlspecialchars($row['Book_Author']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_ISBN']) ? htmlspecialchars($row['Book_ISBN']) : '') . "</td>";
                    echo "<td>" . (isset($row['Published_Year']) ? htmlspecialchars($row['Published_Year']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Genre']) ? htmlspecialchars($row['Book_Genre']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Publisher']) ? htmlspecialchars($row['Book_Publisher']) : '') . "</td>";
                    echo "<td>" . (isset($row['Available_Copies']) ? htmlspecialchars($row['Available_Copies']) : '') . "</td>";
                    echo "<td><a href='reservationForm.php?Book_ID=" . htmlspecialchars($row['Book_ID']) . "'>borrow</a></td>";
                    echo "</tr>";
                }
            }
            // Check if the notification session is set
            if (isset($_SESSION['notification'])) {
                $notification = $_SESSION['notification'];
                echo "<script>
            Swal.fire({
                title: '{$notification['title']}',
                        text: '{$notification['message']}',
                        icon: '{$notification['type']}',
                confirmButtonText: 'Okay',
                background: '#fff',
                backdrop: true,
            }).then(() => {
                window.location.href = 'book.php'; // Redirect to the book page or any other page
            });
          </script>";
    exit();
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Failed to create the reservation. Please try again.';
        echo "<script>
                Swal.fire({
                    title: '{$notification['title']}',
                            text: '{$notification['message']}',
                            icon: '{$notification['type']}',
                    confirmButtonText: 'Okay',
                    background: '#fff',
                    backdrop: true,
                }).then(() => {
                    window.location.href = 'reservationForm.php'; // Redirect to the reservation form page
                });
            </script>";
        exit();
    }
               
            ?>
            
            
        </tbody>
    </table>

</body>
</html>
