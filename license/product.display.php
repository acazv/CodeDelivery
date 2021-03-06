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

	$con =  mysqli_connect('127.0.0.1', 'root', '')or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$view = $_GET['id'];
	$pkey_feature = $_GET['id1'];

	if(isset($_POST['cnum_search']))
	{
		$cnum_search = $_POST['cnum_search'];
		$_SESSION['cnum_search'] = $cnum_search;
	}

	$cnum_search = $_SESSION['cnum_search'];

	// set product.view Navbar Delivery Status
	$navbar_feature   = $pkey_feature;
	$navbar_status = "navbar.".$view.".status.php";
	require $navbar_status;

	$sql_delivery = "SELECT * FROM delivery";
	$result_delivery = mysqli_query($con, $sql_delivery);
?>

<header>
	<a href="../home.php"><b>License Simulation</b></a>
</header>

<?php echo "<label><a href='product.php'>Product View</a> > Packages and Features</label>";?>
<ul>
	
<?php 

	// The user can get to this page either from the Store View or the Product view pages
	// Depending on where the user is coming from is what will be displayed here on the side menu
	// $view is the key in telling the program where the user is coming from and what should be displayed
	
	echo "<li><a class='active' href='product.display.php?id=".$view."&id1=".$pkey_feature."'>Packages and Features</a> </li>";
	if (empty($navbar_delivery_cstatus))
	{
		echo "<li><a href='' >Delivery</a> </li>";
	} else  {
		if(empty($navbar_delivery_rstatus))
		{
			echo "<li style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
		} else {
			echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
		}
	}
?>
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

<form <?php echo "action='product.display.php?id=".$view."&id1=".$pkey_feature."'";?> method="POST">
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
		<th> C# </th>
		<th> Store# </th>
		<th> Name </th>
		<th> Deploy# </th>
		<th> Deploy Status </th>
		<th> Feature Status </th>
		</tr>";

	//  Product Information
	$sql_store = "SELECT * FROM store";
	$result_store = mysqli_query($con, $sql_store)or die('Could not query database at this time');
	while($row_store=mysqli_fetch_array($result_store))
	{
		$snum_distinct = $row_store['snum'];

		if(empty($cnum_search))
		{
			$sql_check = "SELECT * FROM acc_store WHERE snum = '$snum_distinct'";
			$result_store_check = mysqli_query($con, $sql_check);
		} else {
			$words = explode("+", $cnum_search);
			$phrase = implode("%' AND acc_store LIKE '%", $words);
			$sql_check = "SELECT * from acc_store where snum = '$snum_distinct' and cnum like '%$phrase%'";
			$result_store_check = mysqli_query($con, $sql_check);

			if (empty(mysqli_num_rows($result_store_check)))
			{
				$words = explode("+", $cnum_search);
				$phrase = implode("%' AND acc_store LIKE '%", $words);
				$sql_check = "SELECT * from acc_store where snum = '$snum_distinct' and ip like '%$phrase%'";
				$result_store_check = mysqli_query($con, $sql_check);

				if (empty(mysqli_num_rows($result_store_check)))
				{
					$words = explode("+", $cnum_search);
					$phrase = implode("%' AND acc_store LIKE '%", $words);
					$sql_check = "SELECT * from acc_store where snum = '$snum_distinct' and name like '%$phrase%'";
					$result_store_check = mysqli_query($con, $sql_check);
				}
			}
		}

		while($row_store_check=mysqli_fetch_array($result_store_check))
		{
			$cnum_distinct = $row_store_check['cnum'];
			$snum_distinct = $row_store_check['snum'];
			$store_name    = $row_store_check['name'];

			//  Product Information
			$sql_check_distinct = "SELECT * FROM store_product WHERE snum = '$snum_distinct' and feature = '$pkey_feature' ORDER BY snum, dnum DESC LIMIT 1";
			$result_feature_check_distinct = mysqli_query($con, $sql_check_distinct)or die('Could not query database at this time');
			while($row_check_distinct=mysqli_fetch_array($result_feature_check_distinct))
			{
				$status_distinct = $row_check_distinct['status'];
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
					$dstatus_name = $row_dstatus_name['name'];
					$dstatus_code = $row_dstatus_name['code'];
				}

				//display if enabled
				echo "<tr>
					<td> {$cnum_distinct} </td>
					<td>{$snum_distinct}</td>
					<td id='space'>{$store_name}</td>
					<td>{$dnum_distinct}</td>
					<td>{$dstatus_name}";
					
				//different color switches for buttons
				
				$color = ''; //default
				
				if($status_distinct)
				{// enable button
					if(is_numeric(stripos($dstatus_code,"C")))
					{
						$color = '#1E90FF; color:white;';	
					} else {
						$color = '#87CEFA; color:white;';
					}
					//button
					echo "<td><button style='background-color: $color'>Enabled</button>&nbsp;&nbsp;";
					
				} else {// disable button
					if(is_numeric(stripos($dstatus_code,"C")))
					{
						$color = '';
					} else {
						$color = '#87CEFA; color:white';
					}
					//button
					echo "<td><button style='background-color: $color '>Disabled</button>&nbsp;&nbsp;";
				}

				// Logon button color
				
				$color = ''; //default
				$select_dnum = $dnum_distinct;

				require 'product.select.status.php';

				if(is_numeric(stripos($dstatus_code,"N")))
				{
					if($select_product_logon_status == '0')
					{
						$color = '#FFFF99';
					} else {
						$color = '#228B22; color:white;';
					}
					
				}

				if(is_numeric(stripos($dstatus_code,"A")))
				{
					if(is_numeric(stripos($dstatus_code,"F")))
					{
						$color = '#87CEFA; color:white;';
					} else {
						//option to change color
						$color = '#87CEFA; color:white;';
					}
				}
				
				if(is_numeric(stripos($dstatus_code,"C")))
				{
					$color = '#1E90FF; color:white;';
				}
				//button 
				echo"<button style='background-color: $color ;'><a href='feature.logon.php?id=".$view."&id1=".$snum_distinct."&id2=".$pkey_feature."&id3=".$dnum_distinct."&id4=".$dstatus_code."'>Logons</a></button>&nbsp;";

				// Dependency button color
				
				$select_dnum         = $dnum_distinct;
				$select_featuredep   = '';

				require 'productdep.select.status.php';
				
				$color = ''; //default

				if (empty($select_productdep_cnt))
				{
					echo"<button>Dependency</button>";
				} else {
					if(is_numeric(stripos($dstatus_code,"N")))
					{
						if($select_productdep_logon_status == '0')
						{
							$color = '#FFFF99';
						} else {
							
							$color = '#228B22; color:white;';
						}
					}
					if(is_numeric(stripos($dstatus_code,"A")))
					{
						if(is_numeric(stripos($dstatus_code,"F")))
						{
							$color = '#87CEFA; color:white;';
						}else {
							//option to change
							$color = '#87CEFA; color:white;';						
						}
					}

					if(is_numeric(stripos($dstatus_code,"C")))
					{
						$color = '#1E90FF; color:white;';
					}
					//button
					echo"<button style='background-color: $color '><a href='feature.dependency.php?id=".$view."&id1=".$snum_distinct."&id2=".$pkey_feature."&id3=".$dnum_distinct."&id4=".$dstatus_code."'>Dependency</a></button>&nbsp;";
				}

				// Delivery button color
				$select_dnum = $dnum_distinct;

				require 'delivery.required.status.php';
				
				$color = ''; //default

				if(is_numeric(stripos($dstatus_code,"N")))
				{
					if($select_delivery_rstatus == '0')
					{
						$color = '#FFFF99';					
					} else {
						$color = '#228B22; color:white';
					}
				}

				if(is_numeric(stripos($dstatus_code,"A")))
				{
					if(is_numeric(stripos($dstatus_code,"F")))
					{
						$color = '#FF0000; color:white';
					} else {
						$color = '#87CEFA; color:white';
					}
				}
				   
				if(is_numeric(stripos($dstatus_code,"C")))
				{
					$color = '#1E90FF; color:white';
				}
				//button	
				echo "<button style='background-color: $color '><a href='contractedit.php?id=".$view."&id1=".$snum_distinct."&id2=".$dnum_distinct."&id3=".$pkey_feature."'>Delivery</a></button>&nbsp;&nbsp;";
				
				//History button color
				
				$color = ''; //default

				// History botton records
				$sql_feature_color = "SELECT * FROM store_product WHERE snum = '$snum_distinct' AND feature = '$pkey_feature' AND dnum != '$dnum_distinct'";
				$result_feature_color = mysqli_query($con, $sql_feature_color)or die('Could not query database at this time');

				if (mysqli_num_rows($result_feature_color))
				{
					//button
					echo"<button style='background-color: #1E90FF; color:white;'><a href='feature.history.php?id=".$view."&id1=".$snum_distinct."&id2=".$pkey_feature."&id3=".$dnum_distinct."'>History</a></button>";
				} else {
					//button
					echo"<button>History</button>";
				}

				//Logs	
				$color = ''; //default
				
				$path = "../logs/$dnum_distinct/";
				$myDirectory=is_dir($path);					
				if($myDirectory != '')
				{
					if(is_numeric(stripos($dstatus_code,"F")))
					{
						$color = '#FF0000; color:white;';
					} else{
						$color = '#1E90FF; color:white;';
					}
					//button
					echo"<button style='background-color: $color '><a href='feature.log.php?id=".$view."&id1=".$dnum_distinct."&id2=".$snum_distinct."&id3=".$pkey_feature."'>Logs</a></button></td>";
				} else {	
					//button
					echo"<button><a href=''>Logs</a></button></td>";
				}
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