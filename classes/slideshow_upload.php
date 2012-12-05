<?php

class SlideshowUpload{
	
	private $base_path;
	
	public function __construct($file)
	{	
		$this->base_path = realpath(__DIR__) . '/../uploads/';
		$this->relative_path = 'uploads/';

		$this->file_handler = $file;
	}
	
	public function check_file_size()
	{
		return $this->file_handler['size'] <= 5242880;
	}
	
	public function is_valid_extension()
	{
		$extension = pathinfo($this->file_handler['name'], PATHINFO_EXTENSION);
		$extension = strtolower($extension);
		return strcmp($extension, "gif") == 0  || strcmp($extension, "jpeg") == 0 || strcmp($extension, "jpg") == 0 || strcmp($extension, "png") == 0;  
	}
	
	public function move_to_slideshow_dir()
	{
		//trim off the extension
		
		$file_name = substr($this->file_handler["name"], 0, strrpos($this->file_handler["name"], "."));
		$file_name = $file_name . "-" . time();
		$file_name = $file_name . "." . pathinfo($this->file_handler['name'], PATHINFO_EXTENSION);
		$file_name = strtolower($file_name);
		
		$new_file_name = $this->base_path . $file_name;

		
		if(move_uploaded_file($this->file_handler["tmp_name"], $new_file_name))
			return $this->relative_path . $file_name;
		else
			return false;
	}
	
	public function check_file()
	{
		$errors = array();
		if(!$this->check_file_size())
			array_push($errors, "The file size is too large, file cannot exceed 5 MB");
		if(!$this->is_valid_extension())
			array_push($errors, "The file extension is invalid, valid file extensions are .gif, .jpg, .jpeg, or .png");
		
		return count($errors == 0) ? true : $errors;
	}
	
	public static function list_all_files()
	{
		$all_files = array();
		$directory = opendir(slideshow_crud::$base_path);
		while(false !== ($entry = readdir($directory)))
		{
			$fileHandle = fopen(slideshow_crud::$base_path . $entry, "rx");
			array_push($all_files, array("file" => $fileHandle, 
										 "name" => $entry));
		}
		
		return $all_files;
	}
	
	public function delete_file($file_name)
	{
		$file_name_to_remove = $base_path . $file_name;
		return unlink($file_name_to_remove);
	}
	
	public function move_file($old_file_name, $new_file_name)
	{
		if(strpos($old_file_name, "/images/slideshow_images") === 0) //if the path given isn't a local path then add on the prefix
		{
			$old_file_name = slideshow_crud::$base_path . $old_file_name;
		}

		if(strpos($new_file_name, "/images/slideshow_images") === 0) //if the path given isn't a local path then add on the prefix
		{
			$new_file_name = slideshow_crud::$base_path . strtolower($new_file_name) . "-" . time();
		}
		
		if(!file_exists($old_file_name) || file_exists($new_file_name)) //if the old file doesn't exist return false
			return false;
		
		return rename($old_file_name, $new_file_name);
	}
}

?>
