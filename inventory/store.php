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
	<a href="store.php"><b>Inventory Simulation</b></a>
</header>
	<ul>
		<li><a class="active" href="">Stores</a></li>
		<li><a class="" href="product.php">Products</a></li>
	</ul>

<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">

<center>
<label><i class="glyphicon glyphicon-search"></i> Enter C#, or IP, or Name</label>
	<form name="form" action="" method="get">
		<input id="search" class="form-style-1" type="text" name="search" placeholder="Search..">
	</form>
</center> <br>

<?php
 
	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

	$search = (isset($_GET['search']) ? $_GET['search'] : null);

	//select for search
	if (isset($search))
	{
		$words = explode("+", $search);
		$phrase = implode("%' AND acc_store LIKE '%", $words);
		$query = "SELECT * from acc_store where cnum like '%$phrase%' ORDER BY cnum DESC";
		$result = mysqli_query($con, $query) or die('Could not query database at this time');

		if (empty(mysqli_num_rows($result)))
		{
			$words = explode("+", $search);
			$phrase = implode("%' AND acc_store LIKE '%", $words);
			$query = "SELECT * from acc_store where ip like '%$phrase%' ORDER BY cnum DESC";
			$result = mysqli_query($con, $query) or die('Could not query database at this time');

			if (empty(mysqli_num_rows($result)))
			{
				$words = explode("+", $search);
				$phrase = implode("%' AND acc_store LIKE '%", $words);
				$query = "SELECT * from acc_store where name like '%$phrase%' ORDER BY cnum DESC";
				$result = mysqli_query($con, $query) or die('Could not query database at this time');
			}
		}
	} else {
		$query = "SELECT * from acc_store ORDER BY cnum DESC";
		$result = mysqli_query($con, $query) or die('Could not query database at this time');
	}
?>

<center>
<div style="height:400px; width:1100px; border:1px solid #ccc;
	font:16px/26px Georgia, Garamond, Serif; overflow:auto; text-overflow: ellipsis;">
<table class="table ">
	<tr>
		<th> Store# </th>
		<th> C# </th>
		<th> Name </th>
		<th> IP </th>
		<th> Address </th>
		<th> Action </th>
	</tr>

<?php
	if (isset($result))
	{
		while($row=mysqli_fetch_array($result))
		{
			$snum = $row['snum'];
			$name = $row['name'];
			$cnum = $row['cnum'];
			$ip = $row['ip'];
			$address = $row['address'];

			//table display
			echo "<tr>
			<td>{$snum}</td>
			<td>{$cnum}</td>
			<td id='space'>{$name}</td>
			<td>{$ip}</td>
			<td id='space'>{$address}</td>
			<td><button><a href='storeproduct.php?id=".$snum."'>View</a></button></td>
			<tr>";	
		}	
	}
?>
</table>
			
</div>

</center>

</div>
</body>
</div>
</html>