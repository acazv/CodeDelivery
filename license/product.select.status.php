<?php
	$select_product_logon_status = '0';

	$sql_select_product_logon = "SELECT * FROM store_product_logon WHERE dnum = '$select_dnum'";
	$result_select_product_logon = mysqli_query($con, $sql_select_product_logon)or die('Could not query database at this time');
	while($row_select_product_logon=mysqli_fetch_array($result_select_product_logon))
	{
		$select_product_status = $row_select_product_logon['status'];
		if($select_product_status == '1'){ $select_product_logon_status = "1"; break; } // required
	}
?>