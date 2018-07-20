<html>
  <head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	<link href="../css/main.css" rel="stylesheet" />
	<link href="../css/navbar.css" rel="stylesheet" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
<div id="wrapper">
<body>


<?php
	$con =  mysqli_connect('127.0.0.1', 'root', '') or die('Sorry, could not connect to server');
	mysqli_select_db($con, "codedelivery" ) or die(mysqli_error());

	$view         = $_GET['id'];
	$pkey         = $_GET['id1'];
	$pkey_store   = (isset($_GET['id2']) ? $_GET['id2'] : null);
	$pkey_feature = (isset($_GET['id3']) ? $_GET['id3'] : null);

	//Store thats selected info display
	$sql_store = "SELECT * FROM acc_store where snum = '$pkey_store'";
	$result_store = mysqli_query($con, $sql_store)or die('Could not query database at this time');
	if (!empty(mysqli_num_rows($result_store))) 
	{
		while($row_store=mysqli_fetch_array($result_store))
		{
			$id = $row_store['id'];
			$snum = $row_store['snum'];
			$name = $row_store['name'];
			$cnum = $row_store['cnum'];
			$ip = $row_store['ip'];
			$address = $row_store['address'];
		}
	}
	
	//package name selection
	$sql_package_name = "SELECT * FROM product where feature = '$pkey_feature'";
	$result_package_name = mysqli_query($con, $sql_package_name);		
	while($row_package_name=mysqli_fetch_array($result_package_name))
	{
		$package_name = $row_package_name['package'];
		$name_display = $row_package_name['name'];
	}
	
?>

<header>
	<a href="../home.php"><b>License Simulation</b></a>
</header>

<?php 
	// The user can get to this page either from the Store View or the Product view pages
	// Depending on where the user is coming from is what will be displayed here on the "where you are" feature
	// $view is the key in telling the program where the user is coming from and what should be displayed
	
	if($view =='store')
	{
		echo "<label><a href='store.php'>Store View</a> > Packages and Features > Logs</label>";
	}
	if($view =='product')
	{
		echo "<label><a href='product.php'>Product View</a> > Packages and Features > Logs</label>";
	}
?>

<ul>
<?php
	if($view =='store'){ echo "<br>$name<br><br>Store Id: $snum <br><br>"; } 
	
	echo "<li>";
	
	//the left navigation bar also changes depending on the 'view' of the user
	//this section of code displays the correct links and variables according to the users view	
	
	if($view == 'store')
	{
		echo "<a href='store.display.php?id=".$view."&id1=".$pkey_store."'>Packages and Features</a> ";
	}
	if($view == 'product')
	{
		echo "<a href='product.display.php?id=".$view."&id1=".$pkey_feature."'>Packages and Features</a> ";
	}
	echo "</li>";
	echo "<li><a class='active' href=''>Logs</a></li>";
?>
</ul>


<br>
<!-- Page Content -->
<div style="margin-left:20% ;height:550px; width: 1100px; ">

<br>
<?php //label
	echo "<label>Feature:</label> $pkey_feature &nbsp;
			<label>Name:</label> $name_display 
			<label>Deploy#:</label> $pkey <br>"; ?>
<!-- Directory container -->
<div style="height:400px;width:1100px; border:1px solid #ccc;
	font:16px/20px Georgia, Garamond, Serif; overflow:auto;">
<table class="table ">


	<tr>
	<th>Filename</th>
	<th>Size <small>(bytes)</small></th>
	<th>Date Modified</th>
<?php
	//back button on table depending on where you came from in view will send you back to store or product
	if($view == 'store')
	{
		echo "<th><button style='background-color: #708090; color:white;'><a href='store.display.php?id=".$view."&id1=".$pkey_store."'>Back</a></button></th>" ; 
	}
	if($view == 'product')
	{
		echo "<th><button style='background-color: #708090; color:white;'><a href='product.display.php?id=".$view."&id1=".$pkey_feature."'>Back</a></button></th>" ; 
	}


?>
	</tr>


<?php
	// Opens directory

	$path = "../logs/$pkey/";
	$myDirectory=opendir($path);

	// Gets each entry
	while($entryName=readdir($myDirectory)) 
	{
		$dirArray[]=$entryName;
	}

	// Finds extensions of files
	function findexts ($filename) 
	{
		$filename=strtolower($filename);
		$exts=explode("[/\\.]", $filename);
		$n=count($exts)-1;
		$exts=$exts[$n];
		return $exts;
	}

	// Closes directory
	closedir($myDirectory);

	// Counts elements in array
	$indexCount=count($dirArray);

	// Sorts files
	sort($dirArray);

	// Loops through the array of files
	for($index=0; $index < $indexCount; $index++) 
	{

		// Allows ./?hidden to show hidden files
		if($_SERVER['QUERY_STRING']=="hidden")
		{
			$hide="";
			$ahref="./";
			$atext="Hide";
		} else{
			$hide=".";
			$ahref="./?hidden";
			$atext="Show";
		}

		if(substr("$dirArray[$index]", 0, 1) != $hide) 
		{

			// Gets File Names
			$name=$dirArray[$index];
			$namehref=$dirArray[$index];

			// Gets Extensions 
			$extn=findexts($dirArray[$index]); 

			// Gets file size 
			$size=number_format(filesize($path.$dirArray[$index]));

			// Gets Date Modified Data
			date_default_timezone_set('EST');
			$modtime=date("M j Y g:i A", filemtime($path.$dirArray[$index]));
			$timekey=date("YmdHis", filemtime($path.$dirArray[$index]));

			// Prettifies File Types, add more to suit your needs.
			switch ($extn)
			{
				case "png": $extn="PNG Image"; break;
				case "jpg": $extn="JPEG Image"; break;
				case "svg": $extn="SVG Image"; break;
				case "gif": $extn="GIF Image"; break;
				case "ico": $extn="Windows Icon"; break;

				case "txt": $extn="Text File"; break;
				case "log": $extn="Log File"; break;
				case "htm": $extn="HTML File"; break;
				case "php": $extn="PHP Script"; break;
				case "js": $extn="Javascript"; break;
				case "css": $extn="Stylesheet"; break;
				case "pdf": $extn="PDF Document"; break;

				case "zip": $extn="ZIP Archive"; break;
				case "bak": $extn="Backup File"; break;
				case "$name": $extn=""; break;

				default: $extn=strtoupper($extn)." File"; break;
			}

			// Separates directories
			if(is_dir($dirArray[$index])) 
			{
				$extn="&lt;Directory&gt;"; 
				$size="&lt;Directory&gt;"; 
				$class="dir";
			} else {
				$class="file";
			}

			// Cleans up . and .. directories 
			if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;";}
			if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}

			// Print 'em
			print("
			<tr class='$class'>
			<td><a href='$path/$namehref'>$name</a></td>
			<td>$size</td>
			<td sorttable_customkey='$timekey'>$modtime</td>
			<td></td>
			</tr>");
		}
	}
?>

</table>
    
</div>
  
</body>

</html>