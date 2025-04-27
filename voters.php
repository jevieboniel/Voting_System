<?php include('db_connect.php'); ?>

<style>
    body {
        background-color: skyblue;
    }
    table {
        background: white;
        border-collapse: collapse;
        width: 80%;
        margin: 20px auto;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        vertical-align: middle;
    }
    th {
        background-color: blue;
        color: white;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    h2 {
        text-align: center;
        margin-top: 20px;
        color: darkblue;
    }
    img.voter-photo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #4CAF50;
    }
</style>

<h2>List of Voters</h2>

<table>
    <thead>
        <tr>
            <th bg-primary>No.</th>
            <th>Voter Name</th>
            <th>Username</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $voters = $conn->query("SELECT * FROM users WHERE type = 2 ORDER BY id ASC");
        $i = 1;
        while ($row = $voters->fetch_assoc()):
            // Safely build voter name
            $name = 'N/A';
            if (isset($row['lastname']) && isset($row['firstname'])) {
                $name = ucwords($row['lastname'] . ', ' . $row['firstname']);
            } elseif (isset($row['name'])) { // If only 'name' column exists
                $name = ucwords($row['name']);
            } elseif (isset($row['username'])) { // Fallback to username
                $name = $row['username'];
            }
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $name; ?></td>
            <td><?php echo !empty($row['username']) ? $row['username'] : 'N/A'; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
