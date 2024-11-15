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
//insert data to Database
public function create() {
    $query = "INSERT INTO " . $this->tbl_name . " (Username, First_Name, Last_Name, Email, Address, Password) 
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
?>
