<?php
class Users {
    private $conn;
    private $tbl_name = "users";

    public $user_id;
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

    public function readID(){
        $query = "SELECT username, first_name, last_name, email, address, phone_number 
              FROM " . $this->tbl_name . " 
              WHERE user_id = :user_id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->tbl_name . " 
                  SET username = :username, first_name = :first_name, last_name = :last_name, email = :email, address = :address, phone_number = :phone_number, roles = :roles, status = :status
                  WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $this->user_id);
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
                  WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->password);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getUserDetails($user_id) {
        $query = "SELECT user_id, first_name, last_name, username, email, address, phone_number FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id); //bind user id
        $stmt->execute();

        return $stmt;
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
    public $Book_Cover;

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

    public function read10Books(){
        $query = "SELECT * FROM " .$this->tbl_name. " LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read(){
        $query = "SELECT * FROM " .$this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " .$this->tbl_name . " SET 
                    Book_Title = :bookTitle, 
                    Book_Author = :bookAuthor, 
                    Book_ISBN = :bookIsbn, 
                    Published_Year = :publishedYear, 
                    Book_Genre = :bookGenre, 
                    Book_Publisher = :bookPublisher, 
                    Available_Copies = :availableCopies 
                  WHERE Book_ID = :bookId";
                  
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':bookTitle', $this->Book_Title);
        $stmt->bindParam(':bookAuthor', $this->Book_Author);
        $stmt->bindParam(':bookIsbn', $this->Book_ISBN);
        $stmt->bindParam(':publishedYear', $this->Published_Year);
        $stmt->bindParam(':bookGenre', $this->Book_Genre);
        $stmt->bindParam(':bookPublisher', $this->Book_Publisher);
        $stmt->bindParam(':availableCopies', $this->Available_Copies);
        $stmt->bindParam(':bookId', $this->Book_ID);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
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


    public $book_id;
    public $reservation_id;
    public $name;
    public $email;
    public $phone_number;
    public $reservation_date;
    public $pickup_date;
    public $duration;
    public $expected_return_date;
    public $status; 
    public $notes;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->tbl_name . " (book_id, name, email, phone_number, reservation_date, pickup_date, duration, expected_return_date, status, notes, user_id)
          VALUES (:book_id, :name, :email, :phone_number, :reservation_date, :pickup_date, :duration, :expected_return_date, :status, :notes, :user_id)";

        $stmt = $this->conn->prepare($query);
        
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
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function read() {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function getUserReservations($user_id) {
        $query = "SELECT 
                    b.Book_Title, 
                    b.Book_Author, 
                    r.reservation_date,
                    r.pickup_date,
                    r.duration,
                    r.expected_return_date,
                    r.notes,
                    r.status,
                    r.reservation_id
                 FROM " . $this->tbl_name . " r 
                 INNER JOIN books b ON r.book_id = b.Book_ID
                 WHERE r.user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getNoOfActiveReservations($user_id) {
        $query = "SELECT COUNT(*) FROM " .$this->tbl_name. " WHERE user_id = :user_id AND status = 'Approved'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();   
        return $stmt->fetchColumn();
        }
   
    

    
}

class Notifications{
    private $conn;
    private $tbl_name = "notifications";


    public $notification_id;
    public $user_id;
    public $message;


    public function __construct($db){
        $this->conn = $db;
    }

    public function saveNotification($message) {
        $query = "INSERT INTO " . $this->tbl_name . "(user_id, message) VALUES (:user_id, :message)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->bindParam(':message', $message);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function createNotification($title, $message, $type){

        echo "<div class='notification $type'>";
        echo "<h3>$title</h3>";
        echo "<p>$message</p>";
        echo "<small>Received on: " . date('Y-m-d H:i:s') . "</small>";
        echo "</div><br>";
    }

    public function pendingBooking($userName, $bookTitle) {
        $message = "Dear $userName, thank you for submitting your request to borrow the book '$bookTitle'. Your booking is currently **pending approval**. You will be notified once the request is reviewed.";
        
        $this->saveNotification($message);
    }
    
    public function cancelledBooking($userName, $bookTitle){
        $this->createNotification(
            'Cancelled Book Borrowing Request',
            "$userName, your booking for the book '$bookTitle' has been cancelled.",
            'warning'
        );
    }

    public function approvedBooking($userName, $bookTitle){
        $this->createNotification(
            'Approved Booking',
            "$userName, your booking for the book '$bookTitle' has been approved!",
            'success'
        );
    }

    public function successfulSignup($userName){
        $this->createNotification(
            'Signup Successful',
            "Welcome, $userName! Your account has been successfully created.",
            'success'
        );
    }

    public function overdueBooking($userName, $bookTitle){
        $this->createNotification(
            'Overdue Booking',
            "$userName, the book '$bookTitle' is overdue. Please return it as soon as possible.",
            'error'
        );
    }

    public function returnedBooks($userName, $bookTitle){
        $this->createNotification(
            'Books Returned',
            "$userName, you have successfully returned the book '$bookTitle'.",
            'success'
        );
    }

    public function featuredBooks($bookTitle){
        $this->createNotification(
            'Featured Book',
            "Check out our featured book of the week: '$bookTitle'.",
            'info'
        );
    }

    function getUserNotifications($userId) {
        $query = "SELECT subject, message FROM notifications WHERE user_id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($user_id) {
        $query = "SELECT message FROM " .$this->tbl_name. " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt;
    }
}
                                
?>
