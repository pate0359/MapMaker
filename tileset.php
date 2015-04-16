<?php
///////////////////////////////////////////////////////////////////
// File: tileset.php
// Author: Sebatian Lenczewski
// Copyright: 2013, Sebastian Lenczewski, Algonquin College
// Desc: This file contains a class to manage an image of graphical
// 			tiles that will be used for drawing a map.
///////////////////////////////////////////////////////////////////

	class TileSet
	{
		///////////////////////
		// property declaration
		///////////////////////
		
		//Create an object to hold the image for this tile set
		private $imageData;
		// Holds the width and height of each tile in the image
		private $tileWidth;
		private $tileHeight;
		// Holds number of tiles in tileSet and map	
		private $width;
		private $height;
		
		private $tileCount;
		
		
		
		//////////////////////////
		// method declaration
		//////////////////////////
		
		// Create a set of 3 different overloaded Constructors 
		// to create a new TileSet with
		public function __construct()
    	{
			$this->tileWidth = 64;
			$this->tileHeight = 64;
			$this->imageData = imagecreatefrompng('img/tileset.png');
			$this->generateTileStats();
		}
		
		public function __construct1($image)
    	{
			$this->tileWidth = 64;
			$this->tileHeight = 64;
			$this->imageData = $image;
			$this->generateTileStats();
		}
		
		public function __construct2($image, $tileWidth, $tileHeight)
    	{
			$this->tileWidth =  $tileWidth;
			$this->tileHeight = $tileHeight;
			$this->imageData = $image;
			$this->generateTileStats();
		}
		

		
		// Define a Destructor to clean up any imageData 
		// memory still used.  This is VERY good practice.
		public function __destruct()
    	{
			if($this->imageData != NULL){
				imagedestroy($this->imageData);
			}
		}
		
		
		
		// Private function to let thi class refresh info
		// about the tile set.
		private function generateTileStats()
		{
			$this->width = imagesx($this->imageData) / $this->tileWidth;
			$this->height = imagesy($this->imageData) / $this->tileHeight;
			$this->tileCount = $this->width * $this->height;
		}
		
		
		// Make Getter and Setter and memory Clear 
		// accessor for the imageData
		public function getImageData() {
			return $this->imageData;
		}
		
		public function setImageData($image) {
			$this->clearImageData();
			$this->imageData = $image;
			$this->generateTileStats();
		}
		
		public function clearImageData() {
			imagedestroy($this->imageData);
			$this->imageData = NULL;
		}
		
		
		// Getter accessors for the width and height
		// of the image counted in number of tiles
		public function getWidth() {
			return $this->width;
		}
		
		public function getHeight() {
			return $this->height;
		}
		
		
		// Getters and Setters for the tileWidth and tileHeight
		public function getTileWidth() {
			return $this->tileWidth;
		}
		
		public function setTileWidth($number) {
			$this->tileWidth = $number;
		}
		
		public function getTileHeight() {
			return $this->tileHeight;
		}
		
		public function setTileHeight($number) {
			$this->tileHeight = $number;
		}
		
	}
	
	

?>

