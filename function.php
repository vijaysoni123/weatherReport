<?php
require_once('classes/userClass.php'); 
$userObj = new USER(); 
//error_reporting(0);
$queryType = $_POST['queryType'];
header("Content-Type: application/json; charset=UTF-8");

function validateCityName($cityName)
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

function validateDate($date)
{
	if (preg_match("^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$",$date))
	{
		return true;
	}
	else
	{
		return false;
	}
}
if($queryType=='getWeatherReport')
{
	$_SESSION['error']='';
	$cityName = trim(addslashes($_POST['cityname']));
	$startDate = trim($_POST['fromDate']);
	$endDate = trim($_POST['toDate']);
	$explodeStartDate = explode(' ',$startDate);
	$explodendDate = explode(' ',$endDate);
	$apiKey = trim('1a10d0d8c2684d70b53110545181202');
	$format = 'json';
	$hourlyRate='1';
	$validateCity = USER::validateCityName($cityName);
	$validatestartDate = USER::validateDate($explodeStartDate[0]);
	$validateEndDate =USER::validateDate($explodendDate[0]);
	if(!$validateCity)
	{
		echo $_SESSION['error']='1';die;
	}
	else if(!$validatestartDate)
	{
		echo $_SESSION['error']='2';die;
	}
	else if(!$validateEndDate)
	{
		echo $_SESSION['error']='3';die;
	}
	else if(strtotime($startDate) > strtotime($endDate))
	{
		echo $_SESSION['error']='4';die;
	}
	else
	{ 
		unset($_SESSION['error']);
		if(count($explodeStartDate)==3)
		{
			$AM_PM_startDate = $explodeStartDate[2];
			$startTime = trim($explodeStartDate[1]);
			$explodeTime=explode(':',date('H:i',strtotime($startDate)));
			if(strtolower(trim($AM_PM_startDate))==strtolower('am'))
			{
				$finalStartTime = $explodeTime[0]*100;
			}
			else if(strtolower(trim($AM_PM_startDate))==strtolower('pm'))
			{
				$finalStartTime = $explodeTime[0]*100;
			}
		}
		else
		{
			$AM_PM_startDate = "AM";
			if(count($explodeStartDate)==2)
			{
				$startTime = trim($explodeStartDate[1]);
				$explodeTime=explode(':',date('H:i',strtotime($startDate)));
				if(strtolower(trim($AM_PM_startDate))==strtolower('am'))
				{
					$finalStartTime = $explodeTime[0]*100;
				}
				else if(strtolower(trim($AM_PM_startDate))==strtolower('pm'))
				{
					$finalStartTime = $explodeTime[0]*100;
				}
			}
			else 
			{
				$finalStartTime = '100';
			}
		}
		
		if(count($explodendDate)==3)
		{
			$AM_PM_endDate = $explodendDate[2];
			$endTime = trim($explodendDate[1]);
			$explodeTime=explode(':',date('H:i',strtotime($endDate)));
			if(strtolower(trim($AM_PM_endDate))==strtolower('am'))
			{
				$finalEndTime = $explodeTime[0]*100;
			}
			else if(strtolower(trim($AM_PM_endDate))==strtolower('pm'))
			{
				$finalEndTime = $explodeTime[0]*100;
			}
		}
		else
		{
			$AM_PM_endDate = "PM";
			if(count($explodendDate)==2)
			{
				$endTime = trim($explodendDate[1]);
				$explodeTime=explode(':',date('H:i',strtotime($endDate)));
				if(strtolower(trim($AM_PM_endDate))==strtolower('am'))
				{
					$finalEndTime = $explodeTime[0]*100;
				}
				else if(strtolower(trim($AM_PM_endDate))==strtolower('pm'))
				{					
					$finalEndTime =$explodeTime[0]*100;
				}
			}
			else 
			{
				$finalEndTime = '2300';
			}
		}
		$finalEndTime = ($finalEndTime>2300)?2300:$finalEndTime;
		$finalStartTime = ($finalStartTime>2300)?2300:$finalStartTime;
		$data =  USER::getWeatherReport($cityName,$explodeStartDate[0],$explodendDate[0],$apiKey,$format,$hourlyRate);
		$resultData = json_decode($data);
		$weatherReportList='';
		if(!empty($resultData))
		{
			foreach($resultData AS $row)
			{
				$row = get_object_vars($row);
				if(!empty($row['weather']) && isset($row['weather']))
				{
					$weatherData = $row['weather'];
					foreach($weatherData as $weData)
					{
						$weatherDate = $weData->date;
						$maxTemp =  $weData->maxtempC;
						$minTemp =  $weData->mintempC;
						$sunHour = $weData->sunHour;
						$hourlyData = $weData->hourly;
						$windSpeed='';
						$humidity='';
						$pressure='';
						$imageLink='';
						foreach($hourlyData as $hrData)
						{
							$time= $hrData->time;
							if($time==0) $finalTime = '00:00 AM';
							if($time==100)$finalTime = '01:00 AM';
							if($time==200)$finalTime = '02:00 AM';
							if($time==300)$finalTime = '03:00 AM';
							if($time==400)$finalTime ='04:00 AM';
							if($time==500)$finalTime ='05:00 AM';
							if($time==600)$finalTime ='06:00 AM';
							if($time==700)$finalTime ='07:00 AM';
							if($time==800)$finalTime ='08:00 AM';
							if($time==900)$finalTime ='09:00 AM';
							if($time==1000)$finalTime ='10:00 AM';
							if($time==1100)$finalTime ='11:00 AM';
							if($time==1200)$finalTime ='12:00 AM';
							if($time==1300)$finalTime ='01:00 PM';
							if($time==1400)$finalTime ='02:00 PM';
							if($time==1500)$finalTime ='03:00 PM';
							if($time==1600)$finalTime ='04:00 PM';
							if($time==1700)$finalTime ='05:00 PM';
							if($time==1800)$finalTime ='06:00 PM';
							if($time==1900)$finalTime ='07:00 PM';
							if($time==2000)$finalTime ='08:00 PM';
							if($time==2100)$finalTime ='09:00 PM';
							if($time==2200)$finalTime ='10:00 PM';
							if($time==2300)$finalTime ='11:00 PM';
							if(strtotime(trim($explodeStartDate[0])) == strtotime(trim($weatherDate)) && $finalStartTime <= $time)
							{
								$maxTemp=$hrData->tempC;
								$windSpeed=$hrData->windspeedMiles;
								$humidity =$hrData->humidity;
								$pressure= $hrData->pressure;
								$weatherLink =  $hrData->weatherIconUrl;
								foreach($weatherLink as $link)
								{
									$imageLink = $link->value;
								}
							}
							else if(strtotime(trim($explodendDate[0])) == strtotime(trim($weatherDate)) && $finalEndTime >= $time && strtotime(trim($explodeStartDate[0])) != strtotime(trim($weatherDate)))
							{
								$maxTemp=$hrData->tempC;
								$windSpeed=$hrData->windspeedMiles;
								$humidity =$hrData->humidity;
								$pressure= $hrData->pressure;
								$weatherLink =  $hrData->weatherIconUrl;
								foreach($weatherLink as $link)
								{
									$imageLink = $link->value;
								}
							}
							else if(strtotime(trim($explodendDate[0])) > strtotime(trim($weatherDate)) && strtotime(trim($explodeStartDate[0])) < strtotime(trim($weatherDate)))
							{
								$maxTemp=$hrData->tempC;
								$windSpeed=$hrData->windspeedMiles;
								$humidity =$hrData->humidity;
								$pressure= $hrData->pressure;
								$weatherLink =  $hrData->weatherIconUrl;
								foreach($weatherLink as $link)
								{
									$imageLink = $link->value;
								}
							}
							
							if($maxTemp!='' && $windSpeed!='' && $humidity!='' && $pressure!='' && $imageLink!='')
							{
								$error="";
								$weatherReportList .= '{"date":"' . trim($weatherDate).' '.$finalTime. '",'; // trim case types having less than 5 digits
								$weatherReportList .= '"temp":"' . sprintf("%.1f", trim($maxTemp)) . '",';
								$weatherReportList .= '"weatherLink":"' . trim($imageLink) . '",';
								$weatherReportList .= '"error":"'.$error.'",';
								$weatherReportList .= '"pressure":"' . sprintf("%.1f", trim($pressure)) . '",';
								$weatherReportList .= '"air_speed":"' . sprintf("%.1f", trim($windSpeed)). '",';
								$weatherReportList .= '"humidity":"' . sprintf("%.1f", trim($humidity)) . '",';
								$weatherReportList .= '"min_temp":"' . sprintf("%.1f", trim($minTemp)) . '",';
								$weatherReportList .= '"max_temp":"' . sprintf("%.1f", trim($maxTemp)). '",';
								$weatherReportList .= '"min_max_temp":"' .sprintf("%.1f", trim($maxTemp))."/" .sprintf("%.1f", trim($minTemp)). '"},';
							}
						}
					}
				}
				else
				{
					$error_mesa='';
					foreach($row as $error)
					{
						foreach($error as $er)
						{
							$error_mesa = $er->msg;
						}
					}
					$weatherReportList .= '{"error":"' . trim($error_mesa). '" },'; 
					$weatherReportList ='{"records":['.rtrim($weatherReportList,",").']}';
					echo($weatherReportList);die;
				}
			}
			$weatherReportList ='{"records":['.rtrim($weatherReportList,",").']}';
			echo($weatherReportList);die;
		}
		else
		{
			$weatherReportList="";
			$weatherReportList ='{"records":['.rtrim($weatherReportList,",").']}';
			echo($weatherReportList);die;
		}
	}
}
else if($queryType=='saveWeaherReportData')
{
	$reportData = $_POST['reportData'];
	$cityName = trim(addslashes($_POST['cityname']));
	$startDate = trim($_POST['fromDate']);
	$endDate = trim($_POST['toDate']);
	$explodeStartDate = explode(' ',$startDate);
	$explodendDate = explode(' ',$endDate);
	$data =  USER::saveWeatherRecords($reportData,$explodeStartDate[0],$explodendDate[0]);
	echo $data;
}
?>

