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

    public $id;
    public $title;
    public $author;
    public $isbn;
    public $published_year;
    public $genre;
    public $publisher;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addBooks() {
        $query = "INSERT INTO " . $this->tbl_name . " (title, author, isbn, published_year, genre, publisher)
                  VALUES (:title, :author, :isbn, :published_year, :genre, :publisher)";
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':published_year', $this->published_year);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':publisher', $this->publisher);
    
        // Execute the query
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    public function read() {
        $query = "SELECT * FROM books";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    } 

}

class Reservations {
    private $conn;      
    private $tbl_name = "reservation";

    public $id;
    public $username;
    public $email;
    public $phone_number;
    public $pickup_date;
    public $expected_return_date;
    public $reservation_date;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to create a new reservation
    public function create() {
        $query = "INSERT INTO" . $this->tbl_name . "(username, email, phone_number, pickup_date, expected_return_date, reservation_date, notes)
                  VALUES (:name, :email, :phone_number, :pickup_date, :expected_return_date, :reservation_date, :notes)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':pickup_date', $this->pickup_date);
        $stmt->bindParam(':expected_return_date', $this->expected_return_date);
        $stmt->bindParam(':reservation_date', $this->reservation_date);
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
}
    

                                
?>
