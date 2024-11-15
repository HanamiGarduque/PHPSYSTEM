<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blib: Library Management System</title>
    
</head>
<body>
<h2>Register</h2>
    <form method="POST" action="registerDB.php">
        <fieldset>
            <legend>>Register</legend>
        Username: <input type="text" name="username" required>
        <br><br>
        First Name: <input type="text" name="first_name" required>
        <br><br>
        Last Name: <input type="text" name="last_name" required>
        <br><br>
        Email: <input type="email" name="email" required>
        <br><br>
        Address: <input type="text" name="address" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        <input type="submit" value="Register">
        
        </fieldset>
    </form>

    <button type="button">Sign in</button>     
</body>

</html>