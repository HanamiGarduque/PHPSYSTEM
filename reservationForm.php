<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Library Management System</title>
    
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
    <?php
            require_once './Database/database.php';
            require_once './Database/crud.php';
    
            $id = isset($_GET['Book_ID']) ? $_GET['Book_ID'] : die('ERROR: User ID not found.');
    
            $database = new Database();
            $db = $database->getConnect();
    
            $book = new Books($db);
            $book->Book_ID = $id;

            $reservation = new Reservations($db);
    
            $stmt = $book->readID();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                die('ERROR: No data found for the given Book ID.');
            }
            $book->Book_Title = $row['Book_Title'];
            $book->Book_Author = $row['Book_Author'];
            $book->Book_ISBN = $row['Book_ISBN'];
            $book->Published_Year = $row['Published_Year'];
            $book->Book_Genre = $row['Book_Genre'];
            $book->Book_Publisher = $row['Book_Publisher'];
            $book->Available_Copies = $row['Available_Copies'];
            
        ?>
<h2>Reservation Form</h2>
    <form method="POST" action="reservationDB.php">
    <fieldset>
            <legend>User Information</legend>
            Book Title: <br>
            <input type="text" name="Book_Title" value="<?php echo htmlspecialchars($book->Book_Title, ENT_QUOTES); ?>" readonly><br>
            Book Author: <br>
            <input type="text" name="Book_Author" value="<?php echo htmlspecialchars($book->Book_Author, ENT_QUOTES); ?>" readonly><br>
            Book ISBN: <br>
            <input type="text" name="Book_ISBN" value="<?php echo htmlspecialchars($book->Book_ISBN, ENT_QUOTES); ?>" readonly><br>
            Published Year: <br>
            <input type="text" name="Published_Year" value="<?php echo htmlspecialchars($book->Published_Year, ENT_QUOTES); ?>" readonly><br>
            <br>  <br>    
        </fieldset>
        <br>
        <fieldset>
            <legend>User Information</legend>
                Full Name: <br>
                <input type="text" name="username" required> 
                <br>     
                Email Address: <br>
                <input type="email" name="email" required>
                <br>  
                Phone Number: <br>
                <input type="text" name="phone_number" required>
                <br>  <br>    
        </fieldset>
        <br><br>

        <fieldset>
            <legend>Reservation Details</legend>
                Reservation Date: <br>
                <input type="date" name="reservation_date" required>
                <br>
                Pick-Up Date: <br>
                <input type="date" name="pickup_date" required>
                <br>
                Account Status: <br>    
                <select name="duration" id="duration">
                    <option value="7 days" <?php echo $reservation->duration == '7 days' ? 'selected' : ''; ?>> 7 days</option>
                    <option value="14 days" <?php echo $reservation->duration == '14 days' ? 'selected' : ''; ?>> 14 days</option>
                    <option value="21 days" <?php echo $reservation->duration == '21 days' ? 'selected' : '' ?>> 21 days</option>
                </select><br>
                Expected Return Date: <br>
                <input type="date" name="expected_return_date" required>
                <br>                
                Notes/Comments: <br>
                <textarea name="notes" rows="4" cols="50"></textarea>
                <br><br>
        </fieldset>
        <input type="checkbox" id="terms" name="terms" required> I agree to the terms and conditions
    
        <input type="submit" value="Submit">
    </form>
    <?php
    // Check for the session variables and trigger SweetAlert based on the status
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'success') {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'User created successfully!',
                    icon: 'success',
                    confirmButtonText: 'Okay',
                    background: '#fff',
                    backdrop: true
                });
              </script>";
    } elseif (isset($_SESSION['status']) && $_SESSION['status'] == 'error') {
        $message = (isset($_SESSION['message']) && $_SESSION['message'] == 'duplicate') 
                   ? 'Username or Email already exists!' 
                   : 'There was an error creating the user. Please try again.';
        
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: '$message',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    background: '#fff',
                    backdrop: true
                });
              </script>";
    }

    // Clear the session variables after use
    session_unset();
    session_destroy();
?>

   </body>

</html>