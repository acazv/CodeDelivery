<?php 		

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());
	
//One program page to update either the logon or dependancy logon folders. 
// The action key tells the program which update we are doing

	$view            = (isset($_GET['id']) ? $_GET['id'] : null);
	$pkey            = (isset($_GET['id1']) ? $_GET['id1'] : null);
	$pkey_feature    = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_action     = (isset($_GET['id3']) ? $_GET['id3'] : null);
	$pkey_logon      = (isset($_GET['id4']) ? $_GET['id4'] : null);
	$pkey_dnum       = (isset($_GET['id5']) ? $_GET['id5'] : null);
	$pkey_featuredep = (isset($_GET['id6']) ? $_GET['id6'] : null);
	$pkey_status     = (isset($_GET['id7']) ? $_GET['id7'] : null);
	$pkey_dstatus    = (isset($_GET['id8']) ? $_GET['id8'] : null);

   
	if($pkey_action == 'logon')
	{
		$sql_contract_logon = "SELECT * FROM store_product_logon WHERE snum = '$pkey' AND feature = '$pkey_feature' AND logon ='$pkey_logon' AND dnum = '$pkey_dnum' AND status = '1'";
		$result_contract_logon = mysqli_query($con, $sql_contract_logon);
		if (empty(mysqli_num_rows($result_contract_logon)))
		{
			$sql_update = mysqli_query($con, "UPDATE store_product_logon SET status = '1'
			WHERE snum = '$pkey' AND feature = '$pkey_feature' AND logon ='$pkey_logon' AND dnum = '$pkey_dnum'");
			
			header("location: feature.logon.php?id=$view&id1=$pkey&id2=$pkey_feature&id3=$pkey_dnum&id4=$pkey_dstatus");	
			echo "checked";
		} else {

			$sql_update = mysqli_query($con, "UPDATE store_product_logon SET status = '0'
			WHERE snum = '$pkey' AND feature = '$pkey_feature' AND logon ='$pkey_logon' AND dnum = '$pkey_dnum'");

			header("location: feature.logon.php?id=$view&id1=$pkey&id2=$pkey_feature&id3=$pkey_dnum&id4=$pkey_dstatus");	
			echo "unchecked";
		}
	}

	if($pkey_action == 'dependencylogon')
	{
		$sql_featuredep_logon = "SELECT * FROM store_productdep_logon WHERE dnum = '$pkey_dnum' AND feature = '$pkey_feature' AND featuredep = '$pkey_featuredep' AND logon ='$pkey_logon' AND status = '1'";
		$result_featuredep_logon = mysqli_query($con, $sql_featuredep_logon);
		if (empty(mysqli_num_rows($result_featuredep_logon)))
		{
			$sql_update = mysqli_query($con, "UPDATE store_productdep_logon SET status = '1'
			WHERE snum = '$pkey' AND feature = '$pkey_feature' AND featuredep = '$pkey_featuredep' AND logon ='$pkey_logon' AND dnum = '$pkey_dnum'");	
			
			echo "checked";
		} else {
			$sql_update = mysqli_query($con, "UPDATE store_productdep_logon SET status = '0'
			WHERE snum = '$pkey' AND feature = '$pkey_feature' AND featuredep = '$pkey_featuredep' AND logon ='$pkey_logon' AND dnum = '$pkey_dnum'");	
			
			echo "unchecked";
		}

		header("location: feature.dependency.logon.php?id=$view&id1=$pkey&id2=$pkey_feature&id3=$pkey_dnum&id4=$pkey_featuredep&id5=$pkey_dstatus");
	}

?>