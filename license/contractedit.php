<html>
  <head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="../css/main.css" rel="stylesheet" />
	<link href="../css/navbar.css" rel="stylesheet" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
<div id="wrapper">


<?php
	session_start();

	$con =  mysqli_connect('127.0.0.1', 'root', '')or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery")or die(mysqli_error());

	// Reset of values to default 
	$cnum          = "";
	$ctimezone     = "";
	$cinstalltime  = "";

	// get URL values
	$view          = $_GET['id'];
	$pkey          = $_GET['id1'];
	$pkey_dnum     = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_feature  = (isset($_GET['id3']) ? $_GET['id3'] : null);  

	$cnum          = $_SESSION['cnum_search'];

	// side display
	$sql = "SELECT * FROM acc_store where snum = '$pkey'";
	$result = mysqli_query($con, $sql);	
	while($row=mysqli_fetch_array($result))
	{
		$id      = $row['id'];
		$snum    = $row['snum'];
		$name    = $row['name'];
		$cnum    = $row['cnum'];
		$ip      = $row['ip'];
		$address = $row['address'];
	}

	//Need timezone information to be able to update file properly
	$sql_ctimezone = "SELECT * FROM delivery where cnum = '$cnum'";
	$result_ctimezone = mysqli_query($con, $sql_ctimezone);
	while($row_ctimezone=mysqli_fetch_array($result_ctimezone))
	{
		$ctimezone    = $row_ctimezone['timezone'];
		$cinstalltime = $row_ctimezone['installtime'];
	}

	if(empty($ctimezone))
	{
		$sql_ctimezone = "SELECT * FROM timezone";
		$result_ctimezone = mysqli_query($con, $sql_ctimezone);
	}


	// query of selected delivery		
	if(isset($pkey_dnum))
	{
		$sql_display = "SELECT * FROM store_delivery where dnum = '$pkey_dnum'";
		$result_display = mysqli_query($con, $sql_display);	
		while($row_display=mysqli_fetch_array($result_display))
		{
			$dstatus_store_display        = $row_display['dstatus'];
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

			$sql_dstatus_name = "SELECT * FROM deploy_status WHERE status = '$dstatus_store_display'";
			$result_dstatus_name = mysqli_query($con, $sql_dstatus_name);
			while($row_dstatus_name=mysqli_fetch_array($result_dstatus_name))
			{
				$dstatus_code = $row_dstatus_name['code'];
			}
		}

		//package name selection
		$sql_package_name = "SELECT * FROM product where feature = '$pkey_feature'";
		$result_package_name = mysqli_query($con, $sql_package_name);		
		while($row_package_name=mysqli_fetch_array($result_package_name))
		{
			$feature_name_display = $row_package_name['name'];
		}
   }
	
	// default data
	if(empty($requestinstalltime_display)) {$requestinstalltime_display = "$cinstalltime";	}
	if(empty($installtimezone_display))    {$installtimezone_display = "$ctimezone";	}		

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
   <a href="../home.php"><b>License Simulation</b></a>
</header>

<?php 
// The user can get to this page either from the Store View or the Product view pages
// Depending on where the user is coming from is what will be displayed here on the "where you are" feature
// $view is the key in telling the program where the user is coming from and what should be displayed
	if(empty($pkey_dnum))
	{ 
		if($view == 'store')
		{
			echo "<label><a href='store.php'> Store View</a> > Delivery </label>";
		}
		
		if($view == 'product')
		{
			echo "<label><a href='product.php'> Product View</a> > Delivery </label>";
		}
	} else {
		if($view == 'store')
		{
			echo "<label><a href='store.php'> Store View</a> > Packages and Features > Delivery </label>";
		}
		
		if($view == 'product')
		{
			echo "<label><a href='product.php'> Product View</a> > Packages and Features > Delivery </label>";
		}
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
		echo "<a href='store.display.php?id=".$view."&id1=".$pkey."'>Packages and Features</a> ";
	}
	
	if($view == 'product')
	{
		echo "<a href='product.display.php?id=".$view."&id1=".$pkey_feature."'>Packages and Features</a> ";
	}
				
	echo "</li>";

	if (empty($navbar_delivery_cstatus))
	{
		echo "<li><a href='' >Delivery</a> </li>";
	} else {
		
		// why is the else duplicated?
		if(empty($navbar_delivery_rstatus))
		{
			if($view == 'store')
			{
				echo "<li class='active' style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
			}
			
			if($view == 'product')
			{
				echo "<li class='active' style='background-color: #FFFF99'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
			}
		} else {
			if($view == 'store')
			{
				echo "<li class='active' style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=".$pkey."' >Delivery</a></li>";
			}
			
			if($view == 'product')
			{
				echo "<li class='active' style='background-color: #228B22'><a href='contractedit.php?id=".$view."&id1=&id2=&id3=".$pkey_feature."' >Delivery</a></li>";
			}
		}
	}
?>
</ul>
	
<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px">


<?php 
	if(!empty($pkey_dnum))
	{
		//lable display if selected packag
		echo "<label>Feature:</label> $pkey_feature &nbsp;
			  <label>Name:</label> $feature_name_display 
			  <label>Deploy#:</label> $pkey_dnum 
			  <br><br>"; 
	}
?>
				
	<form 
		<?php 
			echo "action='".$view.".updatecontract.php?id=".$view."&id1=".$pkey."&id2=".$pkey_dnum."&id3=&id4=".$pkey_feature."'";
		?> 
	method='post'>

	<!-- button display -->
	<div class="col-sm-12">
		<button  style="background-color: #708090; color:white;"><a href= "
		<?php
			//the buttons also display according to what view the user is in
			if($view == 'store')
			{
				echo "store.display.php?id=".$view."&id1=$pkey"; 
			}
			if($view == 'product')
			{
				echo "product.display.php?id=".$view."&id1=$pkey_feature"; 
			}?>">Cancel</a></button>&nbsp;&nbsp;&nbsp;
            
<?php

	//navbar Delivery Status
	if(empty($pkey_dnum))
	{
		if($navbar_delivery_rstatuscs)
		{
			echo "<input style='background-color: #228B22; color:white;' class='submit' name='saveall' type='submit' value='(".$navbar_delivery_rstatuscs.") Save' />&nbsp;";
		}
		if($navbar_delivery_rstatuscd)
		{
			echo "<button style='background-color: #228B22; color:white;'><a href='".$view.".updatecontract.php?id=".$view."&id1=".$pkey."&id2=&id3=deployall&id4=".$pkey_feature."'>(".$navbar_delivery_rstatuscd.") Deploy </a></button>";
		}
	} else {
		// set Select Delivery Status
		$select_dnum         = $pkey_dnum;
		$select_featuredep   = '';

		require 'select.delivery.status.php';	

		//packages and features delivery select
		if(is_numeric(stripos($dstatus_code,"N")))
		{
			// Status Not Active
			echo "<input style='background-color: #228B22; color:white;' class='submit' name='save' type='submit' value='Save' />&nbsp;";

			if($select_delivery_rstatus)
			{
				// required delivery data set
				echo"<button style='background-color: #228B22; color:white;'><a href='".$view.".updatecontract.php?id=".$view."&id1=".$pkey."&id2=".$pkey_dnum."&id3=deploy&id4=".$pkey_feature."'> Deploy </a> </button>&nbsp;&nbsp;&nbsp;";
			}
		} else {

			// Status Active
			if(is_numeric(stripos($dstatus_code,"F")))
			{
				// Status Failed
				echo"<button style='background-color: #228B22; color:white;'><a href='".$view.".updatecontract.php?id=".$view."&id1=".$pkey."&id2=".$pkey_dnum."&id3=redeploy&id4=".$pkey_feature."'> Re-Deploy </a> </button>&nbsp;";
			}
			if(is_numeric(stripos($dstatus_code,"R")) || $dstatus_code == 'A')
			{
				// Status Re-Deploy
				echo"<button style='background-color: #228B22; color:white;'><a href='".$view.".updatecontract.php?id=".$view."&id1=".$pkey."&id2=".$pkey_dnum."&id3=stopdeploy&id4=".$pkey_feature."'> Stop-Deploy </a> </button>&nbsp;";
			}
		}
	}

	if(!empty($pkey_dnum))
	{
		$xrequesttime         = date('h:i A', strtotime($requesttime_display));
		$xrequestdeliverytime = date('h:i A', strtotime($requestdeliverytime_display));

		echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<label> Inventory Date:</label> $requestdate_display
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<label> Inventory Time:</label> $xrequesttime <br>";

		if(!empty($requestdeliverydate_display))
		{
			echo "<label> Delivery Date:</label> $requestdeliverydate_display 
			<label> Delivery Time:</label> $xrequestdeliverytime";
		} 
	}
?>

	<br><br>

	<div class="form-group col-sm-3 ">
		<label class="control-label" ><i class="glyphicon glyphicon-user"></i> Project Number</label>
		<input  class="form-control" name="projectnum" type="text" value="<?php if(isset($projectnum_display)){ echo "$projectnum_display";}?>" placeholder="Project#" />
	</div>

	<div class="form-group col-sm-3">
		<label class="control-label"><i class="glyphicon glyphicon-user"></i>Case Number</label>
		<input class="form-control" name="casenum" type="text" value="<?php if(isset($casenum_display)){ echo "$casenum_display";}?>"  placeholder="Case#"/>
	</div>

	<div class="form-group col-sm-4">
		<label class="control-label"><i class="glyphicon glyphicon-user"></i> Contract Number</label>
		<input class="form-control" name="contractnum" type="text" value="<?php if(isset($contractnum_display)){ echo "$contractnum_display";}?>" placeholder="Contract#" />
	</div> 

	<div class="form-group col-sm-3">
		<label class="control-label"><i class="glyphicon glyphicon-calendar"></i> Install Date</label><span style="color:red">*</span>
		<input required class="form-control" name="requestinstalldate" type="date" value="<?php if(isset($requestinstalldate_display)){ echo "$requestinstalldate_display";}?>"  placeholder="mm/dd/yyyy/"/>
	</div>

	<div class="form-group col-sm-3">
		<label class="control-label"><i class="glyphicon glyphicon-time"></i> Install Time</label><span style="color:red">*</span>
		<input required class="form-control" step="1" name="requestinstalltime" type="Time"  value="<?php if(isset($requestinstalltime_display)){ echo "$requestinstalltime_display";}?>"  />
	</div>

	<div class="form-group col-sm-4">
		<label class="control-label"><i class="glyphicon glyphicon-time"></i> Install Time Zone</label>
		<?php
		//filling the drop down with table content from database
		if(!empty($installtimezone_display))
		{
			echo "<input class='form-control' name='installtimezone' type='text' value='".$installtimezone_display."' placeholder='Time Zone' readonly />";
		} else {
			echo "<select class='form-control' name='installtimezone' >";
			while ($row_timezone = mysqli_fetch_array($result_ctimezone))
			{
				echo "<option value='".$row_timezone['timezone']."'>".$row_timezone['timezone']."</option>";
			}			
			
			echo " </select>";
		} 
		?>
	</div> 

	<div class="row">
	</div>

	<div class="form-group col-sm-3">
		<label class="control-label"><i class="	glyphicon glyphicon-share"></i> Demo</label>
		<select class="form-control" name="demo" >
			<option   value="<?php if(isset($demo_display)){ echo "$demo_display";}?>" > <?php if(isset($demo_display)){ echo "$demo_display";}?> </option>
			<option value="Y">Y</option>
			<option value="N">N</option>
		</select>
	</div>

	<div class="form-group col-sm-3">
		<label class="control-label" ><i class="glyphicon glyphicon-calendar"></i> Demo Days</label>
		<input class="form-control"  name="demoday" type="num" value="<?php if(isset($demodays_display)){ echo "$demodays_display";}?>"  placeholder="Demo Days"/>
	</div>

	<div class="form-group col-sm-3">
		<label class="control-label" ><i class="glyphicon glyphicon-calendar"></i> Expiration Date</label>
		<input class="form-control"  name="expirationdate" type="date" value="<?php if(isset($expirationdate_display)){ echo "$expirationdate_display";}?>" placeholder="mm/dd/yyyy/"/>
	</div>
	   
	<div class="form-group col-sm-4">
		<label class="control-label"><i class="glyphicon glyphicon-save"></i> Project Name</label><span style="color:red">*</span>
		<input required class="form-control" name="projectname" type="text" value="<?php if(isset($projectname_display)){ echo "$projectname_display";}?>" placeholder="Project Name" />
	</div>

	<div class="form-group col-sm-6">
		<label class="control-label"><i class="glyphicon glyphicon-edit"></i> Pilot </label>
		<input class="form-control" name="pilot" type="text" value="<?php if(isset($pilot_display)){ echo "$pilot_display";}?>"  placeholder="Pilot" />
	</div>
	
</div>
</form>


</div>
</div>
</html>