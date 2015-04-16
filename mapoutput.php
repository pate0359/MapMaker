<?php
///////////////////////////////////////////////////////////////////
// File: mapoutput.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This file contains a class to manage the tile set, the  
// 			creation of the output.png file, and buttons.
///////////////////////////////////////////////////////////////////
	
	
	class MapOutput
	{
		///////////////////////
		// property declaration
		///////////////////////
		
		// The screen dimention used to make the ouput image
		private $mapWidth; // = 640
		private $mapHeight; // = 896
		// TileSet object to hold informtion about the tileSet we load
		private $tileSet;
		// Image data for the output image
		private $image;
 		
		private $selectorImage;
		
		public $selX = 0;
		public $selY = 0;
		
		public $tileCountHorz = 10;
		public $tileCountVert = 14;
		
		
		//////////////////////////
		// method declaration
		//////////////////////////
		
		// Define a constructor to create our set our
		// tileSet and output image dimentions and image data
		public function __construct($width, $height)
		{
			$this->tileCountHorz = $width;
			$this->tileCountVert = $height;
			
			$this->tileSet = new TileSet(imagecreatefrompng('img/tileset.png'));
			$this->selectorImage = imagecreatefrompng('img/selector.png');
			$this->mapWidth = $this->tileSet->getTileWidth() * $this->tileCountHorz; // = 640
			$this->mapHeight = $this->tileSet->getTileHeight() * $this->tileCountVert; // = 896
			$this->image = imagecreatetruecolor($this->mapWidth, $this->mapHeight) or die("Can't initialize GD image stream");
			
		}
		
		
		// Create a custom Destructor to manage our memory deletion
		public function __destruct()
		{
			$this->tileSet->clearImageData();
			imagedestroy($this->selectorImage);
			imagedestroy($this->image);
		}
		
		
		// Create Getters for accessing the map width and height
		public function getMapWidth() {
			return $this->mapWidth;
		}
		
		public function setMapWidth($number) {
			$this->mapWidth = $number;
		}
		
		public function getMapHeight() {
			return $this->mapHeight;
		}
		
		public function setMapHeight($number) {
			$this->mapHeight = $number;
		}
		
		
		
		// Create a function that takes a 2 dimentional map array
		// and parses the data to make the output image
		public function renderMap($map)
		{
			// Update the selected location if the $_GET array
			// captured any user input
			if(isset($_GET['locX']) && isset($_GET['locY']))
			{
				$this->selX = $_GET['locX'];
				$this->selY = $_GET['locY'];
			}
			
			// Keep track of wht position in the $map we are
			// on as we loop through with the foreach loops.  
			// $x is the column and $y is the row we are on.
			$x = 0;
			$y = 0;
			foreach($map as $row)
			{
				foreach($row as $tileNumber)
				{
					
					if($map[$y][$x] >=0 )
					{
						// Draw the specified tile in this location of the image
						imagecopy(	$this->image, $this->tileSet->getImageData(), 
								$x*$this->tileSet->getTileWidth(), $y*$this->tileSet->getTileHeight(), 
								$map[$y][$x] % $this->tileSet->getWidth() * $this->tileSet->getTileWidth(), floor($map[$y][$x] / $this->tileSet->getHeight()) * $this->tileSet->getTileHeight(), 
								$this->tileSet->getTileWidth(), $this->tileSet->getTileHeight());
						
						// Draw the selector graphics if this is the selected location
						if($x == $this->selX && $y == $this->selY )
						{
							imagecopy(	$this->image, $this->selectorImage, 
								$x*$this->tileSet->getTileWidth(), $y*$this->tileSet->getTileHeight(), 
								0, 0, 
								64, 64);
						}
					}
					// Incriment what coloumn we are on
					$x ++;
				}
				// Incriment what row we are on
				$y ++;
				// ...and start back on the first column
				$x = 0;
			}
			//$this->saveMapImage();
		}
		
		
		// This function should output the image right to 
		// the users browser/
		public function showMapImage() {
			header ('Content-Type: image/png');
			imagepng($this->image);
		}
		
		
		// Function to save the map to a file
		public function saveMapImage($name) {
			imagepng($this->image, 'img/' . $name .'.png');
			return "";
		}
		
		
		// Make a function that returns an html formated string
		// that creates a link-map over each tile in the image
		public function makeLinkMap()
		{
			$buf = '<map name="linkmap" id="linkmap">';
			$buf .= "\n";
			
			
			for($y=0; $y< $this->mapHeight / $this->tileSet->getTileHeight(); $y ++)
			{
				for($x=0; $x< $this->mapWidth / $this->tileSet->getTileWidth(); $x ++)
				{
					$buf .= "\t\t";
					$buf .= '<area shape="rect" coords="';
					$buf .= $x*$this->tileSet->getTileWidth() . ",";
					$buf .= $y*$this->tileSet->getTileHeight() . ",";
					$buf .= $this->tileSet->getTileWidth()+$x*$this->tileSet->getTileWidth() . ",";
					$buf .=  $this->tileSet->getTileHeight()+$y*$this->tileSet->getTileHeight();
					$buf .= '" href="index.php';
					$buf .= "?locX=" . $x;
					$buf .= "&locY=" . $y;
					if(isset($_GET['locX']) && isset($_GET['locY']))
					{
						if( $x == $_GET['locX'] && $y == $_GET['locY'] ){
							$buf .= "&click=1";
						}
					}
					$buf .= '" />';
					$buf .= "\n";
				}
			}
			
			$buf .= "\t</map>";
			$buf .= "\n";
			
			return $buf;
		}
		
	}
	

?>

