<?php 
//error_reporting(0);
session_start();
ob_start();
include "urlconfig.php";
date_default_timezone_set("Asia/Kolkata");
?>
<!DOCTYPE html>
<html lang="en" ng-app="weatherApp" ng-controller="weatherCtrl">
<head>
<title>Wheather Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow">      
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Mon, 01 Jan 1990 00:00:00 GMT">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">        
<meta http-equiv="cache-control" content="private" />
<meta http-equiv="cache-control" content="pre-check=0" />
<meta http-equiv="cache-control" content="post-check=0" />
<meta http-equiv="cache-control" content="must-revalidate" />
<meta http-equiv="content-language" content="en">
<meta name="robots" content="DISALLOW">
<meta name="revisit-after" content="1 day">
<meta http-equiv="content-language" content="en">
<meta http-equiv="content-script-type" content="text/javascript">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Weather,Weather Report" />
<meta name="description" content="Website to get weather report" />
	
<link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="css/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/loader.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="shortcut icon" href="weather.png">
<base href="<?php echo $base_url;?>"/>
</head>
<body>
<div class="loading" id="loading" style="display: none;">
<img src="loader.svg" alt="">	</div>
