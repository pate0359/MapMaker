<?php
///////////////////////////////////////////////////////////////////
// File: process.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This file contains PHP code that will process  user input
// 			and the output of the image forms and links in the app
///////////////////////////////////////////////////////////////////

	//session_start();
	//session_destroy();
	$newMap = new MapOutput(10,10);
	
	// Check if theis is rhe users first time running this app
	if(!isset($_SESSION['map1']))
	{
		// if not, make a new map 
		$_SESSION['map1'] = makeMapArray(10,10,0);
	}
	
	// Check $_POST values from buttons and call correct function
	if(isset($_POST['submit'])){
		if($_POST['submit'] == 'Upload'){
			uploadMapArray('map1', $_SESSION['map1']);
		}
		else if($_POST['submit'] == 'Download'){
			$_SESSION['map1'] = downloadMapArray('map1');
		}
		else if($_POST['submit'] == 'Save'){
			saveMapArray('map1', $_SESSION['map1']);
		}
		else if($_POST['submit'] == 'Load'){
			$_SESSION['map1'] = loadMapArray('map1');
		}
		else if($_POST['submit'] == 'Export'){
			exportMapArray('map1', $_SESSION['map1']);
		
		}else if($_POST['submit'] == 'Import'){
			$_SESSION['map1'] = importMapArray('map1');
		
		}else if($_POST['submit'] == 'New'){
			$_SESSION['map1'] = makeMapArray(10,10,0);
		}
	}
	
	// Check if the user has clicked the same spot more than once
	// and change what tile is in that spot
	if(isset($_GET['click'])){
		$x = intval ( $_GET['locX'] );
		$y = intval ( $_GET['locY'] );
		
		$_SESSION['map1'][$y][$x] += 1;
		
		if($_SESSION['map1'][$y][$x] > 15){
			$_SESSION['map1'][$y][$x] = 0;
		}
		
	}

	// Create the map image data with any new changes
	$newMap->renderMap($_SESSION['map1']);
	
	// Save the image to the output.png file
	$newMap->saveMapImage("output");
	
	
	// Create the image tag to output the image to the user
	$output = '<img src="img/output.png" width="' . $newMap->getMapWidth() . '" height="' . $newMap->getMapHeight() . '" alt="generated image"  usemap="#linkmap" />';
	$output .= "\n";
	
	// Append the html image link map to create the links over each tile
	$output .= $newMap->makeLinkMap();
	
	// Append the forms with the different form buttons to get user input
	$output .='	<form method="post" action="index.php" name="" id="">
					<input type="submit" name="submit" value="Download"/>
					<input type="submit" name="submit" value="Upload"/>
				</form>
				
				<form method="post" action="index.php" name="" id="">
					<input type="submit" name="submit" value="Load"/>
					<input type="submit" name="submit" value="Save"/>
				</form>
				
				<form method="post" action="index.php" name="" id="">
					<input type="submit" name="submit" value="New"/>
					<input type="submit" name="submit" value="Export"/>
				</form>
				';
	
?>

