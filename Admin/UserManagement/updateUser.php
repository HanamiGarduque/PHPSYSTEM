<?php
require_once '../../check_session.php';
require_once '../../Database/database.php';
require_once '../../Database/crud.php';
ensureAdminAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../CSS/updateUser.css"> 
</head>
<body>

    <div class = "header">
        <h2>User Information Update</h2>
        <p>Please review and update the user's information as necessary.</p>
        <p>Ensure all fields are accurate, including personal details, account status, and access level.</p>
      
    </div>
  
    <div class="container">
        <?php
            
            $id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: User ID not found.');
    
            $database = new Database();
            $db = $database->getConnect();
    
            $user = new Users($db);
            $user->user_id = $id;
    
            $stmt = $user->readID();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            $user->username = $row['username'];
            $user->first_name = $row['first_name'];
            $user->last_name = $row['last_name'];
            $user->email = $row['email'];
            $user->address = $row['address'];
            $user->phone_number = $row['phone_number'];
            // $user->roles = $row['roles'];
            // $user->status = $row['status'];
        ?>
    
        <h2>Profile Information</h2>
        <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES); ?>">
            Username: <br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user->username, ENT_QUOTES); ?>"><br>
            First Name: <br>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user->first_name, ENT_QUOTES); ?>"><br>
            Last Name: <br>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user->last_name, ENT_QUOTES); ?>"><br>
            Email: <br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user->email, ENT_QUOTES); ?>"><br>
            Address: <br>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user->address, ENT_QUOTES); ?>"><br>
            Phone Number: <br>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user->phone_number, ENT_QUOTES); ?>"><br>
        
        <h2>Account Management</h2>
            Role: 
            <select name="roles">
                <option value="Admin" <?php echo $user->roles == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="User" <?php echo $user->roles == 'User' ? 'selected' : ''; ?>>User</option>
            </select><br>
            Account Status:
            <select name="status" id="status">
                <option value="Active" <?php echo $user->status == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="Suspended" <?php echo $user->status == 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
            </select><br>

            <input type="submit" value="Update User">
        </form>

        <a href="userManagement.php" class="back-btn">← Back to User Management</a>

        
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : die('ERROR: User ID not found.');
                $username = isset($_POST['username']) ? $_POST['username'] : '';
                $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
                $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $address = isset($_POST['address']) ? $_POST['address'] : '';
                $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : ''; 
                $roles = isset($_POST['roles']) ? $_POST['roles'] : '';
                $status = isset($_POST['status']) ? $_POST['status'] : '';
            
                $user->user_id = $user_id;
                $user->username = $username;
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->address = $address;
                $user->phone_number = $phone_number;
                $user->roles = $roles;
                $user->status = $status;
            
                if ($user->update()) {
                    echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'User updated successfully!',
                                icon: 'success',
                                confirmButtonText: 'Okay',
                                background: '#fff',
                                backdrop: true,
                            }).then(() => {
                                window.location.href = 'userManagement.php';
                            });
                          </script>";
                } else {
                    echo "<script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error updating user',
                                icon: 'error',
                                confirmButtonText: 'Try Again',
                                background: '#fff',
                                backdrop: true,
                            }).then(() => {
                                window.location.href = 'userManagement.php';
                            });
                          </script>";
                }
            }
        ?>
    </div>
</body>
</html>
