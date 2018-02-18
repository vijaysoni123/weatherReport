	<script src="js/moment.min.js"></script>   
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datetimepicker.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.js"></script>
	<script src="js/datatables.init.js"></script>
	<script src="js/validation_file.js"></script>
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			var FromEndDate = new Date();
			$('.datetimepicker').datetimepicker({
				format:'YYYY-MM-DD hh:mm A',
				minDate: moment('01/01/1950')
			});
		});
		var app = angular.module('weatherApp', []);
		app.controller('weatherCtrl', function($scope, $http) {
			$scope.fromDate='<?php echo date('Y-m-d H:i A');?>';
			$scope.toDate='<?php echo date('Y-m-d H:i A');?>';
			$scope.city='';
			$scope.weatherReportList=[];
			$('.datatable').hide();

			$scope.getWeaherReportData = function()
			{
				$scope.weatherReportList=[];
				$('.error_text_msg_div').hide();
				if($('#toDate').val()!="" && $('#fromDate').val()!="" && $scope.city!="")
				{
					$('#loading').show();
					$('.requiredValidationForWeatherForm').css("border-color","#ccc");
					$('.form_blank_error_message').hide();
					var post_data = $.param({fromDate:$('#fromDate').val(), toDate: $('#toDate').val(), cityname: $scope.city,queryType:'getWeatherReport'});
					$http({
						method: 'POST',
						url: 'weatherFunction',
						data: post_data,
						headers: {'Content-Type': 'application/x-www-form-urlencoded'}
					})
					.then(function(response) {
						$('.datatable').hide();
						$('.error_text_msg_div').addClass('alert-danger');
						$('.error_text_msg_div').removeClass('alert-success');
						if(response.data=='1')
						{
							$('.error_text_msg_div').html('<strong>Error! </strong> Invalid City Name.').show();
						}
						else if(response.data=='2')
						{
							$('.error_text_msg_div').html('<strong>Error! </strong> Invalid Start Date.').show();
						}
						else if(response.data=='3')
						{
							$('.error_text_msg_div').html('<strong>Error! </strong> Invalid End Date.').show();
						}
						else if(response.data=='4')
						{
							$('.error_text_msg_div').html('<strong>Error! </strong> End Date should be greater than or equal to start Date.').show();
						}
						else if(response.data=='5')
						{
							$('.error_text_msg_div').html('<strong>Error! </strong> There is no weather data available for the date provided.').show();
						}
						else
						{
							if(response.data.records[0].error=="")
							{
								$scope.weatherReportList=response.data.records;
								if(response.data.records.length>0)
								{
										$('.datatable').show();
										$('#datatable').dataTable().fnClearTable();
										$('#datatable').dataTable().fnDestroy();
										setTimeout(function(){ 
											$('#datatable').dataTable({
												ordering:false,
												
												"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]					
											}); }, 500);
								}
							}
							else
							{
								$('.error_text_msg_div').html('<strong>Error! </strong>'+response.data.records[0].error).show();
							}
							
						}
						$('#loading').hide();
					},
					function(response) {
						//console.log(response.data, response.status);
					});
				}
				else
				{
					$('.requiredValidationForWeatherForm').each(function(){
						if($(this).val())
						{
							$(this).css("border-color","#ccc");
						}
						else
						{
							$(this).css("border-color","red");
						}
						$(this).on('keypress change',function(){
							$(this).css("border-color","#ccc");
						});
					})
					$('.form_blank_error_message').show();
				}
			};
			
			$scope.saveWeaherReportData = function()
			{
				$('.error_text_msg_div').hide();
				$('#loading').show();
				var post_data = $.param({reportData:$scope.weatherReportList,queryType:'saveWeaherReportData',fromDate:$('#fromDate').val(), toDate: $('#toDate').val(), cityname: $scope.city,});
				$http({
					method: 'POST',
					url: 'weatherFunction',
					data: post_data,
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
				.then(function(response) {
					$('.datatable').hide();
					if(response.data=='yes')
					{
						$('.datatable').show();
						$('.error_text_msg_div').addClass('alert-success');
						$('.error_text_msg_div').removeClass('alert-danger');
						$('.error_text_msg_div').html('<strong>Success! </strong> Record Save SuccessFully.').show();
					}
					else if(response.data=='no')
					{
						$('.error_text_msg_div').addClass('alert-danger');
						$('.error_text_msg_div').removeClass('alert-success');
						$('.error_text_msg_div').html('<strong>Error! </strong> Something went wrong.').show();
					}
					$('#loading').hide();
				},
				function(response) {
					//console.log(response.data, response.status);
				});
			};
			
			$scope.printWeatherReportData = function()
			{
				$('.error_text_msg_div').hide();
				$('#loading').show();
				var post_data = $.param({reportData:$scope.weatherReportList,queryType:'printWeaherReportData',fromDate:$('#fromDate').val(), toDate: $('#toDate').val(), cityname: $scope.city,});
				$http({
					method: 'POST',
					url: 'weatherReport',
					data: post_data,
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
				.then(function(response) {
					$('.datatable').hide();
					if(response.data)
					{
						$('.datatable').show();
						$('.downloadPdf').attr('href',response.data);
						$('.downloadPdf').get(0).click();
					}
					else
					{
						$('.error_text_msg_div').addClass('alert-danger');
						$('.error_text_msg_div').removeClass('alert-success');
						$('.error_text_msg_div').html('<strong>Error! </strong> Something went wrong.').show();
					}
					$('#loading').hide();
				},
				function(response) {
					//console.log(response.data, response.status);
				});
			};
			
		});
</script>
</body>
</html>


