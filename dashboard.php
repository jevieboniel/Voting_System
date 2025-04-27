<style>
    body{
        background-color: skyblue;
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
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
	span.card-icon {
    position: absolute;
    font-size: 3em;
    bottom: .2em;
    color: #ffffff80;
}
.file-item{
	cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
table th,td{
	/*border-left:1px solid gray;*/
}
a.custom-menu-list span.icon{
		width:1em;
		margin-right: 5px
}
.candidate {
    margin: auto;
    width: 23vw;
    padding: 0 10px;
    border-radius: 20px;
    margin-bottom: 1em;
    display: flex;
    border: 3px solid #00000008;
    background: #8080801a;

}
.candidate_name {
    margin: 8px;
    margin-left: 3.4em;
    margin-right: 3em;
    width: 100%;
}
	.img-field {
	    display: flex;
	    height: 8vh;
	    width: 4.3vw;
	    padding: .3em;
	    background: #80808047;
	    border-radius: 50%;
	    position: absolute;
	    left: -.7em;
	    top: -.7em;
	}
	
	.candidate img {
    height: 100%;
    width: 100%;
    margin: auto;
    border-radius: 50%;
}
.vote-field {
    position: absolute;
    right: 0;
    bottom: -.4em;
}
</style>

<div class="containe-fluid">
	<?php include('db_connect.php') ;
	$voting = $conn->query("SELECT * FROM voting_list where  is_default = 1 ");
	foreach ($voting->fetch_array() as $key => $value) {
		$$key = $value;
	}
	$votes  = $conn->query("SELECT * FROM votes where voting_id = $id ");
	$v_arr = array();
	while($row=$votes->fetch_assoc()){
		if(!isset($v_arr[$row['voting_opt_id']]))
			$v_arr[$row['voting_opt_id']] = 0;

		$v_arr[$row['voting_opt_id']] += 1;
	}
	$opts = $conn->query("SELECT * FROM voting_opt where voting_id=".$id);
	$opt_arr = array();
		while($row=$opts->fetch_assoc()){
		$opt_arr[$row['category_id']][] = $row;

	}

	?>
	<div class="row mt-5">
		<div class="col-lg-12">
			<div class="card col-md-4 offset-2 bg-primary float-left">
				<div class="card-body text-white">
					<h4><b>Voters</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-users"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT * FROM users where type = 2 ')->num_rows ?></b></h3>
				</div>
			</div>
			<div class="card col-md-4 offset-2 bg-warning ml-4 float-left">
				<div class="card-body text-white">
					<h4><b>Voted</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-user-tie"></i></span>
					<h3 class="text-right"><b><?php echo $conn->query('SELECT distinct(user_id) FROM votes where voting_id = '.$id)->num_rows ?></b></h3>
				</div>
			</div>
		</div>
	</div>
	</div>

</div>
<script>
	
</script>