<?php

class Slideshow_Controller
{

	public function __construct($file, $request, $database)
	{
		$this->database = $database;

		$this->file_array = $file;
				
		$is_featured = 0;
		$is_active = 0;
		$link_to = "#";

		if(isset($request["is_featured"])) //change is_featured from "on" to 1 if it is set
		{
			$is_featured = 1;
		}		
		if(isset($request["is_active"])) //change the is active field from "on" to 1
		{
			$is_active = 1;
		}
		if(isset($request["link_to"])) //if there is a link, include the http in front of it
		{
			if(strpos($request['link_to'], 'http://') === FALSE)
			{
				$link_to='http://' . $request['link_to'];
			}
			else
			{
				$link_to = $request['link_to'];
			}
		}
		if(isset($request["expiration_time"])) //parse the time
		{
			$time_input = parse_time($request["expiration_time"]) . ":00";
		}
		
		
		$this->request = array(
				"is_featured" => $is_featured,
				"is_active" => $is_active,
				"title" => $request["title"],
				"expires" => date('Y-m-d H:i:s', strtotime($request["expiration_date"] . ' ' . $request["expiration_time"])),
				"image_alt_text" => $request["image_alt_text"],
				"link_to" => $link_to 
				);// compile the finished array


		if(isset($request["record_id"]))
		{
			$this->record_id = $request["record_id"];
		}
	}
	
	/**
	 * Performs all functions necessary to put a file onto the server.
	 * This includes putting a new record into the database and giving the file a permanent home on the server
	 */
	public function do_file_upload()
	{
		$result_of_upload = $this->_upload_file();
		
		if(is_array($result_of_upload)) //if it's an array then it is an error
		{
			return $result_of_upload;
		}
		
		$this->request["image_source"] = $result_of_upload;
		
		$record_id = $this->database->create($this->request);
	
	
		return $record_id;
	}
	
	/**
	 * This method updates a file including replacing the file and updating all of the fields to go along with it.
	 * @return Ambigous <multitype:, string, boolean>|boolean|multitype:
	 */
	public function do_file_update()
	{
		$result_of_upload = $this->_upload_file();
		
		if(is_array($result_of_upload)) //if it's an array then it is an error
		{
			return $result_of_upload;
		}
		
		$this->request["image_source"] = $result_of_upload; //otherwise what is returned is the new path
		
		$this->request["record_id"] = $this->record_id;
		$this->request["timestamp"] = date('Y-m-d H:i:s');
		$result_of_update = $this->database->update($this->request);
		
		if($result_of_update === TRUE)
		{
			return $result_of_update;
		}
		else
		{
			$errors = array();
			array_push($errors, $result_of_update);
			return $errors;
		}
	}
	/**
	 * Updates the fields of an image.  No new image is put onto the server with this method. 
	 */
	public function do_fields_update()
	{
		$previous_record =  $this->database->get_record($this->record_id);
		$this->request["image_source"] = $previous_record["image_source"];
		$this->request["record_id"] = $this->record_id;
		$this->request["timestamp"] = date('Y-m-d H:i:s');
		$result = $this->database->update($this->request);
		return $result;
	}
	
	/**
	 * Performs all necessary functions to upload a file including size verification, type verification.
	 * After completion the file will be moved to its permanent home on the server.
	 */
	private function _upload_file()
	{	
		$errors = array();	
		$uploader = new SlideshowUpload($this->file_array);
		$result_of_file_check = $uploader->check_file();
		
		if($result_of_file_check !== true) //return an error if the file was not of a valid type
		{
			array_push($errors, "Error, the file is of an invalid file type");
			return $errors;
		}
		
		$move_result = $uploader->move_to_slideshow_dir();
		if($move_result === false) //if we were unable to remove a file, return an error
		{
			array_push($errors, "Error, unable to move the file to its directory");
			return $errors;
		}
		
		return $move_result; //otherwise, return the new path to the file
	}
}
