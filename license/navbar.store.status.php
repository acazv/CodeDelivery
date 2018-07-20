<?php
   // set Navbar Delivery Status
 
   $navbar_delivery_rstatus   = '1';  // default
   $navbar_delivery_rstatuscs = "0";  // default
   $navbar_delivery_rstatuscd = "0";  // default
   $navbar_delivery_cstatus   = '0';  // default
      
   // get all Delivery records
   $sql_navbar_delivery = "SELECT * FROM store_delivery where snum = '$navbar_snum'";
   $result_navbar_delivery = mysqli_query($con, $sql_navbar_delivery)or die('Could not query database at this time');
   while($row_navbar_delivery=mysqli_fetch_array($result_navbar_delivery))
   {
		$select_dnum = $row_navbar_delivery['dnum'];
		$select_featuredep = '';

		// set Delivery Required Status
		require 'select.delivery.status.php';

		if($select_delivery_cstatus)
		{
			$navbar_delivery_cstatus = $navbar_delivery_cstatus + $select_delivery_cstatus; 

			if(empty($select_required_status))
			{ 
				$navbar_delivery_rstatuscs = $navbar_delivery_rstatuscs + '1';
			}

			if(empty($select_delivery_rstatus))
			{
				$navbar_delivery_rstatus = "0";
			} else {
				$navbar_delivery_rstatuscd = $navbar_delivery_rstatuscd + '1';
			}
		}
	}
?>