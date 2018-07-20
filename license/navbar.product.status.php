<?php
   // set Product.view Navbar Delivery Status
 
   $navbar_delivery_rstatus   = '1';  // default
   $navbar_delivery_rstatuscs = "0";  // default
   $navbar_delivery_rstatuscd = "0";  // default
   $navbar_delivery_cstatus   = '0';  // default

   $sql_navbar_feature_check = "SELECT * FROM store_product WHERE feature = '$navbar_feature' ORDER BY snum";
   $result_navbar_feature_check = mysqli_query($con, $sql_navbar_feature_check);
   while($row_navbar_feature_check=mysqli_fetch_array($result_navbar_feature_check))
   {
		$navbar_snum         = $row_navbar_feature_check['snum'];
		$select_dnum         = $row_navbar_feature_check['dnum'];
		$select_featuredep   = '0';

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