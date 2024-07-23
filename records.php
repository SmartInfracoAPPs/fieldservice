<?php
require 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <style>
      
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        

        nav {
            background-color: #333;
            color: #fff;
            padding: 20px 20px;
        }
        
        nav ul {
            list-style-type: none;
            margin: 20px 0px;
            padding: 10px 0px;
            text-align: center;
        }
        
        nav ul li {
            display: inline-block;
            margin-right: 20px;
        }
        
        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s;
        }
        
        nav ul li a:hover {
            background-color: #555;
        }
        
        /* Main content container */
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f2f2f2;
        }
        
        table img {
            max-width: 200px;
            height: auto;
        }
        
        /* Logout button */
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        
        .logout button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .logout button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li style="float: left;"><a href="upload.php">Create Record</a></li>
            <li style="float: left;"><a href="#">Views</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li> <!-- Adjust logout to the right -->
        </ul>
    </nav>

    <div class="container">
      <h3>Search Old Records</h3>
        <form>

            <input type="text" id="search" placeholder="Search by name id or record" style="width: 100%; padding: 10px; margin-bottom: 10px;  font-size: 20px;">
        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $query = "SELECT * FROM tb_images";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td><a href='edit.php?id={$row['id']}' style='text-decoration: none; color: #333;  font-size: 20px;'>" . htmlspecialchars($row['name']) . "</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }

                    $result->free_result();
                    $conn->close();
                } catch (Exception $e) {
                    echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for search functionality -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                var query = $(this).val();
                $.ajax({
                    url: 'bsearch.php',
                    method: 'POST',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        $('tbody').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
