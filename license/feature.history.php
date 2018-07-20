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

	$view         = (isset($_GET['id']) ? $_GET['id'] : null);
	$pkey         = (isset($_GET['id1']) ? $_GET['id1'] : null);
	$feature_pkey = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_dnum    = (isset($_GET['id3']) ? $_GET['id3'] : null);

	//side display 	
	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql)or die('Could not query database at this time');

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
		echo "<label><a href='store.php'>Store View</a> > Packages and Features > History</label>";
	}
	if($view == 'product'){
		echo "<label><a href='product.php'>Product View</a> > Packages and Features > History</label>";
	}
?>
	
<ul>
<?php 
	if($view == 'store'){ echo "<br>$name<br><br>Store Id: $snum <br><br>";}

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
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
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


<div style="height:400px;width:1050px; border:1px solid #ccc;
	font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">
<?php	
	//table headings
	echo "<tr>
		<th> Feature </th>
		<th>Deploy</th>
		<th>Deploy Status</th>
		<th> Feature Status</th>";

	//back button on table depending on where you came from in view will send you back to store or product
	if($view == 'store')
	{
		echo "<th><button style='background-color: #708090; color:white;'><a href='store.display.php?id=".$view."&id1=".$pkey."'>Back</a></button></th></tr> ";
	}
	if($view == 'product')
	{
		echo "<th><button style='background-color: #708090; color:white;'><a href='product.display.php?id=".$view."&id1=".$feature_pkey."'>Back</a></button></th></tr> ";
	}

	//select for display
	$sql_history = "SELECT * FROM store_product WHERE snum = '$snum'	AND feature = '$feature_pkey' AND dnum != '$pkey_dnum' ORDER BY dnum DESC";
	$result_history = mysqli_query($con, $sql_history);
	if (mysqli_num_rows($result_history))
	{
		while($row_history=mysqli_fetch_array($result_history))
		{

			$feature_history = $row_history['feature'];
			$status_history = $row_history['status'];
			$dnum_history = $row_history['dnum'];

			$sql_history_dstatus = "SELECT * FROM store_delivery WHERE snum = '$snum' AND dnum = '$dnum_history'";
			$result_history_dstatus = mysqli_query($con, $sql_history_dstatus);
			while($row_history_dstatus=mysqli_fetch_array($result_history_dstatus))
			{
				$history_dstatus = $row_history_dstatus['dstatus'];
				$projectnum_history_delivery = $row_history_dstatus['projectnum'];
				$casenum_history_delivery = $row_history_dstatus['casenum'];
				$contractnum_history_delivery = $row_history_dstatus['contractnum'];

				$sql_history_dstatus_name = "SELECT * FROM deploy_status WHERE status = '$history_dstatus'";
				$result_history_dstatus_name = mysqli_query($con, $sql_history_dstatus_name);
				while($row_history_dstatus_name=mysqli_fetch_array($result_history_dstatus_name))
				{
					$featuredep_history_name = $row_history_dstatus_name['name'];
					$featuredep_history_code = $row_history_dstatus_name['code'];
				}

				//table display
				echo "<tr><td>{$feature_history}</td>
					<td>{$dnum_history}</td>
					<td id='space'>{$featuredep_history_name}</td>
					";

				//different color switches for buttons
				
				//enable and disable buttons
				
				$color = '';
				if($status_history == '1')
				{
					// enable button
					echo "<td><button style='background-color: #1E90FF; color:white;'>Enabled</button>&nbsp;&nbsp;";	
				}else {
					// disable button
					echo "<td><button>Disabled</button>&nbsp;&nbsp;";
				}

				// logon buttons

				$color = '';

				if(is_numeric(stripos($featuredep_history_code,"A")))
				{
					if(is_numeric(stripos($featuredep_history_code,"F")))
					{
						$color = '#87CEFA; color:white;';
					} else {
						$color = '#87CEFA; color:white;';
					}
				}

				if(is_numeric(stripos($featuredep_history_code,"C")))
				{
					$color = '#1E90FF; color:white;';
				}

				//button
				echo"<button style='background-color: $color '><a href='feature.history.logon.php?id=".$view."&id1=".$pkey."&id2=".$feature_history."&id3=".$dnum_history."&id4=".$status_history."&id5=".$pkey_dnum."'>Logons</a></button>&nbsp;";


				$select_dnum         = $dnum_history;
				$select_featuredep   = '';

				//dependency button 

				$color = '';

				require 'productdep.select.status.php';

				if (empty($select_productdep_cnt))
				{
					echo"<button>Dependency</button>";
				} else {
					if(is_numeric(stripos($featuredep_history_code,"A")))
					{
						if(is_numeric(stripos($featuredep_history_code,"F")))
						{
							$color = '#87CEFA; color:white;';
						}else {
							$color = '#87CEFA; color:white;';
						}
					}

					if(is_numeric(stripos($featuredep_history_code,"C")))
					{
						$color = '#1E90FF; color:white;';
					}
					
					//button
					echo"<button style='background-color: #1E90FF; color:white;'><a href='feature.history.dependency.php?id=".$view."&id1=".$pkey."&id2=".$feature_history."&id3=".$dnum_history."&id4=".$status_history."&id5=".$pkey_dnum."'>Dependency</a></button>&nbsp;";
				}

				// delivery button

				$color = '';
				if(is_numeric(stripos($featuredep_history_code,"A")))
				{
					if(is_numeric(stripos($featuredep_history_code,"F")))
					{
						$color = '#FF0000; color:white;';
					} else {
						$color = '#87CEFA; color:white;';
					}
				}
         
				if(is_numeric(stripos($featuredep_history_code,"C")))
				{
					$color = '#1E90FF; color:white;';
				}
				//button
				echo "<button style='background-color: #1E90FF; color:white;'><a href='producthistory.php?id=".$view."&id1=".$pkey."&id2=".$dnum_history."&id3=".$feature_pkey."&id4=".$pkey_dnum."'>Delivery</a></button>&nbsp;&nbsp;";

				//Logs	
				$color = '';

				$path = "../logs/$dnum_history/";
				$myDirectory=is_dir($path);		
				
				//button
				if($myDirectory != '')
				{
					echo"<button style='background-color: #1E90FF; color:white;'><a href='feature.history.log.php?id=".$view."&id1=".$dnum_history."&id2=".$pkey."&id3=".$feature_history."&id4=".$pkey_dnum."'>Logs</a></button></td>";
				} else {							
					echo"<button><a href=''>Logs</a></button></td>";
				}
               
               echo "<td></td></tr>";
            }
		}
	}
?>

</table>
</div>
</div>

</body>
</div>
</html>