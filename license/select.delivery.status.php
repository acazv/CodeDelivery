<?php

	// set Delivery Required Status
	require 'delivery.required.status.php';

	if($select_delivery_cstatus)
	{ 
		// Not Yet Deployed
		require 'product.select.status.php';

		require 'productdep.select.status.php';

		$select_required_status = $select_delivery_rstatus;

		if($select_required_status)
		{
			if($select_product_logon_status == "0")    {$select_delivery_rstatus = "0"; }
			if($select_productdep_logon_status == "0") {$select_delivery_rstatus = "0"; }
		}
	}
?>