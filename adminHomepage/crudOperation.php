<?php
class Book {
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

    public function delete() {
        $query = "DELETE FROM " .$this->tbl_name ." WHERE Book_ID = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':id', $this->Book_ID);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " .$this->tbl_name . " SET 
                    Book_Title = :title, 
                    Book_Author = :author, 
                    Book_ISBN = :isbn, 
                    Published_Year = :published_year, 
                    Book_Genre = :genre, 
                    Book_Publisher = :publisher, 
                    Available_Copies = :available_copies 
                  WHERE Book_ID = :id";
                  
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':title', $this->Book_Title);
        $stmt->bindParam(':author', $this->Book_Author);
        $stmt->bindParam(':isbn', $this->Book_ISBN);
        $stmt->bindParam(':published_year', $this->Published_Year);
        $stmt->bindParam(':genre', $this->Book_Genre);
        $stmt->bindParam(':publisher', $this->Book_Publisher);
        $stmt->bindParam(':available_copies', $this->Available_Copies);
        $stmt->bindParam(':id', $this->Book_ID);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
}
?>
