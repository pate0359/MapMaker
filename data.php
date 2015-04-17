<?php
///////////////////////////////////////////////////////////////////
// File: data.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This file contains functions that help create, save and
// 			restore map arrays.  Save and restore from MySQL and 
//			SQLite and export the map data to an XML file.
///////////////////////////////////////////////////////////////////


	// Make a function that creates and returns a new blank map array
	function makeMapArray($width, $height, $tile_id)
	{
		// Make an array that will hold the map array data to be returned.
		$map = array();
		// Loop through the height and width as array positions
		for($y=0;$y<$height;$y++)
		{
			for($x=0;$x<$width;$x++)
			{
				// Set the value at this position to $tile_id
				$map[$y][$x]=$tile_id;
			}
		}
		//Return the array
		return $map;
	}
	
	
	
	// Make a function that saves the map array $tileMapArray to an SQLite
	// table with the name as $tableName
	function saveMapArray($tableName, $tileMapArray)
	{
//		foreach ($tileMapArray as $value) {
//			foreach($value as $item)
//			{echo "$item ";}
//			
//			echo "<br/>\n";
//		}
				
		//1.
		// Open a PDO connection to the SQLite file called final.sqlite3
		$db_file = new PDO('sqlite:final.sqlite3');
		
		
		// Check if the table exists by doing a select on the SQLite 
		// table called 'sqlite_master' to check if the table name 
		// exists.  Remember to fetch the data out of the results
		
		//$result = $db_file->query("SELECT COUNT(*) FROM sqlite_master WHERE name=".$tableName);
		$result = $db_file->query('SELECT COUNT(*) AS total FROM sqlite_master WHERE type=\'table\' AND name=\''.$tableName.'\'');
		
		//echo 'SELECT count(*) FROM sqlite_master WHERE type=\'table\' AND name=\''.$tableName.'\'';		
		$row = $result->fetch(PDO::FETCH_NUM);				
		if($row[0] == 0)
		{
			// If the results are 0 the table does not exist, and you must 
		// create the SQLite table.
			$db_file->exec('CREATE TABLE IF NOT EXISTS '.$tableName.'(position_id INTEGER PRIMARY KEY AUTOINCREMENT,position_row INT NOT NULL,position_col INT NOT NULL,tile_id INT NOT NULL)');
   
		}else{
			
			// Else if it does exist you must empty the SQLite table. (do not drop table)
			//Empty the table if there was data in it already
			$db_file->exec('DELETE FROM '.$tableName);
			$db_file->exec('VACUUM');

			//Reset Auto increment value
			$db_file->query('UPDATE SQLITE_SEQUENCE SET seq = 0 WHERE name =\''.$tableName.'\'');
		}
		
		
		// Generate one single SQLite query to insert all the $tileMapArray values to SQLite table 
		// by looping through the array called $tileMapArray. (Do some research on this.  
		// You need to use a SELECT and UNION to insert many records at once in SQLite)
		// Prepare INSERT UNION query string
		$qry='INSERT INTO '.$tableName.' SELECT NULL as "position_id",0 AS "position_row", 0 AS "position_col",'.$tileMapArray[0][0].' AS "tile_id"';
		
		for($y=0;$y<count($tileMapArray);$y++)
		{
			$col=$tileMapArray[$y];
			
			for($x=0;$x<count($col);$x++)
			{
				// Set the value at this position to $tile_id				
				$qry.=' UNION SELECT NULL,'.$y.', '.$x.', '.$tileMapArray[$y][$x].'';
			}
		}	
		
		// Exicute the query to insert the array data
		$db_file->exec($qry); 
		
		// Close connection to PDO object.
		$db_file = null;
		
	}
	
	
	
	// Make a function that loads data from the specified SQLite 
	// table as an array, and returns the array back to the application
	function loadMapArray($tableName)
	{
		
		//Make an empty array that will hold the map array data to be returned.
		$map = array();
		
		//2.
		// Check if the file 'final.sqlite3' exists on the server
		
		// MYSQL CONNECTION
	//Connect to the database by creating a new PDO object
	$db_host = "localhost";
	$db_name = "final";
	$db_user = "root";
	$db_password = "root";
	
		try{
				$pdo_link = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_password);
				$pdo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException $e)
		{
				echo $e->getMessage();
    	}
		
//		echo 'SELECT * FROM ';
			// If the db file exists, open a link to it
			
			// Run a select query to return the whole table
			
			// If the results are not empty, set the given array position to the value 'tile_id'.
			// Remember that each row in the table has the 'position_row' and 'position_col' 
			// stored telling you what array position to fill.
			
			// Else, if the reults are empty, set the $map array equal to the return 
			// of the function makeMapArray(10,10,0)
			
			// Close link to database


		// Else, if the SQLite file does not exist, set the $map array equal to the return
		// of the function makeMapArray(10,10,0) 

		
		// Return the $map array
		return $map;
		
	}
	
	
	
	// Create a function that takes map array data and inserts it into a 
	// given MySQL table in a database called final
	function uploadMapArray($tableName, $tileMapArray)
	{
		//3.
		// Connect to the database by creating a new PDO object
		$db_host = "localhost";
		$db_name = "final";
		$db_user = "root";
		$db_password = "root";
	
		try{
				$pdo_link = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_password);
				$pdo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException $e)
		{
				echo $e->getMessage();
    	}
		
		// Create a table IF NOT EXISTS for the given $tableName
		
		$pdo_link->query('CREATE TABLE IF NOT EXISTS '.$tableName.'(position_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,position_row INT(6) NOT NULL,position_col INT(6) NOT NULL,tile_id INT(6) NOT NULL)');
	
		
		// Run a truncate query on the table to remove any data
		$pdo_link->query('TRUNCATE '.$tableName);
		
		// Loop through the the $tileMapArray array to generate a single query to 
		$qry='INSERT INTO '.$tableName.' SELECT NULL as "position_id",0 AS "position_row", 0 AS "position_col",'.$tileMapArray[0][0].' AS "tile_id"';
		
		for($y=0;$y<count($tileMapArray);$y++)
		{
			$col=$tileMapArray[$y];
			
			for($x=0;$x<count($col);$x++)
			{
				// Set the value at this position to $tile_id				
				$qry.=' UNION SELECT NULL,'.$y.', '.$x.', '.$tileMapArray[$y][$x].'';
			}
		}

		// insert all the records from the $tileMapArray into the MySQL table.
		// Exicute the insert query on the MySQL table
		$pdo_link->query($qry); 		
		
		// Close the PDO link to the database
		$pdo_link = NULL;
	}
	
	
	
	// Create a function that selects the map data from the MySQL table 
	// and returns it as an array to the application.
	function downloadMapArray($tableName)
	{
		//4.
		//Make an empty array that will hold the map array data to be returned.
		$map = array();
		
		// Connect to the database by creating a new PDO object
		$db_host = "localhost";
		$db_name = "final";
		$db_user = "root";
		$db_password = "root";
	
		try{
				$pdo_link = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_password);
				$pdo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException $e)
		{
				echo $e->getMessage();
    	}
		
		// Use a select query to get all the records from the specified table
		$result = $pdo_link->query('SELECT * FROM '.$tableName);
		
		
		$count = $result->rowCount();

		if($count != 0)
		{
		// If the results are not empty, set the given array position to the value 'tile_id'.
		// Remember that each row in the table has the 'position_row' and 'position_col' 
		// stored telling you what array position to fill.
			foreach ($result as $value) {
				
				$pos_row= $value['position_row'];
				 $pos_col = $value['position_col'];
				 $tile_id = $value['tile_id'];
				
				$map[$pos_row][$pos_col]=$tile_id;
			}
		}else
		{
			// Else if the results are empty, then set the $map array equal to the return
			// value of the function call makeMapArray(10,10,0)
			$map = makeMapArray(10,10,0);
		}
		
		// Close the PDO link to the MySQL database
		$pdo_link = NULL;

		// Return the $map array
		return $map;
	}
	
	
	// Create a function to export the given array $tileMapArray to an XML file.  
	// The root node of this document should be named with the value in $tableName.
	// It should have 10 'row' nodes, each with 10 'col' nodes in them. 
	// You can save the column and row numbers in the the nodes as attributes.
	// (Research the format of XML node attributes to save the column and row numbers)
	function exportMapArray($tableName, $tileMapArray)
	{
		//5.
		// Create a string variable formated with the header of a valid XML document.
		$xmlString = "<?xml version='1.0' standalone='yes'?>\n";
		
		// concatinate the root node named with the $tableName value
		$xmlString .='<'.$tableName.'>';
				
		// Loop through the $tileMapArray, each row of the array being a set of 10 tiles,
		// and and each value of a given row being a specific tile.
			for($y=0;$y<count($tileMapArray);$y++)
			{
				// Loop through the $results, each $row being a record from our query results
				$xmlString .= "<row>\n";
				
				$col=$tileMapArray[$y];

				// Loop through each record to concatinate each value inside the <col> node
				for($x=0;$x<count($col);$x++)
				{
					// Loop through each record to print out each $key as the name of an XML tag
					$xmlString .= "<col>\n";
				  	$xmlString .=   "<position_row>"   . $y .   "</position_row>\n";
					$xmlString .=   "<position_col>"   . $x .   "</position_col>\n";
					$xmlString .=   "<tile_id>"   . $tileMapArray[$y][$x] .   "</tile_id>\n";
					$xmlString .= "</col>\n";
				}
				
				$xmlString .= "</row>\n";
			}	
		
		// Close the root node to end the XML structure
		$xmlString .='</'.$tableName.'>';
					
		// Use the string variable to generate a SimpleXMLElement
		$tileMapXml = new SimpleXMLElement($xmlString);
		
		// Save the SimpleXMLElement to a file with the value of $tableName as the name
		$tileMapXml->asXML('tileMapXml.xml');
	}	
?>
