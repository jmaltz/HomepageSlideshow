<?php

class SlideshowDatabase
{
	private $host;
	private $dbname;
	private $username;
	private $password;
	private $db;

	public function __construct($params)
	{
		// define class variables
		foreach($params as $key => $value)
			$this->$key = $value;

		// establish database connection
		$this->_connect();
		
		// create slideshow table
		$this->_create_table();
	}

	private function _connect()
	{
		try
		{
			$this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (PDOException $e)
		{
			error_log(__FUNCTION__ .': ' . $e->getMessage());
		}
	}

	private function _create_table()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `homepage_slideshow` (".
       			"`record_id` int(11) AUTO_INCREMENT NOT NULL, ".
       			"`title` varchar(100) NOT NULL, ".
			"`image_source` varchar(255) NOT NULL, ".
			"`image_alt_text` varchar(255) NOT NULL, ".
			"`link_to` varchar(255) NOT NULL, ".
			"`is_active` int(1) NOT NULL, ".
			"`is_featured` int(1) NOT NULL, ".
			"`timestamp` datetime NOT NULL, ".
			"`expires` datetime NOT NULL, ".
			"PRIMARY KEY (`record_id`) ".
			") CHARACTER SET utf8 COLLATE utf8_general_ci";

		try
		{
			$this->db->exec($sql);
		}
		catch(PDOException $e)
		{
			error_log(__FUNCTION__ .': '. $e->getMessage());
		}
	}
	
	public function create($request)
	{
		if(!empty($request['title']) && !empty($request['image_source']) && !empty($request['image_alt_text'])) //make sure necessary fields are set
		{
			$request['timestamp'] = date("Y-m-d H:i:s");

			$sql = "INSERT INTO homepage_slideshow(".
				"title, image_source, image_alt_text, link_to, is_active, is_featured, timestamp, expires) VALUES (".
				":title, :image_source, :image_alt_text, :link_to, :is_active, :is_featured, :timestamp, :expires)";

			$query = $this->db->prepare($sql);
		
			try
			{
				$query->execute($request);
				return $this->db->lastInsertId();
			}
			catch(PDOException $e) //log any errors and return false
			{
				error_log(__FUNCTION__ .': '. $e->getMessage());
			}
		}
		return FALSE;
	}

	public function update($record)
	{
		if(!empty($record['title']) && !empty($record['image_source']) && !empty($record['image_alt_text']))
		{
			$sql = "UPDATE homepage_slideshow SET link_to= :link_to, image_alt_text = :image_alt_text, ". 								"expires = :expires, is_featured = :is_featured, timestamp = :timestamp, ".
				"title = :title, image_source = :image_source, is_active = :is_active ".
				"WHERE record_id = :record_id";

			try
			{
				$query = $this->db->prepare($sql);
				$query->execute($record);
					
				return TRUE;
			}
			catch(PDOException $e)
			{

				error_log(__FUNCTION__ .': '. $e->getMessage());
			}
		}

		return FALSE;
	}
	
	public function delete($record_id)
	{
		try
		{
			$query = $this->db->prepare("DELETE FROM homepage_slideshow WHERE record_id= :record_id");
			$query->bindParam(":record_id", $record_id);
			$query->execute();

			return TRUE;
		}
		catch(PDOException $e)
		{
			error_log(__FUNCTION__ .': '. $e->getMessage());
		}

		return FALSE;
	}
	
	public function get()
	{
		try 
		{
			$query = $this->db->prepare("SELECT * FROM homepage_slideshow ORDER BY record_id DESC");
			$query->execute();

                        return $query->fetchAll();
                }
                catch(PDOException $e)
                {
                	error_log(__FUNCTION__ .': '. $e->getMessage());        
                }

		return FALSE;
	}

	public function get_record($record_id)
	{
		try
		{		
			$query = $this->db->prepare("SELECT * FROM homepage_slideshow WHERE record_id = :record_id");
			$query->bindParam(":record_id", $record_id);
			$query->execute();

			return $query->fetch();
		}
		catch(PDOException $e)
                {
                	error_log(__FUNCTION__ .': '. $e->getMessage());        
                }

		return FALSE;
	}

	public function get_visible()
    {
        $expires = date("Y-m-d H:i:s");
		try
        {
            // first try to get all featured images
			$query = $this->db->prepare("Select * FROM homepage_slideshow WHERE expires >= :expires AND is_active = 1 AND is_featured = 1");
            $query->bindParam(":expires", $expires);
			$query->execute();

            $results = $query->fetchAll();
            // if there are any featured, just return them
            if(count($results) > 0)
            {
                return $results;
            }
            
            $query = $this->db->prepare("Select * FROM homepage_slideshow WHERE expires >= :expires AND is_active = 1");
            $query->bindParam(":expires", $expires);
			$query->execute();

			return $query->fetchAll();
		}
		catch(PDOException $e)
                {
                	error_log(__FUNCTION__ .': '. $e->getMessage());        
                }

		return FALSE;
	}
}

?>
