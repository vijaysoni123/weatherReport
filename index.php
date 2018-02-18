<?php 
include_once('header.php'); 
?>
<style>
.header
{
    background: #000;
}
.set_section
{
    padding-top: 10px;
    padding-bottom: 10px;
    color: #fff;
    text-transform: uppercase;
    font-size: 20px;
    font-weight: bold;
}
.mb-10
{
	margin-bottom:10px;
}
body
{
	background: #d3d3d342;
}
thead
{
	background: #d7d7d77a;
}
.table-responsive
{
	overflow-x:hidden !important;
}
</style>
<header class="header mb-10">
	<div class="container">
		<div class="col-sm-12 text-center set_section">Weather Report</div>
	</div>
</header>
<section class="mb-10">
	<div class="container">
		<h3>Place & Date information</h3>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="col-sm-3"></div>
		<div class="col-sm-6">
			<form class="form-horizontal" id="weatherReportForm" method="post">
				<div class="alert alert-danger form_blank_error_message col-md-12" style="display:none">
					<strong>Error!</strong> All Fields are mandatory.
				</div>
				<div class="alert alert-danger error_text_msg_div col-md-12" style="display:none;">
							<strong>Error!</strong> Invalid City Name.
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<label class="col-sm-3">City Name <span class="req" title="required" style="color:red;">*</span> </label>
					<div class="col-sm-9">
						<input type="text" style="text-transform: capitalize;" class="form-control requiredValidationForWeatherForm" name="city" ng-model="city" maxlength="30" onkeypress="return AllowAlphabet(event);" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3">Start Date <span class="req" title="required" style="color:red;">*</span> </label>
					<div class="col-sm-9">
						<div class='input-group date datetimepicker' >
							<input type='text' id="fromDate" class="form-control requiredValidationForWeatherForm datetimepicker" ng-model="fromDate" name="fromDate" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
							<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3">End Date <span class="req" title="required" style="color:red;">*</span> </label>
					<div class="col-sm-9">
						<div class='input-group date datetimepicker'>
							<input type='text' id="toDate" class="form-control requiredValidationForWeatherForm datetimepicker" ng-model="toDate"  name="toDate" onselectstart="return false"  onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
							<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<button type="button" class="btn btn-primary get_weather_history" ng-click="getWeaherReportData();" name="getData"><img src="weather.png" style="wisth:30px;height:30px;" >&nbsp;&nbsp;Get Weather History</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-3"></div>
		<a  download class="downloadPdf" style="display:none;"></a>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="table-responsive datatable">
		<div class="form-group datatable" style="float:right;">
			<div class="col-sm-4">
				<button type="button" ng-click="saveWeaherReportData();" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;Save</button>
			</div>
			<div class="col-sm-8">
				<button type="button" ng-click="printWeatherReportData();" class="btn btn-danger"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;Export To PDF</button>
			</div>
		</div>
		<div class="clearfix"></div>
		<table id="datatable" class="table datatable" style="display:none" >
			<thead>
				<tr>
					<th>Time</th>
					<th>Weather</th>
					<th>Temperature</th>
					<th>Min/Max Temperature</th>
					<th>Pressure(hPa)</th>
					<th>Air Speed(mps)</th>
					<th>Humadity</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="weather in weatherReportList">
					<td>{{weather.date}}</td>
					<td><img style="width: 50px;height: 50px;border-radius: 50%;" src='{{weather.weatherLink}}'></td>
					<td>{{weather.temp}}&nbsp;<sup>0</sup>C</td>
					<td>{{weather.min_max_temp}}&nbsp;<sup>0</sup>C</td>
					<td>{{weather.pressure}}</td>
					<td>{{weather.air_speed}}</td>
					<td>{{weather.humidity}}&nbsp;%</td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
</section>
 <?php include_once('footer.php'); ?>

