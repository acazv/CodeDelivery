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

	$view		  = $_GET['id'];
	$pkey		  = $_GET['id1'];
	$feature_pkey = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_dnum    = (isset($_GET['id3']) ? $_GET['id3'] : null);
	$pkey_dstatus = (isset($_GET['id4']) ? $_GET['id4'] : null);

	//side display
	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql)or die('Could not query database at this time');	
	if (!empty(mysqli_num_rows($result))) {
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
	if($view == 'store')
	{
		echo "<label><a href='store.php'>Store View</a> > Packages and Features > Dependency</label>";
	}
	if($view == 'product')
	{
		echo "<label><a href='product.php'>Product View</a> > Packages and Features > Dependency</label>";
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
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$feature_pkey."' >Delivery</a></li>";
			}
		}
	}
?>
</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">


<?php
	//select for label display
	$sql_second_repo = "SELECT * FROM product WHERE feature = '$feature_pkey'";
	$result_second_repo = mysqli_query($con, $sql_second_repo)or die('Could not query database at this time');
	while($row_second_repo=mysqli_fetch_array($result_second_repo))
	{
		$id_second_display = $row_second_repo['id'];
		$feature_second_display = $row_second_repo['feature'];
		$repo_second_display = $row_second_repo['repo'];
		$name_second_display = $row_second_repo['name'];

		//label
		echo "<label>Feature:</label> $feature_pkey &nbsp;
		<label>Name:</label> $name_second_display 
		<label>Deploy#:</label> $pkey_dnum 
		<h5><b>Dependency</a></h5>
		";
	}
?>
<div style="height:400px;width:1050px; border:1px solid #ccc;
	font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">
<?php
	//table headings
	echo "<tr>
	<th></th>
	<th> Feature </th>
	<th> Name</th>
	<th> Deploy Status</th>";
	
	//back button on table depending on where you came from in view will send you back to store or product
	if($view == 'store')
	{
		echo"<th><button style='background-color: #708090; color:white;'><a href='store.display.php?id=".$view."&id1=".$pkey."'>Back</a></button></th></tr>";
	}
	if($view == 'product')
	{
		echo"<th><button style='background-color: #708090; color:white;'><a href='product.display.php?id=".$view."&id1=".$feature_pkey."'>Back</a></button></th></tr>";
	}

	//select for table display
	$sql_dependency = "SELECT * FROM store_productdep WHERE dnum = '$pkey_dnum' AND feature = '$feature_pkey'";
	$result_dependency = mysqli_query($con, $sql_dependency)or die('Could not query database at this time');
	while($row_dependency=mysqli_fetch_array($result_dependency))
	{
		$feature = $row_dependency['feature'];
		$featuredep = $row_dependency['featuredep'];

		$sql_featuredep_dstatus = "SELECT * FROM store_delivery WHERE dnum = '$pkey_dnum'";
		$result_featuredep_dstatus = mysqli_query($con, $sql_featuredep_dstatus);
		while($row_featuredep_dstatus=mysqli_fetch_array($result_featuredep_dstatus))
		{
			$featuredep_dstatus = $row_featuredep_dstatus['dstatus'];

			$sql_featuredep_dstatus_name = "SELECT * FROM deploy_status WHERE status = '$featuredep_dstatus'";
			$result_featuredep_dstatus_name = mysqli_query($con, $sql_featuredep_dstatus_name);
			while($row_featuredep_dstatus_name=mysqli_fetch_array($result_featuredep_dstatus_name))
			{
				$featuredep_dstatus_name = $row_featuredep_dstatus_name['name'];
				$featuredep_dstatus_code = $row_featuredep_dstatus_name['code'];
			}
		}            

		$sql_featurename = "SELECT * FROM product WHERE feature = '$featuredep'";
		$result_featurename = mysqli_query($con, $sql_featurename)or die('Could not query database at this time');					
		while($row_featurename=mysqli_fetch_array($result_featurename))
		{
			$feature_name = $row_featurename['name'];
			
			$select_dnum         = $pkey_dnum;
			$select_featuredep   = $featuredep;

			require 'productdep.select.status.php';

			//table display

			echo "<tr><td></td><td>{$featuredep} </td>
				 <td>{$feature_name}</td>
				 <td>{$featuredep_dstatus_name}</td>";

			//different color switches for buttons
			if(is_numeric(stripos($featuredep_dstatus_code,"N")))
			{
				if($select_productdep_logon_status == '0')
				{
					$color = '#FFFF99';
				} else {
					$color = '#228B22; color:white;';
				}  
			}
			if(is_numeric(stripos($featuredep_dstatus_code,"A")))
			{
				$color = '#87CEFA; color:white;';
			}
			if(is_numeric(stripos($featuredep_dstatus_code,"C")))
			{
				$color = '#1E90FF; color:white;';
			}
			
			//button
			echo"<td><button style='background-color: $color'><a href='feature.dependency.logon.php?id=".$view."&id1=".$pkey."&id2=".$feature."&id3=".$pkey_dnum."&id4=".$featuredep."&id5=".$pkey_dstatus."'>Logon</a></button>&nbsp;</td>";    
			echo "</tr>";
		}
	}
?>
</table>

</div>
</div>

</body>
</div>
</html>