<?php
require_once 'util.php';

class SlideshowModel {

	public function display_records($records)
	{
		for($i = 0; $i < count($records); $i++)
		{
			if($records[$i]["expires"] != NULL)
			{
				$records[$i]["expires"] = str_replace(" ", "T", $records[$i]["expires"]); //format it for the client side
			}
		}		

		$html = '<div id="image-list">'.
				'<div class="row">'.
					'<div id="search-bar" class="span12">'.
						'<span class="search-category" id="search-all">'.
							'All'.
						'</span>'.
						' | '.
						'<span class="search-category" id="search-featured">'.
							'Featured'.
						'</span>'.
						' | '.	
						'<span class="search-category" id="search-active">'.
							'Active'.
						'</span>'.
						'<input type="text" class="pull-right span2 search-query" id="title-search" placeholder="Search title"/>'.
					'</div>'.
				'</div>'.
				'<div class="row">' .
					'<ul id="images">'.	
					'</ul>'.
				'</div>'.
			'</div>';
		include('templates/image-template.temp');
		$html .= '<script type="text/javascript" src="'. $GLOBALS['base_url'] . '/js/homepage.js"></script>'.
			 '<script type="text/javascript">'.
				'model.load(\''. json_encode($records) . '\');'.
			 '</script>';	

		return $html;
	}

	public function display_record($record, $error)
	{

		$html = '<div>';
		if($error)
		{
			$html .= '<div class="alert alert-error">' . $error. '</div>';	
		}

		$html .= '<form action="" method="post" class = "form-horizontal" enctype="multipart/form-data" id="slideshow_upload_image">' .
				'<div class="control-group">' .
				'<label class="control-label" for="title">Image Title: </label>' .
				'<div class="controls" id="image_title_controls">' .
				'<input type="text" name="title" id="image_title" class="slideshow-submission-field" ' . get_value_for_input($record, "title", "Image Title") . '/>' .
				 '</div>' .
			 	 '</div>' .
				'<div class="control-group">' .
				'<label class="control-label" for="image_alt_text">Alt Text: </label>' .
				'<div class="controls" id="image_alt_text_controls">' .
				' <input type="text" id="image_alt_text" class="slideshow-submission-field" name="image_alt_text" ' . get_value_for_input($record, "image_alt_text", "Image Alt Text") . '/>' .
				'</div>' .
				'</div>' .
				'<div class="control-group">' .
				' <label class="control-label" for="image_href">Link Location:</label> ' .
				'<div class="controls">' .
				'<input type="text" id="image_href" name="link_to"';  
		
		if(isset($record["link_to"])) //if a link value is passed in, show it
		{
			if(strpos($record["link_to"], "http://") !== FALSE) //if there is an http:// strip it
			{
				
				$html .= 'value="' . substr($record["link_to"], 7) . '"';
			}
			else
			{
				$html .= 'value="' . $record["link_to"] . '"';
			}	
		}
 
		$html .= 	'/>' .	
				'</div>' .
				'</div>' .
				'<div class="control-group">' .
				'<div class="controls">' .
				'<label class="checkbox">Featured <input type="checkbox" name="is_featured"'; 
		
		if(isset($record["is_featured"]) && strcmp($record["is_featured"], "1") === 0)
		{
			$html .= 'checked="checked"';	
		}
		
		$html .= 	'/>' .
				'</label> ' .
				'<label class="checkbox">Active <input type="checkbox" name="is_active"'; 
		
		if(isset($record["is_active"]) && strcmp($record["is_active"], "1") === 0)
		{
			$html .= 'checked="checked"';	
		}
		
		$html .= 	'/>' .
				'</label>' .
				'</div>' .
				'</div>' .
				'<div class="control-group">' .
				'<label class="control-label">Expiration Date </label>' .
				'<div class="controls" id="expiration_date_controls">' .
				'<input id="datepicker" type="text" name="expiration_date" class="slideshow-submission-field"';
		
		if(isset($record["expires"]))
		{

			$html .= 'value="' . date('Y-m-d', strtotime($record['expires'])) . '"';
		}			
		$html .= 	 '/>' .
				'</div>' .
				'</div>' .
				'<div class="control-group">' .
				'<label class="control-label">Time</label>' .
				'<div class="controls" id="expiration_time_controls">'. 
				'<input id="timepicker" type="text" name="expiration_time" class="slideshow-submission-field"';
		

		if(isset($record["expires"]))
		{
				
			$html .= 'value="' . date('g:i A', strtotime($record['expires'])) . '"';
		}
					
		$html .=	'/>' .
				'</div>' .
				'</div>' .
				'<div class="control-group">' .
				'<label class="control-label">File Name </label>' .
				'<div class="controls">' .
				'<input type="file" name="upload_file" />' .
				'</div>' .
				'</div>' .
				'<input type="hidden"';
		
		if(isset($record["record_id"]))
		{
			$html .= 'value="' . $record["record_id"] . '"';
		}
		 
		$html .= 'name="record_id"/>' .
				'<input type="submit" class="btn" name="submit"';
		if(isset($record["record_id"]))
		{
			$html .= 'value="Edit"';
		}
		else
		{
			$html .= 'value="Upload"';
		}
		$html .=	 '/>' .
				'</form>' .
		
	'<script type="text/javascript" src="' .  $GLOBALS["base_url"] . '/js/upload.js"></script>'
	;
	
	return $html;
	}
}
?>
