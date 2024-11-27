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
                $book->Book_ID = $row['Book_ID'];
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
        <input type="hidden" name="book_id" value="<?php echo $book->Book_ID; ?>">

        <fieldset>
                <legend>Book Information</legend>
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
                    <input type="text" name="name" required> 
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
                <input type="date" name="reservation_date" value="<?php echo date('Y-m-d'); ?>" required readonly>
                <br>
                
                Pick-Up Date: <br>
                <input type="date" id="pickup_date" name="pickup_date" required>
                <br>
                
                Duration: <br>    
                <select name="duration" id="duration" onchange="calculateExpectedReturnDate()" required>
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="21">21 days</option>
                </select><br>
                
                Expected Return Date: <br>
                <input type="date" id="expected_return_date" name="expected_return_date" readonly required>
                <br>                
                
                Notes/Comments: <br>
                <textarea name="notes" rows="4" cols="50"></textarea>
                <br><br>
            </fieldset>

    <script> //updates the date dynamically
        function calculateExpectedReturnDate() {
            // Get selected duration in days
            var duration = parseInt(document.getElementById('duration').value);
            
            // Get pick-up date
            var pickupDate = document.getElementById('pickup_date').value;
            
            if (pickupDate) {
                // Create a new date object with the pickup date
                var pickupDateObj = new Date(pickupDate);
                
                // Add the selected duration to the pick-up date
                pickupDateObj.setDate(pickupDateObj.getDate() + duration);
                
                // Format the expected return date to 'YYYY-MM-DD'
                var expectedReturnDate = pickupDateObj.toISOString().split('T')[0];
                
                // Set the calculated return date in the input field
                document.getElementById('expected_return_date').value = expectedReturnDate;
            } else {
                alert('Please select a pickup date first.');
            }
        }
        
        // Trigger return date calculation when the pick-up date is changed
        document.getElementById('pickup_date').addEventListener('change', calculateExpectedReturnDate);
    </script>

            <input type="checkbox" id="terms" name="terms" required> I agree to the terms and conditions
        
            <input type="submit" value="Submit">
        </form>
    <?php
    //check session variables 
        require_once './notifications.php';  // Ensure the notifications are availableyyyyy
            if (isset($_SESSION['notification'])) {
                
                $notification = $_SESSION['notification'];
                echo "<div class='notification {$notification['type']}'>";
                echo "<h3>{$notification['title']}</h3>";
                echo "<p>{$notification['message']}</p>";
                echo "<small>Received on: {$notification['timestamp']}</small>";
                echo "</div><br>";

                // Clear the notification after displaying it
                unset($_SESSION['notification']);
        }
    ?>


</body>

</html>