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
	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$view			 = $_GET['id'];
	$pkey      		 = $_GET['id1'];
	$feature_pkey    = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_dnum       = (isset($_GET['id3']) ? $_GET['id3'] : null);
	$pkey_featuredep = (isset($_GET['id4']) ? $_GET['id4'] : null);
	$pkey_dstatus    = (isset($_GET['id5']) ? $_GET['id5'] : null);
   
	//side display		
	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql)or die('Could not query database at this time');
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

	// set Navbar Delivery Status
	if ($view == 'store')
	{
		$navbar_snum      = $pkey;
	} else {
		$navbar_feature   = $feature_pkey;
	}
	
	$navbar_status = "navbar.".$view.".status.php";
	require $navbar_status;
?>
<header>
	<a href="../home.php"><b>License Simulation</b></a>
</header>

<?php 
// The user can get to this page either from the Store View or the Product view pages
// Depending on where the user is coming from is what will be displayed here on the "where you are" feature
// $view is the key in telling the program where the user is coming from and what should be displayed
	if($view == 'store'){
		echo "<label><a href='store.php'>Store View</a> > Packages and Features > Dependency > Logons</label>";
	}
	if($view == 'product'){
		echo "<label><a href='product.php'>Product View</a> > Packages and Features > Dependency > Logons</label>";
	}
		
?>
	
<ul>
<?php 
	if($view == 'store'){echo "<br>$name<br><br>Store Id: $snum <br><br>";}
	
	echo "<li>";
	
	//the left navigation bar also changes depending on the 'view' of the user
	//this section of code displays the correct links and variables according to the users view	

	if($view == 'store')
	{
		echo "<a class='active' href='store.display.php?id=".$view."&id1=".$pkey."'>Packages and Features</a> ";
	}
	if($view == 'product')
	{
		echo "<a class='active' href='product.display.php?id=".$view."&id1=".$feature_pkey."'>Packages and Features</a> ";
	}

	echo "</li>";
	if (empty($navbar_delivery_cstatus))
	{
		echo "<li><a href='' >Delivery</a> </li>";
	} else {
		if($navbar_delivery_rstatus == '0')
		{
			if($view == 'store')
			{
				echo "<li style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
			}
			
			if($view == 'product')
			{
				echo "<li style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$feature_pkey."' >Delivery</a></li>";
			}
		} else {
			if($view == 'store')
			{
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id=".$pkey."' >Delivery</a></li>";
			}
			
			if($view == 'product')
			{
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=&id2=&id3==".$feature_pkey."' >Delivery</a></li>";
			}
		}
	}
?>
</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">


<?php

	//label select
	$sql_second_repo = "SELECT * FROM product WHERE feature = '$pkey_featuredep'";
	$result_second_repo = mysqli_query($con, $sql_second_repo)or die('Could not query database at this time');
	while($row_second_repo=mysqli_fetch_array($result_second_repo))
	{
		$id_second_display = $row_second_repo['id'];
		$feature_second_display = $row_second_repo['feature'];
		$repo_second_display = $row_second_repo['repo'];
		$name_second_display = $row_second_repo['name'];

		$sql_first_repo = "SELECT * FROM product WHERE feature = '$feature_pkey'";
		$result_first_repo = mysqli_query($con, $sql_first_repo)or die('Could not query database at this time');
		while($row_first_repo=mysqli_fetch_array($result_first_repo))
		{
			$repo_first_display = $row_first_repo['repo'];
			$name_first_display = $row_first_repo['name'];
		}

			//label 
			echo "<label>Feature:</label> $feature_pkey &nbsp;
			<label>Name:</label> $name_first_display 
			<label>Deploy#:</label> $pkey_dnum 
			<h5><b>Dependency</b></h5>
			<label> Feature:</label> $feature_second_display 
			<label>Name:</label> $name_second_display
			<label> Deploy#:</label> $pkey_dnum<br><br>";
	}
?>
<div style="height:400px;width:1050px; border:1px solid #ccc;
	font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
	
<table class="table ">
<?php
	//table heading
	echo "<tr>
	<th> Logon </th>
	<th> Name </th>
	<th> Deploy Status </th>
	<th><button style='background-color: #708090; color:white;'><a href='feature.dependency.php?id=".$view."&id1=".$pkey."&id2=".$feature_pkey."&id3=".$pkey_dnum."&id4=".$pkey_dstatus."'>Back</a></button></th>
	</tr>";

	//display all logons for selected feature dependency
	$sql_logon = "SELECT * FROM store_productdep_logon WHERE dnum = '$pkey_dnum' AND feature = '$feature_pkey' AND featuredep = '$pkey_featuredep' ORDER BY dnum DESC";
	$result_repo_logon = mysqli_query($con, $sql_logon)or die('Could not query database at this time');
	while($row_logon=mysqli_fetch_array($result_repo_logon))
	{
		$feature      = $row_logon['feature'];
		$featuredep   = $row_logon['featuredep'];
		$logon        = $row_logon['logon'];
		$status_logon = $row_logon['status'];
		$dnum_select  = $row_logon['dnum'];

		// Extentions of Logon
		$exts= explode("-", $logon);
		$acccode= $exts[1];

		$sql_logon_name = "SELECT * FROM acc_code WHERE appcode = '$acccode'";
		$result_logon_name = mysqli_query($con, $sql_logon_name)or die('Could not HERE query database at this time');
		while($row_logon_name=mysqli_fetch_array($result_logon_name))
		{
			$logon_name = $row_logon_name['name'];
		}               

		//status name
		$sql_dnum_name = "SELECT * FROM store_delivery WHERE dnum = '$dnum_select'";
		$result_dnum_name = mysqli_query($con, $sql_dnum_name);
		while($row_dnum_name=mysqli_fetch_array($result_dnum_name))
		{
			$dstatus_name = $row_dnum_name['dstatus'];
		}

		$sql_status_name = "SELECT * FROM deploy_status WHERE status = '$dstatus_name'";
		$result_status_name = mysqli_query($con, $sql_status_name);
		while($row_status_name=mysqli_fetch_array($result_status_name))
		{
			$status_name = $row_status_name['name'];
			$status_code = $row_status_name['code'];
		}

		//table display

		echo "<tr><td>{$logon}</td>
		<td>{$logon_name}</td>
		<td>{$status_name}</td>";

		//different color switches for buttons
		if ($status_logon)
		{
			if(is_numeric(stripos($status_code,"N")))
			{
				echo"<td><button style='background-color: #228B22; color:white;'><a href='productupdate.php?id=".$view."&id1=".$pkey."&id2=".$feature_pkey."&id3=dependencylogon&id4=".$logon."&id5=".$pkey_dnum."&id6=".$feature_second_display."&id7=".$status_logon."&id8=".$pkey_dstatus."'>Enabled</a></button></td>";
			}

			if(is_numeric(stripos($status_code,"A")))
			{
				echo"<td><button style='background-color: #87CEFA; color:white;'>Enabled</button></td>";
			}

			if(is_numeric(stripos($status_code,"C")))
			{
				echo"<td><button style='background-color: #1E90FF; color:white;'>Enabled</button></td>";
			}
		} else {
			if(is_numeric(stripos($status_code,"N")))
			{
				echo"<td><button><a href='productupdate.php?id=".$view."&id1=".$pkey."&id2=".$feature_pkey."&id3=dependencylogon&id4=".$logon."&id5=".$pkey_dnum."&id6=".$feature_second_display."&id7=".$status_logon."&id8=".$pkey_dstatus." '>Disabled</a></button></td>";
			}  else {
				echo"<td><button>Disabled</button></td>";
			}
		}
	}
	
	echo "</tr>";
?>
</table>

</div>
</div>

</body>
</div>
</html>