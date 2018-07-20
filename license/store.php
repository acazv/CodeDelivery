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

	<label> License > <a href="store.php">Store View</a> </label>

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
	$search         = (isset($_GET['search']) ? $_GET['search'] : null);
	$display_result = "0";

	if (isset($search))
	{
		$words = explode("+", $search);
		$phrase = implode("%' AND acc_store LIKE '%", $words);
		$query = "SELECT * from acc_store where cnum like '%$phrase%' ORDER BY cnum DESC";
		$result = mysqli_query($con, $query);

		if (empty(mysqli_num_rows($result)))
			{
				$words = explode("+", $search);
				$phrase = implode("%' AND acc_store LIKE '%", $words);
				$query = "SELECT * from acc_store where ip like '%$phrase%' ORDER BY cnum DESC";
				$result = mysqli_query($con, $query);
			
			if (empty(mysqli_num_rows($result)))
				{
					$words = explode("+", $search);
					$phrase = implode("%' AND acc_store LIKE '%", $words);
					$query = "SELECT * from acc_store where name like '%$phrase%' ORDER BY cnum DESC";
					$result = mysqli_query($con, $query);
				}
			}
	} else {
		$query = "SELECT * from store";
		$result = mysqli_query($con, $query);
	}
?>

<center>
<div style="height:400px;width:1150px; border:1px solid #ccc;
	font:16px/26px Georgia, Garamond, Serif; overflow:auto;">
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

	while($row=mysqli_fetch_array($result))
	{
		$acc_snum = $row['snum'];

		$query = "SELECT * from store WHERE snum = '$acc_snum'";
		$result_invetory = mysqli_query($con, $query);
		while($row_inventory=mysqli_fetch_array($result_invetory))
		{
			$inventory_snum = $row_inventory['snum'];
			
			$query = "SELECT * from acc_store WHERE snum = '$inventory_snum'";
			$result_acc = mysqli_query($con, $query);
			while($row_acc=mysqli_fetch_array($result_acc))
			{
				$snum = $row_acc['snum'];
				$name = $row_acc['name'];
				$cnum = $row_acc['cnum'];
				$ip = $row_acc['ip'];
				$address = $row_acc['address'];
				
				// set Navbar Delivery Status
				$navbar_snum 	= $snum;
				$navbar_status = "navbar.store.status.php";
				require $navbar_status;

				//table display
				echo "<tr>
						<td>h{$inventory_snum}</td>
						<td>{$cnum}</td>
						<td id='space'>{$name}</td><td>{$ip}</td>
						<td id='space'>{$address}</td>";
				if (empty($navbar_delivery_cstatus))
				{
					echo "<td><button><a href='store.display.php?id=store&id1=".$snum."'>View</a></button></td><tr>";	
				} else {
					if(empty($navbar_delivery_rstatus))
					{
						$color = '#FFFF99'; 
					} else {
						$color = '#228B22; color:white;';					
					}
					//button
					echo "<td><button style='background-color: $color '><a href='store.display.php?id=store&id1=".$snum."'>View</a></button></td>";
				}
			}
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