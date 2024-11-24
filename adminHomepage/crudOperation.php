<?php
class Book {
    private $conn;
    private $tbl_name = "books";

    public $book_id;
    public $book_title;
    public $book_author;
    public $book_isbn;
    public $published_year;
    public $book_genre;
    public $book_publisher;
    public $available_copies;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " .$this->tbl_name ." (book_title, book_author, book_isbn, published_year, book_genre, book_publisher, available_copies) VALUES (:bookTitle, :bookAuthor, :bookIsbn, :publishedYear, :bookGenre, :bookPublisher, :availableCopies)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':bookTitle', $this->book_title);
        $stmt->bindParam(':bookAuthor', $this->book_author);
        $stmt->bindParam(':bookIsbn', $this->book_isbn);
        $stmt->bindParam(':publishedYear', $this->published_year);
        $stmt->bindParam(':bookGenre', $this->book_genre);
        $stmt->bindParam(':bookPublisher', $this->book_publisher);
        $stmt->bindParam(':availableCopies', $this->available_copies);

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
        $query = "DELETE FROM " .$this->tbl_name ." WHERE Book_ID = :bookId";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':bookId', $this->book_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " .$this->tbl_name . " SET 
                    book_title = :bookTitle, 
                    book_author = :bookAuthor, 
                    book_isbn = :bookIsbn, 
                    published_year = :publishedYear, 
                    book_genre = :bookGenre, 
                    book_publisher = :bookPublisher, 
                    available_copies = :availableCopies 
                  WHERE book_id = :bookId";
                  
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':bookTitle', $this->book_title);
        $stmt->bindParam(':bookAuthor', $this->book_author);
        $stmt->bindParam(':bookIsbn', $this->book_isbn);
        $stmt->bindParam(':publishedYear', $this->published_year);
        $stmt->bindParam(':bookGenre', $this->book_genre);
        $stmt->bindParam(':bookPublisher', $this->book_publisher);
        $stmt->bindParam(':availableCopies', $this->available_copies);
        $stmt->bindParam(':bookId', $this->book_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
}
?>
