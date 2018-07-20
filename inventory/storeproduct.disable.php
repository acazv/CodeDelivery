<?php 		

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('3 Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

	$view         = (isset($_GET['id']) ? $_GET['id'] : null);
	$pkey         = (isset($_GET['id1']) ? $_GET['id1'] : null);
	$pkey_feature = (isset($_GET['id2']) ? $_GET['id2'] : null);

	// API to disabled database

	$product_delete = "0";

	// get new product dnum
	$sql_store_product_dnum = "SELECT * FROM store_product WHERE snum = '$pkey' AND feature = '$pkey_feature' ORDER BY dnum DESC LIMIT 1";
	$result_store_product_dnum = mysqli_query($con, $sql_store_product_dnum)or die('17 Could not query database at this time');
	while($row_store_product_dnum=mysqli_fetch_array($result_store_product_dnum))
	{
		$product_dnum = $row_store_product_dnum['dnum'];
	}

	// get new delivery status
	$sql_store_delivery_status = "SELECT * FROM store_delivery WHERE snum = '$pkey' AND dnum = '$product_dnum'";
	$result_store_delivery_status = mysqli_query($con, $sql_store_delivery_status)or die('25 Could not query database at this time');
	while($row_store_product_status=mysqli_fetch_array($result_store_delivery_status))
	{
		$product_status = $row_store_product_status['dstatus'];

		$sql_store_status_code = "SELECT * FROM deploy_status WHERE status = '$product_status'";
		$result_store_status_code = mysqli_query($con, $sql_store_status_code)or die('31 Could not query database at this time');
		while($row_store_status_code=mysqli_fetch_array($result_store_status_code))
		{
			$product_code = $row_store_status_code['code'];

			if(is_numeric(stripos($product_code,"N")))
			{
				// Status set to Not Active
				$product_delete = "1";
			}
		}
	}
		
	if(empty($product_delete))
	{
		// get store
		$sql_store = "SELECT * FROM store WHERE snum = '$pkey'";
		$result_store = mysqli_query($con, $sql_store)or die('47 Could not query database at this time');
		if(empty(mysqli_num_rows($result_store)))
		{
			// new store record
			$sql_insert_store = mysqli_query($con, "INSERT INTO store ( snum ) VALUES ('$pkey')")or die('51 Could not query database at this time');
		}

		// new store delivery record

		$requestdate = date("Y-m-d");
		$requesttime = date("H:i:s");

		$sql_insert_store_delivery = mysqli_query($con, "INSERT INTO store_delivery ( snum, dstatus, requesttime, requestdate ) VALUES ('$pkey', 'N', '$requesttime', '$requestdate' )");

		// get new delevery number
		$sql_store_delivery_id = "SELECT * FROM store_delivery WHERE snum = '$pkey' ORDER BY id DESC LIMIT 1";
		$result_store_delivery_id = mysqli_query($con, $sql_store_delivery_id)or die('64 Could not query database at this time');
		while($row_store_delivery_id=mysqli_fetch_array($result_store_delivery_id))
		{
			$id_delivery = $row_store_delivery_id['id'];
		}

		// load new delivery number to dnum field
		$sql_update_store = mysqli_query($con, "UPDATE store SET dnum = '$id_delivery'
		WHERE snum = '$pkey'")or die('72 Could not query database at this time');

		// load new delivery number to dnum field
		$sql_update_store_delivery = mysqli_query($con, "UPDATE store_delivery SET dnum = '$id_delivery'
		WHERE id = $id_delivery")or die('76 Could not query database at this time');

		// load product for new delivery number
		$sql_insert_product = mysqli_query($con, "INSERT INTO store_product (
		snum, feature, status, dnum
		) VALUES (
		'$pkey', '$pkey_feature', '0', '$id_delivery'
		)")or die('83 Could not query database at this time');

		// load enabled logons for specific feature
		$sql_store_product_logon = "SELECT * FROM store_product_logon WHERE snum = '$pkey' AND feature = '$pkey_feature' ORDER BY dnum, feature, logon";
		$result_store_product_logon = mysqli_query($con, $sql_store_product_logon)or die('87 Could not query  database at this time');
		while($row_store_product_logon=mysqli_fetch_array($result_store_product_logon))
		{
			$dnum    = $row_store_product_logon['dnum'];
			$logon   = $row_store_product_logon['logon'];

			$sql_store_product = "SELECT * FROM store_product WHERE dnum = '$dnum'";
			$result_store_product = mysqli_query($con, $sql_store_product)or die('95 Could not query  database at this time');
			while($row_store_product=mysqli_fetch_array($result_store_product))
			{
				$status  = $row_store_product['status'];

				if($status)
				{
					//store_product_logon update
					$sql_insert_logon = mysqli_query($con, "INSERT INTO store_product_logon (
					snum, feature, logon, status, dnum
					) VALUES (
					'$pkey', '$pkey_feature', '$logon', '0', '$id_delivery'
					)")or die('100 Could not query database at this time');
				} else {
					//store_product_logon delete
					$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$id_delivery' and feature = '$pkey_feature and logon = '$logon'")or die('108 Could not query database at this time');
				}
			}
		}

		// load feature dependencies
		$sql_store_productdep = "SELECT * FROM store_productdep WHERE snum = '$pkey' AND feature = '$pkey_feature' ORDER BY dnum, feature";
		$result_store_productdep = mysqli_query($con, $sql_store_productdep)or die('117 Could not query  database at this time');
		while($row_store_productdep=mysqli_fetch_array($result_store_productdep))
		{
			$dnum       = $row_store_productdep['dnum'];
			$featuredep = $row_store_productdep['featuredep'];

			$sql_store_product = "SELECT * FROM store_product WHERE dnum = '$dnum'";
			$result_store_product = mysqli_query($con, $sql_store_product)or die('122 Could not query  database at this time');
			while($row_store_product=mysqli_fetch_array($result_store_product))
			{
				$status  = $row_store_product['status'];

				if($status)
				{
					//store_product_logon update
					$sql_insert_productdep = mysqli_query($con, "INSERT INTO store_productdep (
					snum, feature, featuredep, status, dnum
					) VALUES (
					'$pkey', '$pkey_feature', '$featuredep', '1', '$id_delivery'
					)")or die('118 Could not query database at this time');
				} else {
					//store_productdep delete
					$sql_delete_productdep = mysqli_query($con, "DELETE FROM store_productdep WHERE dnum = '$id_delivery' AND feature = '$pkey_feature' AND featuredep = '$featuredep'")or die('138 Could not query database at this time');
				}

				// load enabled feature dependencies logon
				$sql_store_productdep_logon = "SELECT * FROM store_productdep_logon WHERE snum = '$pkey' AND feature = '$pkey_feature' AND featuredep = '$featuredep' ORDER BY dnum, feature, featuredep, logon";
				$result_store_productdep_logon = mysqli_query($con, $sql_store_productdep_logon)or die('123 Could not query  database at this time');
				while($row_store_productdep_logon=mysqli_fetch_array($result_store_productdep_logon))
				{
					$logon       = $row_store_productdep_logon['logon'];

					if($status)
					{
						$sql_insert_logon = mysqli_query($con, "INSERT INTO store_productdep_logon (
						snum, feature, featuredep, logon, status, dnum
						) VALUES (
						'$pkey', '$pkey_feature', '$featuredep', '$logon', '0', '$id_delivery'
						)")or die('136 Could not query database at this time');
					} else {
						//store_productdep delete
						$sql_delete_productdep_logon = mysqli_query($con, "DELETE FROM store_productdep_logon WHERE dnum = '$id_delivery' and feature = '$pkey_feature' and featuredep = '$featuredep' and logon = '$logon'")or die('157 Could not query database at this time');
					}
				}
			}
		}
	}

	if ($product_delete)
	{
		//store_delivery delete
		$sql_delete_delivery = mysqli_query($con, "DELETE FROM store_delivery WHERE dnum = '$product_dnum'")or die('147 Could not query database at this time');

		//store_product delete
		$sql_delete_product = mysqli_query($con, "DELETE FROM store_product WHERE dnum = '$product_dnum'")or die('150 Could not query database at this time');

		//store_productdep delete
		$sql_delete_productdep = mysqli_query($con, "DELETE FROM store_productdep WHERE dnum = '$product_dnum'")or die('153 Could not query database at this time');

		//store_productdep_logon delete
		$sql_delete_productdep_logon = mysqli_query($con, "DELETE FROM store_productdep_logon WHERE dnum = '$product_dnum'")or die(' 156 Could not query database at this time');

		//store_product_logon delete
		$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$product_dnum'")or die('159 Could not query database at this time');

		//store delete
		$sql_store_product = "SELECT * FROM store_product WHERE snum = '$pkey'";
		$result_store_product = mysqli_query($con, $sql_store_product);
		if(empty(mysqli_num_rows($result_store_product)))
		{
			$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store WHERE snum = '$pkey'");
		}
	}

	//where to go after the program is run
	if($view == 'store')
	{
		header("location: storeproduct.php?id=$pkey");
	} else {
		header("location: product.display.php?id=$pkey_feature");		
	}
?>