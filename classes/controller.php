<?php

class Controller {

	
	static $upload_errors = array(
	    UPLOAD_ERR_OK        => 'No errors.',
	    UPLOAD_ERR_INI_SIZE    => 'Larger than upload_max_filesize.',
	    UPLOAD_ERR_FORM_SIZE    => 'Larger than form MAX_FILE_SIZE.',
	    UPLOAD_ERR_PARTIAL    => 'Partial upload.',
	    UPLOAD_ERR_NO_FILE        => 'No file.',
	    UPLOAD_ERR_NO_TMP_DIR    => 'No temporary directory.',
	    UPLOAD_ERR_CANT_WRITE    => 'Can\'t write to disk.',
	    UPLOAD_ERR_EXTENSION     => 'File upload stopped by extension.'
	  ); 

	private $db;
	private $alert;

	public function __construct($params)
	{
		foreach($params as $key => $value)
			$this->$key = $value;

		$this->db = new SlideshowDatabase($this->config['database']);
	}
	
	public function route($params)
	{
		if(!empty($params['s1']))
		{
			$method_name = $params['s1'];
			unset($params['s1']);

			if(method_exists($this, $method_name))
			{
				call_user_func(array($this, $method_name), $params);
			}
		}
		else
		{
			self::homepage();
		}
	}

	public function homepage()
	{
		// initialize the slideshow model class
		$view = new SlideshowModel();

		// fetch the available database records
		$records = $this->db->get();

		// print out the display records view
		echo $view->display_records($records);
	}

	public function edit($params)
	{
		// fetch the specified database record		
		$record = $this->db->get_record($params['s2']);
	
		if($record == FALSE)
		{
			header('Location: ' . $GLOBALS['base_url'] . '/upload/');
		}
	
		if($_SERVER['REQUEST_METHOD'] === 'POST') //if the request was posted then they are trying to upload
		{
			$files_array = $_FILES['upload_file'];
		
			$controller = new Slideshow_Controller($files_array, $_REQUEST, $this->db);
			$result = TRUE;
			if($files_array['error'] === 4) //if a file wasn't uploaded, just update does fields
			{ 
				$result = $controller->do_fields_update();
			}
			else 
			{
				$result = $controller->do_file_update();
			}
		
			$view = new SlideshowModel();
			
			if($result === TRUE) //if the result is true, we just uploaded successfully
			{
				$records = $this->db->get();
				header('Location: ' . $GLOBALS['base_url']);
			}
			else if(is_array($result)) //if its an array print all errors
			{
				$error = '';
				foreach($result as $key => $value)
				{
					$error .= $value . '  ';
				}
				echo $view->display_record($this->db->get_record($params['s2']), $error);
			}
			else
			{
				echo $view->display_record($this->db->get_record($params['s2']), 'There was an unknown error, please try again');		
			}
		}
		else
		{
			// initialize the slideshow model class
			$view = new SlideshowModel();

			// print out the edit record view
			echo $view->display_record($record, NULL);
		}
	}

	public function upload($params)
	{
		$view = new SlideshowModel();
		if($_SERVER['REQUEST_METHOD'] === 'POST') //if we posted here then do the file upload
		{
			$files_array = $_FILES['upload_file'];
			
			
			if($files_array['error'] !== 0)
			{
				echo $view->display_record(array(), 'There was an error uploading your file ' . Controller::$upload_errors[$files_array['error']]);
			}
			else
			{
				$controller = new Slideshow_Controller($files_array, $_REQUEST, $this->db);

				$result = $controller->do_file_upload(); //the only option is to upload a file
				if(is_array($result)) //if the result of the upload is an array, there was an error
				{
					$error = '';
					foreach($result as $key => $value)
					{
						$error = $error . $value; //append the error messages
					}
					echo $view->display_record(array(), $error);	
				}
				else if($result === FALSE)
				{
					echo $view->display_record(array(), 'There was an error inserting into our database, please try again');
				}
				else
				{
					header('Location: ' . $GLOBALS['base_url']); //note that we're using global scope
				}
			}
		}
		else //otherwise just echo the upload form
		{
			echo $view->display_record(array(), NULL);
		}

	}
}
