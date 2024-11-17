<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Library Management System</title>
    
</head>
<body>
<h2>Reservation Form</h2>
    <form method="POST" action="reservationDB.php">
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
                
                Pick-Up Date: <br>
                <input type="date" name="pickup_date" required>
                <br>
                Expected Return Date: <br>
                <input type="date" name="expected_return_date" required>
                <br>
                Reservation Date: <br>
                <input type="date" name="reservation_date" required>
                <br>
                Notes/Comments: <br>
                <textarea name="notes" rows="4" cols="50"></textarea>
                <br><br>
        </fieldset>
        
        <input type="submit" value="Submit">
    </form>

   </body>

</html>