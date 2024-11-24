<?php
class Users {
    private $conn;
    private $tbl_name = "users";

    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $phone_number;  
    public $roles;
    public $status;
    public $password;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    public function checkAccStatus($username) {
        $query = "SELECT status FROM " . $this->tbl_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        
        // Bind the user ID to the query
        $stmt->bindParam('username', $username);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['status'] == 'Suspended') {
            return true;  //account inactive
        } else {
            return false; //account active
        }
    }
    
    public function checkDuplicateAcc() {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    public function create() {

        if ($this->checkDuplicateAcc()) {
            echo "Username or Email already exists.";
            return false;
        }
    
        $query = "INSERT INTO " . $this->tbl_name . " (username, first_name, last_name, email, address, phone_number, roles, status, password) 
                VALUES (:username, :first_name, :last_name, :email, :address, :phone_number, :roles, :status, :password)";
        
        $stmt = $this->conn->prepare($query);

        $defaultRole = 'User';
        $defaultStatus = 'Active';
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':roles', $defaultRole);
        $stmt->bindParam(':status', $defaultStatus);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    } 
    
    public function read(){
        $query = "SELECT * FROM " .$this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function readID() {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt;
    }
    
    public function update() {
        $query = "UPDATE " . $this->tbl_name . " 
                  SET username = :username, first_name = :first_name, last_name = :last_name, email = :email, address = :address, phone_number = :phone_number, roles = :roles, status = :status
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':roles', $this->roles);
        $stmt->bindParam(':status', $this->status);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function updatePassword() {
        $query = "UPDATE " . $this->tbl_name . " 
                  SET password = :password
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->password);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
   
}
class Books {
    private $conn;
    private $tbl_name = "books";

    public $Book_ID;
    public $Book_Title;
    public $Book_Author;
    public $Book_ISBN;
    public $Published_Year;
    public $Book_Genre;
    public $Book_Publisher;
    public $Available_Copies;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " .$this->tbl_name ." (Book_Title, Book_Author, Book_ISBN, Published_Year, Book_Genre, Book_Publisher, Available_Copies) VALUES (:title, :author, :isbn, :published_year, :genre, :publisher, :available_copies)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->Book_Title);
        $stmt->bindParam(':author', $this->Book_Author);
        $stmt->bindParam(':isbn', $this->Book_ISBN);
        $stmt->bindParam(':published_year', $this->Published_Year);
        $stmt->bindParam(':genre', $this->Book_Genre);
        $stmt->bindParam(':publisher', $this->Book_Publisher);
        $stmt->bindParam(':available_copies', $this->Available_Copies);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read(){
        $query = "SELECT * FROM " .$this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function readID() {
        $query = "SELECT * FROM Books WHERE Book_ID = :Book_ID LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Book_ID', $this->Book_ID);
        $stmt->execute();
    
        return $stmt;
    }

    public function delete() {
        $query = "DELETE FROM " .$this->tbl_name ." WHERE Book_ID = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':id', $this->Book_ID);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateBookCopies() {
        $query = "UPDATE " . $this->tbl_name . " SET Available_Copies = Available_Copies - 1 WHERE Book_ID = :Book_ID";
        $stmt = $this->conn->prepare($query);
    
        // Bind the Book_ID to the query
        $stmt->bindParam(':Book_ID', $this->Book_ID);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    
}
class Reservations {
    private $conn;      
    private $tbl_name = "reservation";


    public $book_id; // foreign key
    public $reservation_id;
    public $name;
    public $email;
    public $phone_number;
    public $reservation_date;
    public $pickup_date;
    public $duration;
    public $expected_return_date;
    public $status; // pending, active, cancelled
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to create a new reservation
    public function create() {
        $query = "INSERT INTO " . $this->tbl_name . " (book_id, name, email, phone_number, reservation_date, pickup_date, duration, expected_return_date, status, notes)
          VALUES (:book_id, :name, :email, :phone_number, :reservation_date, :pickup_date, :duration, :expected_return_date, :status, :notes)";

        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $defaultStatus = 'Pending Approval';
        
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':reservation_date', $this->reservation_date);
        $stmt->bindParam(':pickup_date', $this->pickup_date);
        $stmt->bindParam(':duration', $this->duration);
        $stmt->bindParam(':expected_return_date', $this->expected_return_date);
        $stmt->bindParam(':status', $defaultStatus); //set 
        $stmt->bindParam(':notes', $this->notes);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Function to read reservation list
    public function read() {
        $query = "SELECT * FROM reservations";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function setStatus($status) { //pending, active, cancelled, overdue ADMIN
        $query = "UPDATE " . $this->tbl_name . " SET status = :status WHERE reservation_id = :reservation_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':reservation_id', $this->reservation_id);
        $stmt->execute();
    }         
   
    

    
}
    

                                
?>
