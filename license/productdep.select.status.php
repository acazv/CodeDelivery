<?php
   // set Product Logon Select Status

	if(empty($select_featuredep))
	{
		$select_productdep_logon_status  = '0';
		$select_productdep_cnt           = '0';

		$sql_select_product = "SELECT * FROM store_product WHERE dnum = '$select_dnum'";
		$result_select_product = mysqli_query($con, $sql_select_product)or die('Could not query database at this time');
		while($row_select_product=mysqli_fetch_array($result_select_product))
		{
			$select_feature = $row_select_product['feature'];
		}

		$sql_select_productdep = "SELECT * FROM productdep WHERE feature = '$select_feature'";
		$result_select_productdep = mysqli_query($con, $sql_select_productdep)or die('Could not query database at this time');
		$select_productdep_cnt = mysqli_num_rows($result_select_productdep);

		while($row_select_productdep=mysqli_fetch_array($result_select_productdep))
		{
			$select_featuredep = $row_select_productdep['featuredep'];

			$select_productdep_flogon_status = "0";

			$sql_select_productdep_logon = "SELECT * FROM store_productdep_logon WHERE dnum = '$select_dnum' AND featuredep = '$select_featuredep'";
			$result_select_productdep_logon = mysqli_query($con, $sql_select_productdep_logon)or die('Could not query database at this time');
			while($row_select_productdep_logon=mysqli_fetch_array($result_select_productdep_logon))
			{
				$select_productdep_status = $row_select_productdep_logon['status'];

				if($select_productdep_status == '1')
				{ 
					$select_productdep_logon_status =  $select_productdep_logon_status + '1';  break; 
				} // required
			}
		}

		if($select_productdep_cnt == $select_productdep_logon_status)
		{
			$select_productdep_logon_status = "1";
		} else {
			$select_productdep_logon_status = "0";
		}
	} else {
		$select_productdep_logon_status = '0';

		$sql_select_productdep_logon = "SELECT * FROM store_productdep_logon WHERE dnum = '$select_dnum' AND featuredep = '$select_featuredep'";
		$result_select_productdep_logon = mysqli_query($con, $sql_select_productdep_logon)or die('Could not query database at this time');
		$select_productdep_cnt = mysqli_num_rows($result_select_productdep_logon);      
		while($row_select_productdep_logon=mysqli_fetch_array($result_select_productdep_logon))
		{
			$select_productdep_status = $row_select_productdep_logon['status'];

			if($select_productdep_status == '1'){ $select_productdep_logon_status = "1"; break; } // required
		}
	}
?>