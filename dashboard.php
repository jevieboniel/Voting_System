<style>
    body {
        background-color: lightgray;
    }
    .custom-menu {
        z-index: 1000;
        position: absolute;
        background-color: #ffffff;
        border: 1px solid #0000001c;
        border-radius: 5px;
        padding: 8px;
        min-width: 13vw;
    }
    .chart-container {
        height: 350px;
    }
</style>

<div class="container-fluid">
<?php 
    include('db_connect.php');
    $voting = $conn->query("SELECT * FROM voting_list WHERE is_default = 1");
    foreach ($voting->fetch_array() as $key => $value) {
        $$key = $value;
    }
    $votes = $conn->query("SELECT v.*, o.partylist FROM votes v INNER JOIN voting_opt o ON o.id = v.voting_opt_id WHERE v.voting_id = $id");

    $partylist_fullnames = [
        'ASLE' => 'Alliance of Student Leaders',
        'SVEA' => 'Students for Vision & Action',
        'Independent' => 'Independent'
    ];

    $partylist_votes = ['ASLE' => 0, 'SVEA' => 0, 'Independent' => 0];
    while($row = $votes->fetch_assoc()){
        $partylist = $row['partylist'] ?? 'Independent';
        if (isset($partylist_votes[$partylist])) {
            $partylist_votes[$partylist]++;
        }
    }

    $partylist_labels = json_encode(array_keys($partylist_votes));
    $partylist_counts = json_encode(array_values($partylist_votes));
?>

<!-- Cards Section -->
<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card col-md-4 offset-2 bg-primary float-left">
            <div class="card-body text-white">
                <h4><b>Voters</b></h4>
                <hr>
                <span class="card-icon"><i class="fa fa-users"></i></span>
                <h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM users WHERE type = 2')->num_rows ?></b></h3>
            </div>
        </div>

        <div class="card col-md-4 offset-2 bg-warning ml-4 float-left">
            <div class="card-body text-white">
                <h4><b>Voted</b></h4>
                <hr>
                <span class="card-icon"><i class="fa fa-user-tie"></i></span>
                <h3 class="text-right"><b><?php echo $conn->query('SELECT DISTINCT(user_id) FROM votes WHERE voting_id = '.$id)->num_rows ?></b></h3>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mt-5">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body d-flex justify-content-center align-items-center chart-container">
                <div>
                    <h4 class="text-center"><b>Partylist Vote Counts</b></h4>
                    <canvas id="partylistChart" width="300" height="300" style="display: block; margin: auto;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('partylistChart').getContext('2d');
const partylistChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo $partylist_labels; ?>,
        datasets: [{
            label: 'Vote Counts',
            data: <?php echo $partylist_counts; ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',     // ASLE
                'rgba(255, 206, 86, 0.7)',     // SVEA
                'rgba(75, 192, 192, 0.7)'      // Independent
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: false, // Fixed size
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Partylist Vote Distribution'
            }
        }
    }
});
</script>
