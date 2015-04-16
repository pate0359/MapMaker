<?php
///////////////////////////////////////////////////////////////////
// File: index.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This is the main starting file used to launch the app.
// 			It includes the TileSet and MapOutput classes, the data
//			processing functions, and then runs this applications 
//			process that will generate the html $output string
///////////////////////////////////////////////////////////////////
	session_start();
	require ("tileset.php");
	require ("mapoutput.php");
	require ("data.php");
	require ("process.php");
?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Final - Map Maker</title>

<!-- -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="height=device-height, width=device-width, initial-scale = 1.0, user-scalable=no" />
<meta name ="viewport" content ="width = 640, initial-scale = 0.49, user-scalable = no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />


<link href="style.css" rel="stylesheet" type="text/css"/>
<!-- <link rel="icon" href="*.ico" /> -->

</head>

<body>
<?php echo $output; ?>
</body>
</html>