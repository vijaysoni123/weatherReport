In this project I used
Demo http://api.worldweatheronline.com/premium/v1/past-weather.ashx
I have used Free services of this api and there is some limitation we can search weather information of past dates and date next for only 2days.

And one more thing you need to change one morething in php.ini file

max_input_vars=30000000000

This is beacuse i used angular js for that no need to call webservices again and again this is used to increase the performance of website.
When we search data for jaipur city and from date 2018-02-14 10:50AM to end Date  2018-02-14 10:50AM Then we call the 
Demo http://api.worldweatheronline.com/premium/v1/past-weather.ashx using curl then we just store the result of this code into 
$scope.weatherReportList [] first time and after that you click on save Button or Export to PDF button then no need to call the webservice again and again 
because when i call the webservice again and again then the performance of the code is decrease and you just passed that result array directly.
