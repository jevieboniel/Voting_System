<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sidebar</title>
    <style>
        #sidebar {
            background-color: gray;
            padding: 10px;
            width: 250px;
            height: 100vh;
            margin-top: 0;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
            margin-top: 55px;
        }

        .sidebar-title {
            font-size: 16px;
            color: gold;
            margin: 0;
        }

        .sidebar-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-list a {
            display: block;
            padding: 15px;
            color: black;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar-list a:hover,
        .sidebar-list a.active {
            background-color: darkgray;
            color: white;
            text-decoration: none;
        }

        .icon-field {
            margin-right: 10px;
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="./images/TMC-Logo.png" alt="School Logo" class="logo">
            <h3 class="sidebar-title">Online School Voting System</h3>
        </div>

        <div class="sidebar-list">
            <a href="index.php?page=dashboard" class="nav-item bg-primary nav-home">
                <span class='icon-field'><i class="fas fa-tachometer-alt"></i></span> Dashboard
            </a>
            <a href="index.php?page=categories" class="nav-item bg-primary nav-categories">
                <span class='icon-field'><i class="fas fa-list"></i></span> Categories
            </a>
            <a href="index.php?page=voting_list" class="nav-item bg-primary nav-voting_list nav-manage_voting">
                <span class='icon-field'><i class="fas fa-users"></i></span> Candidates
            </a>
            <a href="index.php?page=casting_votes" class="nav-item bg-primary nav-casting_votes">
                <span class='icon-field'><i class="fas fa-vote-yea"></i></span> Votes
            </a>
            <a href="index.php?page=voters" class="nav-item bg-primary nav-voters">
                <span class='icon-field'><i class="fas fa-user-check"></i></span> Voters
            </a>
            <?php if(isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1): ?>
                <a href="index.php?page=users" class="nav-item bg-primary nav-users">
                    <span class='icon-field'><i class="fas fa-user-cog"></i></span> Users
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let activePage = "<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>";
            if (activePage) {
                document.querySelector('.nav-' + activePage)?.classList.add('active');
            }
        });
    </script>

</body>
</html>
