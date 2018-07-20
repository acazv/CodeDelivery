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
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$view              = $_GET['id'];
	$pkey              = $_GET['id1'];
	$pkey_dnum         = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_feature      = (isset($_GET['id3']) ? $_GET['id3'] : null);
	$pkey_history_dnum = (isset($_GET['id4']) ? $_GET['id4'] : null);

	// side display
	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql);	
	while($row=mysqli_fetch_array($result))
	{
		$id = $row['id'];
		$snum = $row['snum'];
		$name = $row['name'];
		$cnum = $row['cnum'];
		$ip = $row['ip'];
		$address = $row['address'];
	}

	//query of selected delivery		
	if(isset($pkey_dnum))
	{
		$sql_display = "SELECT * FROM store_delivery WHERE dnum = '$pkey_dnum'";
		$result_display = mysqli_query($con, $sql_display);	
		while($row_display=mysqli_fetch_array($result_display))
		{
			$projectnum_display           = $row_display['projectnum'];
			$casenum_display              = $row_display['casenum'];
			$contractnum_display          = $row_display['contractnum'];
			$requestdate_display          = $row_display['requestdate'];
			$requesttime_display          = $row_display['requesttime'];
			$requestdeliverydate_display  = $row_display['requestdeliverydate'];
			$requestdeliverytime_display  = $row_display['requestdeliverytime'];
			$requestinstalldate_display   = $row_display['requestinstalldate'];
			$requestinstalltime_display   = $row_display['requestinstalltime'];
			$installtimezone_display      = $row_display['installtimezone'];
			$demo_display                 = $row_display['demo'];
			$demodays_display             = $row_display['demodays'];
			$expirationdate_display       = $row_display['expirationdate'];
			$projectname_display          = $row_display['projectname'];
			$pilot_display                = $row_display['pilot'];
		}
	}
		
	// set Navbar Delivery Status
	if ($view == 'store')
	{
		$navbar_snum      = $pkey;
	} else {
		$navbar_feature   = $pkey_feature;
	}
	$navbar_status = "navbar.".$view.".status.php";
	
	require $navbar_status;
?>
<header>
	<a href=".../home.php"><b>License Simulation</b></a>
</header>

<?php 
	// The user can get to this page either from the Store View or the Product view pages
	// Depending on where the user is coming from is what will be displayed here on the "where you are" feature
	// $view is the key in telling the program where the user is coming from and what should be displayed
	
	if($view == 'store'){
		echo "<label><a href='store.php'>Store View</a> > Packages and Features > History > Delivery</label>";
	}	
	if($view == 'product'){
		echo "<label><a href='store.php'>Product View</a> > Packages and Features > History > Delivery</label>";
	}		
?>
<ul>
<?php
	if($view == 'store'){ echo "<br>$name<br><br>Store Id: $snum <br><br>"; } 
	
	echo "<li>";

	//the left navigation bar also changes depending on the 'view' of the user
	//this section of code displays the correct links and variables according to the users view	
		
	if($view == 'store')
	{
		echo "<a href='store.display.php?id=".$view."&id1=".$pkey."'>Packages and Features</a> ";
	}
	if($view == 'product')
	{
		echo "<a href='product.display.php?id=".$view."&id1=".$pkey_feature."'>Packages and Features</a> ";
	}
	echo "</li>";

	if (empty($navbar_delivery_cstatus))
	{
		echo "<li><a class='active' href=''>Delivery</a> </li>";
	} else {
		if($navbar_delivery_rstatus == '0') 
		{
			if($view == 'store')
			{
				echo "<li style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
			}
			if($view == 'product')
			{
				echo "<li style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
			}
		} else {
			if($view == 'store')
			{
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
			}
			if($view == 'product')
			{
				echo "<li style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
			}

		}
	}
?>
</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px;">

<?php	 
	$sql_second_repo = "SELECT * FROM product WHERE feature = '$pkey_feature'";
	$result_second_repo = mysqli_query($con, $sql_second_repo)or die('Could not query database at this time');
	while($row_second_repo=mysqli_fetch_array($result_second_repo))
	{
		$feature_name_display = $row_second_repo['name'];

		//label display
		echo "<label>Feature:</label> $pkey_feature &nbsp;
		<label>Name:</label> $feature_name_display 
		<label>Deploy#:</label> $pkey_dnum ";
	}

	$xrequesttime         = date('h:i A', strtotime($requesttime_display));
	$xrequestdeliverytime = date('h:i A', strtotime($requestdeliverytime_display));

	//label display
	echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label> Inventory Date:</label> $requestdate_display
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label> Inventory Time:</label> $xrequesttime <br>
	<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Delivery Date:</label> $requestdeliverydate_display 
	<label> Delivery Time:</label> $xrequestdeliverytime";
?>
	
<br><br>

	<form <?php echo "action='updatecontract.php?id=".$pkey."&id1=".$pkey_dnum."'";?> method='post'>
	<!-- button display -->
	<div class="col-sm-12">
		<button  style="background-color: #708090; color:white;">
			<a href= "<?php
				if($view == 'store'){
					echo "feature.history.php?id=".$view."&id1=$pkey&id2=$pkey_feature&id3=$pkey_history_dnum"; 
				}
				if($view == 'product'){
					echo "feature.history.php?id=".$view."&id1=$pkey&id2=$pkey_feature&id3=$pkey_history_dnum"; 
				}
				?> ">Cancel</a>
		</button>&nbsp;&nbsp;&nbsp;

		<br><br>
		<div class="form-group col-sm-3 ">
			<label class="control-label" ><i class="glyphicon glyphicon-user"></i> Project Number</label>
		<input  class="form-control" name="projectnum" type="text" value="<?php echo "$projectnum_display"?>" placeholder="Project#" />
		</div>
		
		<div class="form-group col-sm-3">
			<label class="control-label"><i class="glyphicon glyphicon-user"></i>Case Number</label>
		<input class="form-control" name="casenum" type="text" value="<?php echo"$casenum_display"?>" placeholder="Case#"/>
		</div>

		<div class="form-group col-sm-4">
			<label class="control-label"><i class="glyphicon glyphicon-user"></i> Contract Number</label>
		<input class="form-control" name="contractnum" type="text" value="<?php echo"$contractnum_display"?>" placeholder="Contract#" />
		</div> 

		<div class="form-group col-sm-3">
			<label class="control-label"><i class="glyphicon glyphicon-calendar"></i> Install Date</label><span style="color:red">*</span>
		<input required class="form-control" name="requestinstalldate" type="date" value="<?php echo "$requestinstalldate_display"?>" placeholder="mm/dd/yyyy/"/>
		</div>

		<div class="form-group col-sm-3">
			<label class="control-label"><i class="glyphicon glyphicon-time"></i> Install Time</label><span style="color:red">*</span>
		<input required class="form-control" step="1" name="requestinstalltime" type="Time" value="<?php echo "$requestinstalltime_display";?>" />
		</div>
				
		<div class="form-group col-sm-4">
			<label class="control-label"><i class="glyphicon glyphicon-time"></i> Install Time Zone</label>
			<input class="form-control" name="installtimezone" type="text" value="<?php if(isset($installtimezone_display)){ echo "$installtimezone_display";}?>" placeholder="Time Zone" readonly />
		</div> 

		<div class="row">
		</div>

		<div class="form-group col-sm-3">
			<label class="control-label"><i class="	glyphicon glyphicon-share"></i> Demo</label>
			<select class="form-control" name="demo" >
				<option  value="<?php echo"$demo_display"?>"> <?php echo"$demo_display"?></option>
				<option value="Y">Y</option>
				<option value="N">N</option>
		</select>
		</div>

		<div class="form-group col-sm-3">
			<label class="control-label" ><i class="glyphicon glyphicon-calendar"></i> Demo Days</label>
			<input class="form-control"  name="demoday" type="num" value="<?php echo"$demodays_display"?>"/>
		</div>

		<div class="form-group col-sm-3">
			<label class="control-label" ><i class="glyphicon glyphicon-calendar"></i> Expiration Date</label>
			<input class="form-control"  name="expirationdate" type="date" value="<?php echo"$expirationdate_display"?>"/>
		</div>

		<div class="form-group col-sm-4">
			<label class="control-label"><i class="glyphicon glyphicon-save"></i> Project Name</label><span style="color:red">*</span>
			<input required class="form-control" name="projectname" type="text" value="<?php echo"$projectname_display"?>" placeholder="Project Name" />
		</div>

		<div class="form-group col-sm-6">
			<label class="control-label"><i class="glyphicon glyphicon-edit"></i> Pilot </label>
			<input class="form-control" name="pilot" type="text" value="<?php echo"$pilot_display"?>" placeholder="Pilot" />
		</div>
	</form>
</div>


</div>
</div>

</body>
</html>