<html>
  <head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	<link href="../css/main.css" rel="stylesheet" />
	<link href="../css/navbar.css" rel="stylesheet" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
<div id="wrapper">
<body>


<?php
	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$pkey = $_GET['id'];

	$sql     = "SELECT * FROM acc_store where snum = '$pkey'";
	$result  = mysqli_query($con, $sql);	

	if (!empty(mysqli_num_rows($result))) 
	{
		while($row=mysqli_fetch_array($result))
		{
			$id      = $row['id'];
			$snum    = $row['snum'];
			$name    = $row['name'];
			$cnum    = $row['cnum'];
			$ip      = $row['ip'];
			$address = $row['address'];
		}
	}	
?>
<header>
	<a href="store.php"><b>Inventory Simulation</b></a>
</header>

<?php echo "<label><a href='store.php'>Stores</a> > Packages and Features </label>";?>
	
	<ul>
		<?php echo "<br>$name <br><br>Store Id: $snum";?> <br><br>
		<li><?php echo "<a href='department.php?id=".$pkey."' >Departments</a> "?></li>
		<li><?php echo "<a class='active' href='storeproduct.php?id=".$pkey."'>Packages and Features</a> "?></li>
	</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px; ">
<br>
<div style="height:400px;width:1150px; border:1px solid #ccc;
	font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">

	<tr>
		<th> Packages  </th>
		<th> Feature Status </th>
		<th> Action </th>
	</tr>

<?php
	//select for product				
	$sql_feature = "SELECT * FROM product";
	$result_feature = mysqli_query($con, $sql_feature);
	while($row_feature=mysqli_fetch_array($result_feature))
	{
		$id      = $row_feature['id'];
		$feature = $row_feature['feature'];
		$name    = $row_feature['name'];
		$package = $row_feature['package'];
		 
		$status     = '0';
		$dcode      = 'N';
		$repo_check = "0";

		$sql_check = "SELECT * FROM store_product WHERE snum = '$pkey' AND feature = '$feature' ORDER BY dnum DESC LIMIT 1";
		$result_repo_check = mysqli_query($con, $sql_check);
		$repo_check =	mysqli_num_rows($result_repo_check);
		while($row_feature_status=mysqli_fetch_array($result_repo_check))
		{
			$status  = $row_feature_status['status'];
			$dnum    = $row_feature_status['dnum'];

			$sql_delivery_check = "SELECT * FROM store_delivery WHERE snum = '$pkey' AND dnum = '$dnum'";
			$result_delivery_check = mysqli_query($con, $sql_delivery_check);
			while($row_delivery_check=mysqli_fetch_array($result_delivery_check))
			{
				$dstatus = $row_delivery_check['dstatus'];

				$sql_delivery_code = "SELECT * FROM deploy_status WHERE status = '$dstatus'";
				$result_delivery_code = mysqli_query($con, $sql_delivery_code);
				while($row_delivery_code=mysqli_fetch_array($result_delivery_code))
				{
					$dcode = $row_delivery_code['code'];
				}
			}
		}

		//table display
		echo "<tr><td id='space'>{$package}</td>";

		if($repo_check)
		{
			if(is_numeric($status))
		{
			echo "<td><button style='background-color: #228B22; color:white;'> (1)</button><button>Completed</button></td>";
		} else {
			echo "<td></td>";
		}

		} else {
			echo "<td></td>";
		}
				
		if(empty($status))
		{					
		if(is_numeric(stripos($dcode, "C")) || is_numeric(stripos($dcode,"N")))
		{
			echo "<td><button ><a href='storeproduct.enable.php?id=store&id1=".$pkey."&id2=".$feature."'>Disabled</a></button></td>";
			} else {
		echo "<td><button><a href='storeproduct.php?id=".$pkey."'>Disabled</a></button></td>";
		}

		} else {
			if(is_numeric(stripos($dcode,"C")) || is_numeric(stripos($dcode,"N")))
			{
				echo "<td><button style='background-color: #1E90FF; color:white;'><a href='storeproduct.disable.php?id=store&id1=".$pkey."&id2=".$feature."'>Enabled</a></button></td>";
			} else {
				echo "<td><button style='background-color: #1E90FF; color:white;'><a href='storeproduct.php?id=".$pkey."'>Enabled</a></button></td>";
			}
		}
         echo "</tr>";
	}
?>
</table>

</div>
</div>

</body>
</div>
</html>