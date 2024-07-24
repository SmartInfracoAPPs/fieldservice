<?php
require 'config.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    try {
        $query = "SELECT * FROM table_backup WHERE name LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $searchParam);
        
        $searchParam = "%{$search}%";
        $stmt->execute();
        
        $result = $stmt->get_result();
        
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

        $stmt->close();
    } catch (Exception $e) {
        echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
    }
    
    $conn->close();
}
?>
