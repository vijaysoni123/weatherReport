<?php
require_once("dbconfig.php");
ini_set('max_input_vars','30000000000');
class USER extends dbconfig 
{
	public static $resultData;
	function __construct() 
	{
		parent::__construct();
	}
//************************************* FUNCTION TO GET WEATHER REPORT *******************************************************************************//
	public static function getWeatherReport($cityName,$startDate,$endDate,$apiKey,$format,$hourlyRate) 
	{
		$requestedUrl = 'http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key='.$apiKey.'&q='.$cityName.'&format='.$format.'&date='.$startDate.'&enddate='.$endDate.'&tp='.$hourlyRate;
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$requestedUrl);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Weather Webservice');
		$resultData = curl_exec($curl_handle);
		curl_close($curl_handle);
		return $resultData;
	}

//************************************* FUNCTION TO SAVE RECORD *******************************************************************************//	
	public static function saveWeatherRecords($reportData,$startDate,$endDate) 
	{
		try 
		{
			$queryDelete = "delete from weather_record where DATE(date) BETWEEN DATE('$startDate') AND DATE('$endDate')";
			$resultDelete = dbconfig::run($queryDelete);
			foreach($reportData as $row)
			{
				$query = "INSERT INTO weather_record (date,link,temp,max_temp,min_temp,pressure,air_speed,humidity,ip,createdDate) ";
				$query .= "VALUES ('".trim($row['date'])."', '".trim($row['weatherLink'])."', '".trim($row['temp'])."', '".trim($row['max_temp'])."', '".trim($row['min_temp'])."', '".trim($row['pressure'])."', '".trim($row['air_speed'])."', '".trim($row['humidity'])."', '".trim($_SERVER['REMOTE_ADDR'])."', '".trim(date('Y-m-d h:i:s A'))."')";
				$result = dbconfig::run($query);
				if(!$result) 
				{
					throw new exception("Error to create new user.");
				}
			}
			echo json_encode("yes");die;
		}
		catch (Exception $e) 
		{
			echo json_encode("no");die;
		} 
	}
		
	public static function validateCityName($cityName)
	{
		if (preg_match('/^[A-Za-z_-]*$/', $cityName)) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function validateDate($date)
	{
		if (preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$date))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
