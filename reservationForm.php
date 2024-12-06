<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Reservation Form</title>
    <link rel="stylesheet" href="./css/reservation.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <?php
    require_once 'check_session.php';
    require_once './Database/database.php';
    require_once './Database/crud.php';

    $id = isset($_GET['Book_ID']) ? $_GET['Book_ID'] : die('ERROR: Book ID not found.');

    $database = new Database();
    $db = $database->getConnect();

    $book = new Books($db);
    $book->Book_ID = $id;

    $query = "SELECT username, first_name, last_name, email, phone_number FROM users WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['id']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['username'];
    if (!$user) {
        echo "Error: User details not found.";
        exit;
    }

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

    echo $book->Book_Title;
    ?>

    <form method="POST" action="">
        <div class="form-header">
            <button type="button" onclick="window.history.back();" title="Go back">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1>Book Borrowing Form</h1>
            <p>Please provide the required details to reserve a book.</p>
        </div>

        <input type="hidden" name="book_id" value="<?php echo $book->Book_ID; ?>">

        <div class="left-column">
            <h3>Book Information</h3>
            <span style="color: #727D3D;">Book Title:</span><br>
            <input type="text" name="Book_Title" value="<?php echo htmlspecialchars($book->Book_Title, ENT_QUOTES); ?>" readonly><br>

            <span style="color: #727D3D;">Book Author:</span><br>
            <input type="text" name="Book_Author" value="<?php echo htmlspecialchars($book->Book_Author, ENT_QUOTES); ?>" readonly><br>

            <span style="color: #727D3D;">Book ISBN:</span><br>
            <input type="text" name="Book_ISBN" value="<?php echo htmlspecialchars($book->Book_ISBN, ENT_QUOTES); ?>" readonly><br>

            <span style="color: #727D3D;">Published Year:</span><br>
            <input type="text" name="Published_Year" value="<?php echo htmlspecialchars($book->Published_Year, ENT_QUOTES); ?>" readonly><br>
        </div>

        <div class="right-column">
            <h3>User Information</h3>
            <span style="color: #727D3D;">Full Name:</span><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']); ?>"><br>

            <span style="color: #727D3D;">Email Address:</span><br>
            <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>

            <span style="color: #727D3D;">Phone Number:</span><br>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>"><br>
        </div>

        <div class="bottom-column">
            <h3>Reservation Details</h3>
            <span style="color: #727D3D;">Reservation Date and Time:</span><br>
            <input type="datetime-local" name="reservation_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required readonly> <br>

            <span style="color: #727D3D;">Pick-Up Date:</span><br>
            <input type="date" id="pickup_date" name="pickup_date" required>

            <span style="color: #727D3D;">Duration:</span><br>
            <select name="duration" id="duration" onchange="calculateExpectedReturnDate()" required>
                <option value="7">7 days</option>
                <option value="14">14 days</option>
                <option value="21">21 days</option>
            </select>

            <span style="color: #727D3D;">Expected Return Date:</span><br>
            <input type="date" id="expected_return_date" name="expected_return_date" readonly required>

            <span style="color: #727D3D;">Notes/Comments:</span><br>
            <textarea name="notes" rows="4" cols="50"></textarea>
        </div>

        <script>
            function calculateExpectedReturnDate() {
                var duration = parseInt(document.getElementById('duration').value);
                var pickupDate = document.getElementById('pickup_date').value;

                if (pickupDate) {
                    var pickupDateObj = new Date(pickupDate);
                    pickupDateObj.setDate(pickupDateObj.getDate() + duration);
                    var expectedReturnDate = pickupDateObj.toISOString().split('T')[0];
                    document.getElementById('expected_return_date').value = expectedReturnDate;
                } else {
                    alert('Please select a pickup date first.');
                }
            }
            document.getElementById('pickup_date').addEventListener('change', calculateExpectedReturnDate);
            let localDate = new Date();

            let utcDate = new Date(localDate.toISOString());

            let year = utcDate.getFullYear();
            let month = String(utcDate.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            let day = String(utcDate.getDate()).padStart(2, '0');
            let hours = String(utcDate.getHours()).padStart(2, '0');
            let minutes = String(utcDate.getMinutes()).padStart(2, '0');
            let seconds = String(utcDate.getSeconds()).padStart(2, '0');

            document.querySelector("input[type='datetime-local']").value = `${year}-${month}-${day}T${hours}:${minutes}`;
        </script>

        <input type="checkbox" id="terms" name="terms" required> I agree to the terms and conditions
        <input type="submit" value="Submit">
    </form>

    <?php
    $database = new Database();
    $db = $database->getConnect();

    $reservation = new Reservations($db);
    $book = new Books($db);
    $notification = new Notifications($db);

    $reservation_count = $reservation->getNoOfActiveReservations($_SESSION['id']);


    if ($reservation_count > 3) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'You have 3 active borrowed books, please finish your current borrowed books before making a new reservation.',
                icon: 'error',
                confirmButtonText: 'Close',
                background: '#fff',
                backdrop: true
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit();
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {


            $user_id = $_SESSION['id'];

            $Book_ID = isset($_POST['book_id']) ? $_POST['book_id'] : die('ERROR: Book ID not found.');
            $book->Book_ID = $Book_ID;  

            $stmt = $book->readID();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $book_title = $row['Book_Title'];
            if (!$row) {
                die('ERROR: No data found for the given Book ID.');
            }

            $reservation->book_id = $Book_ID;
            $reservation->name = htmlspecialchars(trim($_POST['name']));
            $reservation->email = htmlspecialchars(trim($_POST['email']));
            $reservation->phone_number = htmlspecialchars(trim($_POST['phone_number']));
            $reservation->reservation_date = htmlspecialchars(trim($_POST['reservation_date']));
            $reservation->pickup_date = htmlspecialchars(trim($_POST['pickup_date']));
            $reservation->duration = htmlspecialchars(trim($_POST['duration']));
            $reservation->expected_return_date = htmlspecialchars(trim($_POST['expected_return_date']));
            $reservation->notes = htmlspecialchars(trim($_POST['notes']));
            $reservation->user_id = $user_id;

            if ($reservation->create()) {
                $notification->user_id = $user_id;

                $notification->pendingBooking($username, $book_title);
                echo $book->Book_Title;

                echo "<script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Pending Book Borrowing Approval'
                }).then(() => {
                    window.location.href = 'homepage.php';
                });
            </script>";
            } else {
                echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error creating form',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    background: '#fff',
                    backdrop: true,
                }).then(() => {
                    window.location.href = 'homepage.php';
                });
              </script>";
            }
        }
    }
    ?>


</body>

</html>