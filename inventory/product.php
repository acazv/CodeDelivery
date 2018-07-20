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
	<a href="product.php"><b>Inventory Simulation</b></a>
</header>
	<ul>
		<li><a class="" href="store.php">Stores</a></li>
		<li><a class="active" href="product.php">Products</a></li>
	</ul>

<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">

<br>

<center>
<label><i class="glyphicon glyphicon-search"></i> Enter Feature or Name</label>
	<form>
		<input id="search" class="form-style-1" type="text" name="search" placeholder="Search..">
	</form>
</center> <br>



<?php
	session_start();
	$_SESSION['search'] = "";

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

 
	$search = (isset($_GET['search']) ? $_GET['search'] : null);
	$display_result = "0";
	
	//select for search
	if (isset($search) && !empty($search))
	{
		$words = explode("+", $search);
		$phrase = implode("%' AND product LIKE '%", $words);
		$query = "SELECT * from product where name like '%$phrase%' ORDER BY name DESC";
		$result = mysqli_query($con, $query);

		if (empty(mysqli_num_rows($result)))
		{
			$words = explode("+", $search);
			$phrase = implode("%' AND product LIKE '%", $words);
			$query = "SELECT * from product where feature like '%$phrase%' ORDER BY name DESC";
			$result = mysqli_query($con, $query) or die('Could not query database at this time');
		}
	} else {
		$sql_distinct_feature = "SELECT * FROM product ORDER BY feature";
		$result = mysqli_query($con, $sql_distinct_feature);
	}
?>



<center>
<div style="height:400px; width:1150px; border:1px solid #ccc;
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
		$feature_distinct = $row_distinct['feature'];

		// Product information
		$sql_check = "SELECT * FROM product WHERE feature = '$feature_distinct'";
		$result_feature_check = mysqli_query($con, $sql_check)or die('Could not query database at this time');
		while($row_check=mysqli_fetch_array($result_feature_check))
		{
			$distinct_name = $row_check['name'];
		}

		//table display
		echo "<tr>
		<td>{$feature_distinct}</td>
		<td>{$distinct_name}</td>
		<td><button><a href='product.display.php?id=".$feature_distinct."'> View </a></button></td>
		<tr>";		
	}			

?>
</table>
	
</div>

</center>


</div>
 
  

</body>
</div>
</html>