<?php include('db_connect.php'); ?>

<style>
    body {
        background-color: lightgray;
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
    .status-voted {
        color: green;
        font-weight: bold;
    }
    .status-not-voted {
        color: red;
        font-weight: bold;
    }
</style>

<h2>List of Voters</h2>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Voter Name</th>
            <th>Username</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Get all voters
        $voters = $conn->query("SELECT * FROM users WHERE type = 2 ORDER BY id ASC");
        $i = 1;

        while ($row = $voters->fetch_assoc()):
            // Safely build voter name
            $name = 'N/A';
            if (isset($row['lastname']) && isset($row['firstname'])) {
                $name = ucwords($row['lastname'] . ', ' . $row['firstname']);
            } elseif (isset($row['name'])) {
                $name = ucwords($row['name']);
            } elseif (isset($row['username'])) {
                $name = $row['username'];
            }

            // Check if voter has already voted
            $user_id = $row['id'];
            $check_vote = $conn->query("SELECT * FROM votes WHERE user_id = $user_id LIMIT 1");

            if ($check_vote && $check_vote->num_rows > 0) {
                $status = "<span class='status-voted'>Voted</span>";
            } else {
                $status = "<span class='status-not-voted'>Not Voted</span>";
            }
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $name; ?></td>
            <td><?php echo !empty($row['username']) ? $row['username'] : 'N/A'; ?></td>
            <td><?php echo $status; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
