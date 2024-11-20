<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#bookTable').DataTable();
        });
    </script>
</head>

<body>
    <h1>Admin Homepage</h1>
    <a href="add_book.php">
        <button>Add New Book</button>
    </a>

    <h2>List of Books</h2>
    <table id="bookTable" class="display">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Published Year</th>
                <th>Genre</th>
                <th>Publisher</th>
                <th>Available Copies</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'dbConnection.php';
            require_once 'crudOperation.php';

            $database = new Database();
            $db = $database->getConnect();

            $book = new Book($db);
            $stmt = $book->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . (isset($row['Book_ID']) ? htmlspecialchars($row['Book_ID']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Title']) ? htmlspecialchars($row['Book_Title']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Author']) ? htmlspecialchars($row['Book_Author']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_ISBN']) ? htmlspecialchars($row['Book_ISBN']) : '') . "</td>";
                    echo "<td>" . (isset($row['Published_Year']) ? htmlspecialchars($row['Published_Year']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Genre']) ? htmlspecialchars($row['Book_Genre']) : '') . "</td>";
                    echo "<td>" . (isset($row['Book_Publisher']) ? htmlspecialchars($row['Book_Publisher']) : '') . "</td>";
                    echo "<td>" . (isset($row['Available_Copies']) ? htmlspecialchars($row['Available_Copies']) : '') . "</td>";
                    echo "<td>
                            <form action='delete_book.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($row['Book_ID']) . "' />
                                <button type='button' class='delete-btn'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            }
            ?>

        </tbody>
    </table>

    <script>
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();

            const form = $(this).closest('form');
            const bookTitle = $(this).closest('tr').find('td:nth-child(2)').text();

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you really want to delete the book "${bookTitle}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>