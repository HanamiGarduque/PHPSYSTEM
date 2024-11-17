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
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function checkDuplicateAcc() {
        $query = "SELECT * FROM " . $this->tbl_name . " WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    //insert data to Database
    public function create() {

        if ($this->checkDuplicateAcc()) {
            echo "Username or Email already exists.";
            return false;
        }
        $query = "INSERT INTO " . $this->tbl_name . " (username, first_name, last_name, email, address, password) 
                VALUES (:username, :first_name, :last_name, :email, :address, :password)";
        
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }   
    //Read data in database
    
    public function read(){
        $query = "SELECT * FROM " .$this->tbl_name;
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

    // Constructor to initialize the database connection
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
