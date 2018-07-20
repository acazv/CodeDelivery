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
	mysqli_select_db($con, "codedevlivery" ) or die(mysqli_error());

	$pkey =$_GET['id'];

	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql);	
	if (!empty(mysqli_num_rows($result)))
	{
		while($row=mysqli_fetch_array($result))
		{
				$id = $row['id'];
				$snum = $row['snum'];
				$name = $row['name'];
				$cnum = $row['cnum'];
				$ip = $row['ip'];
				$address = $row['address'];
		}
	}
?>
<header>
<a href="store.php"><b>Inventory Simulation</b></a>
</header>

<?php echo "<label><a href='store.php'>Stores</a> > Departments </label>";?>
	
<ul>
	<?php echo "<br>$name <br><br>Store Id: $snum";?> <br><br>
	<li><?php echo "<a class='active' href='department.php?id=".$pkey."' >Departments</a> "?></li>
	<li><?php echo "<a href='storeproduct.php?id=".$pkey."'>Packages and Features</a> "?></li>
</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px; ">

<br>	

<div style="height:400px;width:900px; border:1px solid #ccc;
			font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">
	
	<tr>
		<th> Logon  </th>
		<th> Name  </th>
		<th></th>
	</tr>

<?php
	//select for logon				
	$sql_logon = "SELECT * FROM acc_store_logon WHERE snum = '$pkey'";
	$result_logon = mysqli_query($con, $sql_logon);
	while($row_logon=mysqli_fetch_array($result_logon))
	{
		$logon = $row_logon['logon'];

		// Extentions of Logon
		$exts= explode("-", $logon);
		$acccode= $exts[1];


		$sql_logon_name = "SELECT * FROM acc_acccode WHERE appcode = '$acccode'";
		$result_logon_name = mysqli_query($con, $sql_logon_name)
		or die('Could not HERE query database at this time');
		while($row_logon_name=mysqli_fetch_array($result_logon_name))
		{
			$logon_name = $row_logon_name['name'];
		}

		//table display
		echo "<tr><td>{$logon}</td><td>{$logon_name}</td>
		<td></td>
		<td></td>
		<td></td>
		</tr>";
	}	
?>
</table>

</div>
</div>

</body>
</div>
</html>