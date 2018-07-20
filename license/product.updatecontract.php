<?php

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

	$view          = (isset($_GET['id']) ? $_GET['id'] : null);
	$snum          = (isset($_GET['id1']) ? $_GET['id1'] : null);
	$dnum          = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$action        = (isset($_GET['id3']) ? $_GET['id3'] : null);
	$feature_pkey  = (isset($_GET['id4']) ? $_GET['id4'] : null);

	$fetchflag = "0";

	if(isset($_POST['save']))      { $fetchflag = "1";}
	if(isset($_POST['saveall']))   { $fetchflag = "1";}

	if ($fetchflag == "1")
	{ // Fetching variables of the form which travels in URL
		$projectnum          = $_POST['projectnum'];
		$casenum             = $_POST['casenum'];
		$contractnum         = $_POST['contractnum'];
		$requestinstalldate  = $_POST['requestinstalldate'];
		$requestinstalltime  = $_POST['requestinstalltime'];
		$installtimezone     = $_POST['installtimezone'];
		$demo                = $_POST['demo'];
		$demodays            = $_POST['demoday'];
		$expirationdate      = $_POST['expirationdate'];
		$projectname         = $_POST['projectname'];
		$pilot               = $_POST['pilot'];
	}

	if(isset($_POST['save']))
	{ // Save
		$sql_update = mysqli_query($con, "UPDATE store_delivery SET projectnum = '$projectnum',
		casenum              = '$casenum',
		contractnum          = '$contractnum',
		requestinstalldate   = '$requestinstalldate',
		requestinstalltime   = '$requestinstalltime',
		installtimezone      = '$installtimezone',
		demo                 = '$demo',
		demodays             = '$demodays',
		expirationdate       = '$expirationdate',
		projectname          = '$projectname',
		pilot                = '$pilot'
		WHERE dnum = '$dnum'")or die('Could not query database at this time HERE');
	} 

	if(isset($_POST['saveall']))
	{  //Save All

		// get all Delivery records
		$sql_delivery_button = "SELECT * FROM store_product WHERE feature = '$feature_pkey' ORDER BY snum";
		$result_delivery_button = mysqli_query($con, $sql_delivery_button);
		while($row_delivery_button=mysqli_fetch_array($result_delivery_button))
		{
			// set Delivery Required Status
			$select_dnum = $row_delivery_button['dnum'];

			require 'delivery.required.status.php';

			if (empty($select_delivery_rstatus))
			{
				$sql_update = mysqli_query($con, "UPDATE store_delivery SET projectnum = '$projectnum',
				casenum              = '$casenum',
				contractnum          = '$contractnum',
				requestinstalldate   = '$requestinstalldate',
				requestinstalltime   = '$requestinstalltime',
				installtimezone      = '$installtimezone',
				demo                 = '$demo',
				demodays             = '$demodays',
				expirationdate       = '$expirationdate',
				projectname          = '$projectname',
				pilot                = '$pilot'
				WHERE dnum = '$select_dnum'")or die('Could not query database at this time');
			}
		}
	} 
		
	if($action == 'deploy')
	{ //deploy
		$requestdeliverydate = date("Y-m-d");
		$requestdeliverytime = date("H:i:s");

		$sql_update = mysqli_query($con, "UPDATE store_delivery SET dstatus = '0',
		requestdeliverydate  = '$requestdeliverydate',
		requestdeliverytime  = '$requestdeliverytime'
		WHERE dnum = '$dnum'")or die('Could not query database at this time');

		//ice_store_product_logon delete
		$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$dnum' AND status = '0'")or die('Could not query database at this time');

		//ice_store_productdep_logon delete
		$sql_delete_productdep_logon = mysqli_query($con, "DELETE FROM store_productdep_logon WHERE dnum = '$dnum' AND status = '0'")or die('Could not query database at this time');
	}

	if($action == 'deployall')
	{ //deploy all

		// get all Delivery records
		$sql_delivery_button = "SELECT * FROM store_product WHERE feature = '$feature_pkey' ORDER BY snum";
		$result_delivery_button = mysqli_query($con, $sql_delivery_button);
		while($row_delivery_button=mysqli_fetch_array($result_delivery_button))
		{
			// set Delivery Required Status
			$select_dnum         = $row_delivery_button['dnum'];
			$select_featuredep   = '';

			require 'select.delivery.status.php';

			if ($select_delivery_rstatus)
			{
				$requestdeliverydate = date("Y-m-d");
				$requestdeliverytime = date("H:i:s");

				$sql_update = mysqli_query($con, "UPDATE store_delivery SET dstatus = '0',
				requestdeliverydate = '$requestdeliverydate',
				requestdeliverytime = '$requestdeliverytime'
				WHERE dnum = '$select_dnum'");

				//ice_store_product_logon delete
				$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$select_dnum' AND status = '0'")or die('Could not query database at this time');

				//ice_store_productdep_logon delete
				$sql_delete_productdep_logon = mysqli_query($con, "DELETE FROM store_productdep_logon WHERE dnum = '$select_dnum' AND status = '0'")or die('Could not query database at this time');
			}
		}
	}
   
	if($action == 'stopdeploy')
	{ //stop deploy
		$sql_update = mysqli_query($con, "UPDATE store_delivery SET dstatus = '3'
		WHERE dnum = '$dnum'")or die('Could not query database at this time');
	}

	if($action == 'redeploy')
	{ //re-deploy
		$sql_update = mysqli_query($con, "UPDATE store_delivery SET dstatus = '4'
		WHERE dnum = '$dnum'")or die('Could not query database at this time');
	}
   
	echo "Updated here";	
	if($view == 'store')
	{
		header("location: store.display.php?id=$view&id1=$snum");
	}
	if($view == 'product')
	{
		header("location: product.display.php?id=$view&id1=$feature_pkey");
	}
   
	mysqli_close($con); // Closing Connection with Server
?>