 <?php 		

	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, 3 could not connect to server');
	mysqli_select_db($con, "codedelivery") or die(mysqli_error());

	$view         =(isset($_GET['id']) ? $_GET['id'] : null);
	$pkey         =(isset($_GET['id1']) ? $_GET['id1'] : null);
	$pkey_feature =(isset($_GET['id2']) ? $_GET['id2'] : null);

	// API to enabled database

	$product_dnum = "0";
	$product_delete = "0";

	// get new product dnum
	$sql_store_product_dnum = "SELECT * FROM store_product WHERE snum = '$pkey' AND feature = '$pkey_feature' ORDER BY dnum DESC LIMIT 1";
	$result_store_product_dnum = mysqli_query($con, $sql_store_product_dnum)or die('20 Could not query database at this time');
	while($row_store_product_dnum=mysqli_fetch_array($result_store_product_dnum))
	{
		$product_dnum = $row_store_product_dnum['dnum'];
	}

	// get new delivery status
	$sql_store_delivery_status = "SELECT * FROM store_delivery WHERE snum = '$pkey' AND dnum = '$product_dnum'";
	$result_store_delivery_status = mysqli_query($con, $sql_store_delivery_status)or die('28 Could not query database at this time');
	while($row_store_product_status=mysqli_fetch_array($result_store_delivery_status))
	{
		$product_status = $row_store_product_status['dstatus'];

		$sql_store_status_code = "SELECT * FROM deploy_status WHERE status = '$product_status'";
		$result_store_status_code = mysqli_query($con, $sql_store_status_code)or die('34 Could not query database at this time');
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
		$result_store = mysqli_query($con, $sql_store);
		if(empty(mysqli_num_rows($result_store)))
		{
			// new store record
			$sql_insert_store = mysqli_query($con, "INSERT INTO store ( snum ) VALUES ('$pkey')")or die('54 Could not query database at this time');
		}

		// new store delivery record
		date_default_timezone_set("America/New_York");

		$requestdate = date("Y-m-d");
		$requesttime = date("H:i:s");

		$sql_insert_store_delivery = mysqli_query($con, "INSERT INTO store_delivery ( snum, dstatus, requesttime, requestdate ) VALUES ('$pkey', 'N', '$requesttime', '$requestdate' )");

		// get new delevery number
		$sql_store_delivery_id = "SELECT * FROM store_delivery WHERE snum = '$pkey' ORDER BY id DESC LIMIT 1";
		$result_store_delivery_id = mysqli_query($con, $sql_store_delivery_id)or die('67 Could not query database at this time');
		while( $row_store_delivery_id = mysqli_fetch_array( $result_store_delivery_id))
		{
			$id_delivery = $row_store_delivery_id['id'];
		}

		// load new delivery number to dnum field
		$sql_update_store = mysqli_query($con, "UPDATE store SET dnum = '$id_delivery'
		WHERE snum = '$pkey'")or die('75 Could not query  database at this time');
		
		// load new delivery number to dnum field
		$sql_update_store_delivery = mysqli_query($con, "UPDATE store_delivery SET dnum = '$id_delivery'
		WHERE id = $id_delivery")or die('79 Could not query  database at this time');

		// load feature for new delivery number
		$sql_insert_product = mysqli_query($con, "INSERT INTO store_product (
		snum, feature, status, dnum 
		) VALUES (
		'$pkey', '$pkey_feature', '1', '$id_delivery' 
		)")or die('86 Could not query database at this time');

		// load disabled logons for specific feature
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

				if(empty($status))
				{
					//store_product_logon update
					$sql_insert_logon = mysqli_query($con, "INSERT INTO store_product_logon (
					snum, feature, logon, status, dnum
					) VALUES (
					'$pkey', '$pkey_feature', '$logon', '0', '$id_delivery'
					)")or die('100 Could not query database at this time');
				} else {
					//store_product_logon delete
					$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$id_delivery' and feature = '$pkey_feature and logon = '$logon'");
				}
			}
		}

		// load feature logons for specific feature
		$sql_product_appcode = "SELECT * FROM product_appcode WHERE feature = '$pkey_feature'";
		$result_product_appcode = mysqli_query($con, $sql_product_appcode)or die('119 Could not query  database at this time');
		while($row_product_appcode=mysqli_fetch_array($result_product_appcode))
		{
			$appcode = $row_product_appcode['appcode'];

			$sql_appcode_logon = "SELECT * FROM acc_store_logon WHERE snum ='$pkey' AND logon like '%-$appcode'";
			$result_appcode_logon = mysqli_query($con, $sql_appcode_logon)or die('125 Could not queryheredatabase at this time');	
			while($row_appcode_logon=mysqli_fetch_array( $result_appcode_logon))
			{
				$appcode_logon = $row_appcode_logon['logon'];

				$sql_store_product_logon = "SELECT * FROM store_product_logon WHERE dnum = '$id_delivery' AND feature = '$pkey_feature' AND logon = '$appcode_logon'";
				$result_store_product_logon = mysqli_query($con, $sql_store_product_logon)or die('131 Could not query  database at this time');

				if(empty(mysqli_num_rows($result_store_product_logon)))
				{
					$sql_insert_logon = mysqli_query($con, "INSERT INTO store_product_logon (
					snum, feature, logon, status, dnum
					) VALUES (
					'$pkey', '$pkey_feature', '$appcode_logon', '0', '$id_delivery'
					)")or die('139 Could not query database at this time');
				}
			}
		}

		// load feature dependencies
		$sql_repo_productdep = "SELECT * FROM productdep WHERE feature = '$pkey_feature'";
		$result_repo_productdep = mysqli_query($con, $sql_repo_productdep)or die('165 Could not query database at this time');
		while($row_repo_productdep=mysqli_fetch_array($result_repo_productdep))
		{
			$featuredep = $row_repo_productdep['featuredep'];

			$sql_insert_productdep = mysqli_query($con, "INSERT INTO store_productdep (
			snum, feature, featuredep, status, dnum
			) VALUES (
			'$pkey', '$pkey_feature', '$featuredep', '1', '$id_delivery'
			)")or die('174 Could not query database at this time');

			// load logons for specific feature/feature dependencies appcode
			$sql_featuredep_appcode = "SELECT * FROM product_appcode WHERE feature = '$featuredep'";
			$result_featuredep_appcode = mysqli_query($con, $sql_featuredep_appcode)or die('178 Could not query  database at this time');
			while($row_featuredep_appcode=mysqli_fetch_array($result_featuredep_appcode))
			{
				$appcode = $row_featuredep_appcode['appcode'];

				$sql_appcode_logon = "SELECT * FROM acc_store_logon WHERE snum ='$pkey' AND logon like '%-$appcode'";
				$result_appcode_logon = mysqli_query($con, $sql_appcode_logon)or die('184 Could not queryheredatabase at this time');	
				while($row_appcode_logon=mysqli_fetch_array( $result_appcode_logon))
				{
					$appcode_logon = $row_appcode_logon['logon'];

					$sql_insert_logon = mysqli_query($con, "INSERT INTO store_productdep_logon (
					snum, feature, featuredep, logon, status, dnum
					) VALUES (
					'$pkey', '$pkey_feature', '$featuredep', '$appcode_logon', '0', '$id_delivery'
					)")or die('193 Could not query database at this time');	
				}
			}
		}
	}

	if ($product_delete)
	{
		//store_delivery delete
		$sql_delete_delivery = mysqli_query($con, "DELETE FROM store_delivery WHERE dnum = '$product_dnum'")or die('Could not query database at this time');

		//store_product delete
		$sql_delete_product = mysqli_query($con, "DELETE FROM store_product WHERE dnum = '$product_dnum'")or die('Could not query database at this time');

		//store_productdep delete
		$sql_delete_productdep = mysqli_query($con, "DELETE FROM store_productdep WHERE dnum = '$product_dnum'")or die('Could not query database at this time');

		//store_productdep_logon delete
		$sql_delete_productdep_logon = mysqli_query($con, "DELETE FROM store_productdep_logon WHERE dnum = '$product_dnum'")or die('Could not query database at this time');

		//store_product_logon delete
		$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store_product_logon WHERE dnum = '$product_dnum'")or die('Could not query database at this time');

		//store delete
		$sql_store_product = "SELECT * FROM store_product WHERE snum = '$pkey'";
		$result_store_product = mysqli_query($con, $sql_store_product);
		if(empty(mysqli_num_rows($result_store_product)))
		{
			$sql_delete_product_logon = mysqli_query($con, "DELETE FROM store WHERE snum = '$pkey'");
		}
	}
      		
	if($view == 'store'){
		header("location: storeproduct.php?id=$pkey");	
	} else {
		header("location: product.display.php?id=$pkey_feature");	
	}
?>