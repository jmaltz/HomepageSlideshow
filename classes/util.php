<?php

function get_value_for_input($array, $field_to_check, $default)
{
	return isset($array[$field_to_check]) ? 'value="' . $array[$field_to_check] . '"' : 'placeholder="'. $default . '"';
}

function parse_time($time_string)
{
	//become good at regex and split the string
	$split_array = split(" ", $time_string);
	
	$suffix = $split_array[1];
	
	if(strcasecmp($suffix, "AM") === 0)
	{
		$split_time = split(":", $split_array[0]);
		if(intval($split_time[0]) === 12) //if it is 12 AM, knock off 12 hours to make it into 24 hour time.
		{
			return "00:" . $split_time[1];
		}
		return $split_array[0]; //otherwise, just return the time.
	}
	else
	{
		$split_time = split(":", $split_array[0]);
		$hours_as_int = intval($split_time[0]);
		if($hours_as_int !== 12)
		{
			$hours_as_int += 12;
		}
		return $hours_as_int . ":" . $split_time[1];
	}
}

function datetime_to_am_pm($datetime)
{
	$split_date = split(":", $datetime);
	$hours_as_int = intval($split_date[0]);
	if($hours_as_int < 12)
	{
		$suffix = "AM";
		if($hours_as_int === 0)
		{
			$hours_as_int = 12;	
		}
		return $hours_as_int . ":" . $split_date[1] . " " . $suffix;
	}
	else
	{
		$suffix = "PM";
		if($hours_as_int !== 12)
		{
			$hours_as_int -= 12;
		}
		return $hours_as_int . ":" . $split_date[1] . " " . $suffix;
	}
}

function convert_date_to_datetime($old_date)
{
	$split_date = split("-", $old_date);
	return $split_date[2] . "-" . $split_date[0] . "-" . $split_date[1];
}

function convert_datetime_to_date($datetime)
{
	$split_date = split("-", $datetime);
	return $split_date[1] . "-" . $split_date[2] . "-" . $split_date[0];
}

?>
