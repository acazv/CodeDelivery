<?php
   // set Delivery Required Status

   $select_delivery_rstatus = '1';  // default
   $select_delivery_cstatus = '0';  // default

	// get all Delivery records
	$sql_select_delivery = "SELECT * FROM store_delivery where dnum = '$select_dnum'";
	$result_select_delivery = mysqli_query($con, $sql_select_delivery)or die('Could not query database at this time');
	while($row_select_delivery=mysqli_fetch_array($result_select_delivery))
	{
		$select_dstatus = $row_select_delivery['dstatus'];

		$select_dstatus_code = "";

		// Deploy Status Information
		$sql_select_deploy_status = "SELECT * FROM deploy_status WHERE status = '$select_dstatus'";
		$result_select_deploy_status = mysqli_query($con, $sql_select_deploy_status)or die('Could not query database at this time');
		while($row_select_deploy_status=mysqli_fetch_array($result_select_deploy_status))
		{
			$select_dstatus_code = $row_select_deploy_status['code'];

			if($select_dstatus_code == 'N')
			{
				$select_delivery_cstatus = '1';
				$select_requestinstalldate = $row_select_delivery['requestinstalldate'];
				$select_requestinstalltime = $row_select_delivery['requestinstalltime'];
				$select_projectname        = $row_select_delivery['projectname'];
				$select_installtimezone    = $row_select_delivery['installtimezone'];

				if($select_requestinstalldate == ''){ $select_delivery_rstatus = "0"; } // required
				if($select_requestinstalltime == ''){ $select_delivery_rstatus = "0"; } // required
				if($select_projectname == '')       { $select_delivery_rstatus = "0"; } // required
				if($select_installtimezone == '')   { $select_delivery_rstatus = "0"; } // required
			}
		}
	}
?>