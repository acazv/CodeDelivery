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
<header>
	<a href="../home.php"><b>License Simulation</b></a>
</header>
<label> License > <a href="product.php">Product View</a> </label>


<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">

<br>

<center>
	<label><i class="glyphicon glyphicon-search"></i> Enter C#, or IP, or Name</label>
		<form>
			<input id="search" class="form-style-1" type="text" name="search" placeholder="Search..">
		</form>
</center> <br>



<?php
	session_start();

	$_SESSION['cnum_search'] = "";

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

	//search code
	$search = (isset($_GET['search']) ? $_GET['search'] : null);
	$display_result = "0";

	if (isset($search) && !empty($search))
	{
		$words = explode("+", $search);
		$phrase = implode("%' AND product LIKE '%", $words);
		$query = "SELECT * from product where name like '%$phrase%' ORDER BY name";
		$result = mysqli_query($con, $query);
	} else {
		$sql_distinct_feature = "SELECT * FROM product ORDER BY name";
		$result = mysqli_query($con, $sql_distinct_feature);
	}
?>



<center>
<div style="height:400px;width:1150px; border:1px solid #ccc;
	font:16px/26px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">
	<tr>
		<th> Feature </th>
		<th> Feature Name </th>
		<th> Action </th>
	</tr>
	
<?php
	while($row_distinct=mysqli_fetch_array($result))
	{
		$feature_distinct    = $row_distinct['feature'];
		$result_feature_cnt  = "0";

		// Product information
		$sql_check = "SELECT * FROM product WHERE feature = '$feature_distinct'";
		$result_feature_check = mysqli_query($con, $sql_check)or die('Could not query database at this time');
		while($row_check=mysqli_fetch_array($result_feature_check))
		{
			$distinct_name = $row_check['name'];

			//table display
			echo "<tr><td><a href='product.display.php?id=product&id1=".$feature_distinct."'>{$feature_distinct}</a></td><td>{$distinct_name}</td>";

			$sql_check_distinct = "SELECT * FROM store_product WHERE feature = '$feature_distinct' ORDER BY dnum DESC LIMIT 1";
			$result_feature_check_distinct = mysqli_query($con, $sql_check_distinct);
			$result_feature_cnt =  mysqli_num_rows($result_feature_check_distinct);
		}

		if($result_feature_cnt)
		{
			// set product.view Navbar Delivery Status
			$navbar_feature   = $feature_distinct;
			$navbar_status    = "navbar.product.status.php";
			require $navbar_status;

			//different color switches for buttons
			if (empty($navbar_delivery_cstatus))
			{
				echo "<td><button style='background-color: '><a href='product.display.php?id=product&id1=".$feature_distinct."'>View</a></button></td>";
			} else {
				if(empty($navbar_delivery_rstatus))
				{
					$color = '#FFFF99';
				} else {
					$color = '#228B22; color:white;';
				}
				//button
				echo "<td><button style='background-color: $color '><a href='product.display.php?id=product&id1=".$feature_distinct."'>View</a></button></td>";
			}

		} else {
			echo "<td></td>";
		}
		
		echo "<tr>";		
	}			
?>
</table>
	
</div>

</center>

</div>
</body>
</div>
</html>