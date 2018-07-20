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

<!-- Page Content -->

<?php
	session_start();

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$pkey_feature = $_GET['id'];

	if(isset($_POST['cnum_search']))
	{ 
		$cnum_search = $_POST['cnum_search'];
		$_SESSION['search'] = $cnum_search;
	}

	$cnum_search = $_SESSION['search'];

	$sql_delivery = "SELECT * FROM delivery";
	$result_delivery = mysqli_query($con, $sql_delivery);
?>

<header>
   <a href="product.php"><b>Inventory Simulation</b></a>
</header>

<?php echo "<label><a href='product.php'>Product </a> > Packages and Features</label>";?>

<ul>
	<li><?php echo "<a class='active' href='product.display.php?id=".$pkey_feature."'>Packages and Features</a> "?></li>
</ul>
<br>

<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px; ">

<?php 
    //label
	 
	$sql_check = "SELECT * FROM product WHERE feature = '$pkey_feature'";
	$result_feature_check = mysqli_query($con, $sql_check);
	while($row_check=mysqli_fetch_array($result_feature_check))
	{
		$feature_name = $row_check['name'];
	}

	echo "<label>Feature:</label> $pkey_feature &nbsp;
		<label>Name:</label> $feature_name
		<br>";
?>

<form <?php echo "action='product.display.php?id=".$pkey_feature."'";?> method="POST">
   <div class="form-group col-sm-4">
      <input class="form-control" name="cnum_search" type="text" value="" placeholder="C#, or IP, or Name.." />
      <input class='submit' name='submit' type='submit' value='Submit' />
   </div>
</form>

<div style="height:400px;width:1150px; border:1px solid #ccc;
   font:16px/20px Georgia, Garamond, Serif; overflow:auto">
<table class="table ">

<?php
	//table headers
	echo "<tr>
	<th>C#</th>
	<th>Store#</th>
	<th>Name</th>
	<th>Feature Status</th>
	<th>Action</th>
	</tr>";
	
	//select for search
	if(empty($cnum_search))
	{
		$sql_distinct_feature = "SELECT * FROM acc_store ORDER BY name";
		$result = mysqli_query($con, $sql_distinct_feature);
	} else {
		$words = explode("+", $cnum_search);
		$phrase = implode("%' AND acc_store LIKE '%", $words);
		$sql_check = "SELECT * from acc_store where cnum like '%$phrase%'";
		$result = mysqli_query($con, $sql_check);

		if (empty(mysqli_num_rows($result)))
		{
			$words = explode("+", $cnum_search);
			$phrase = implode("%' AND acc_store LIKE '%", $words);
			$sql_check = "SELECT * from acc_store where ip like '%$phrase%'";
			$result = mysqli_query($con, $sql_check);

			if (empty(mysqli_num_rows($result)))
			{
				$words = explode("+", $cnum_search);
				$phrase = implode("%' AND acc_store LIKE '%", $words);
				$sql_check = "SELECT * from acc_store where name like '%$phrase%'";
				$result = mysqli_query($con, $sql_check);
			}
		}
	}

	//  Product Information
	while($row_store=mysqli_fetch_array($result))
	{
		$snum_distinct = $row_store['snum'];

		$sql_check = "SELECT * FROM acc_store WHERE snum = '$snum_distinct'";
		$result_store_check = mysqli_query($con, $sql_check);
		while($row_store_check=mysqli_fetch_array($result_store_check))
		{
			$store_name = $row_store_check['name'];
			$store_cnum = $row_store_check['cnum'];
		}

		$status     = '0';
		$dcode      = 'N';
		$repo_check = "0";

		//  Product Information
		$sql_check_distinct = "SELECT * FROM store_product WHERE snum = '$snum_distinct' and feature = '$pkey_feature' ORDER BY snum, dnum DESC LIMIT 1";
		$result_feature_check_distinct = mysqli_query($con, $sql_check_distinct)or die('Could not query database at this time');
		$repo_check =	mysqli_num_rows($result_feature_check_distinct);
		while($row_check_distinct=mysqli_fetch_array($result_feature_check_distinct))
		{
			$status = $row_check_distinct['status'];
			$dnum_distinct = $row_check_distinct['dnum'];

			// Delivery Information
			$sql_dstatus_store = "SELECT * FROM store_delivery WHERE dnum = '$dnum_distinct'";
			$result_dstatus_store = mysqli_query($con, $sql_dstatus_store);
			while($row_dstatus_store=mysqli_fetch_array($result_dstatus_store))
			{
				$dstatus_store = $row_dstatus_store['dstatus'];
			}
				
			// Deploy Status Information
			$sql_dstatus_name = "SELECT * FROM deploy_status WHERE status = '$dstatus_store'";
			$result_dstatus_name = mysqli_query($con, $sql_dstatus_name)or die('Could not query database at this time');
			while($row_dstatus_name=mysqli_fetch_array($result_dstatus_name))
			{
				$dcode = $row_dstatus_name['code'];
			}
		}

		//table display
		echo "<tr>
			<td>{$store_cnum}</td>
			<td>{$snum_distinct}</td>
			<td id='space'>{$store_name}</td>";

		//check to see what type of button to display given the status in the database
		if($repo_check)
		{
			if(is_numeric($status))
			{
				echo "<td><button style='background-color: #228B22; color:white;'>(1)</button><button>Completed</button></td>";
			}
		} else {
			echo "<td></td>";
		}

		if(empty($status))
		{					
			if(is_numeric(stripos($dcode, "C")) || is_numeric(stripos($dcode,"N")))
			{
				echo "<td><button ><a href='storeproduct.enable.php?id=product&id1=".$snum_distinct."&id2=".$pkey_feature."'>Disabled</a></button></td>";
			} else {
				echo "<td><button><a href='product.display.php?id=".$pkey_feature."'>Disabled</a></button></td>";
			}

		} else {
         
			if(is_numeric(stripos($dcode,"C")) || is_numeric(stripos($dcode,"N")))
			{
				echo "<td><button style='background-color: #1E90FF; color:white;'><a href='storeproduct.disable.php?id=product&id1=".$snum_distinct."&id2=".$pkey_feature."'>Enabled</a></button></td>";
			} else {
				echo "<td><button style='background-color: #1E90FF; color:white;'><a href='product.display.php?id=".$pkey_feature."'>Enabled</a></button></td>";
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