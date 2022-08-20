<?php

// Fungsi Ambil Aqi
function ambilAqi($kota) {

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://air-quality-by-api-ninjas.p.rapidapi.com/v1/airquality?city={$kota}",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: air-quality-by-api-ninjas.p.rapidapi.com",
			"X-RapidAPI-Key: 186a15c966msh1505e0792dfbf03p1e781djsnbe8d5ded7f15"
		],
	]);
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $arr=json_decode($response);
    return $arr->overall_aqi;
  
  }
  
}


// Fungsi Ambil Cuaca hari ini
function ambilDay($kota) {

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://weatherapi-com.p.rapidapi.com/current.json?q={$kota}",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: weatherapi-com.p.rapidapi.com",
		"X-RapidAPI-Key: 186a15c966msh1505e0792dfbf03p1e781djsnbe8d5ded7f15"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$arr=json_decode($response);
	return $arr;
}
}


// Fungsi Cuaca untuk 6 hari ke depan
function ambilCuaca($location) {

	$curl = curl_init();
	
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://yahoo-weather5.p.rapidapi.com/weather?location={$location}&format=json&u=f",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: yahoo-weather5.p.rapidapi.com",
			"X-RapidAPI-Key: 186a15c966msh1505e0792dfbf03p1e781djsnbe8d5ded7f15"
		],
	]);
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	curl_close($curl);
	
	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		$arr=json_decode($response);
		return $arr;
	}
}

// Konversi Fahrenheit ke Celcius
function FtoC($f) {
	return round(5/9 * ($f - 32));
}

// kota default
$kota = "Bandung";
if(isset($_GET["kota"])){
	$kota = $_GET["kota"];
}

// Untuk mengambil data dari API
$arr= ambilCuaca($kota);
$aqi= ambilAqi($kota);
$day= ambilDay($kota);
$city = $arr->location->city;
$region = $arr->location->region;
$country = $arr->location->country;
$latw = $arr->location->lat;
$longw = $arr->location->long;
$wind = $arr->current_observation->wind->speed;
$atmosphere = $arr->current_observation->atmosphere->humidity;
$condition = $arr->current_observation->condition->temperature;
$theday = $day->current->condition->icon;
$day0 = $arr->forecasts[0];
$day1 = $arr->forecasts[1];
$day2 = $arr->forecasts[2];
$day3 = $arr->forecasts[3];
$day4 = $arr->forecasts[4];
$day5 = $arr->forecasts[5];
$day6 = $arr->forecasts[6];

// Kondisi untuk Air Quality Index

$nilai = $aqi;
$quality = "Good";

if ($nilai >= 0 && $nilai < 50) {
  $quality = "Good";
} elseif($nilai >= 50 && $nilai < 100){
  $quality = "Moderate";
} elseif($nilai >= 100 && $nilai < 150){
  $quality = "Unhealty for Sensitive <br>Groups";
} elseif($nilai >= 150 && $nilai < 200){
  $quality = "Unhealty";
} elseif($nilai >= 200 && $nilai < 300){
  $quality = "Very Unhealty";
} else {
  $quality = "Hazardous";
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		<!-- Favicons -->
        <link rel="shortcut icon" href="images/favicon.png">
		<title>Prakiraan Cuaca dan Kualitas Udara</title>

		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">
		
		<!--[if lt IE 9]>
		<script src="js/ie-support/html5.js"></script>
		<script src="js/ie-support/respond.js"></script>
		<![endif]-->

	</head>
	<body>

			<div class="mobile-navigation"></div>

				</div>
			</div> <!-- .site-header -->

			<div class="hero" data-bg-image="images/bg.jpg">
				<div class="container">
					<form action="?" class="find-location" method="GET">
						<input type="text" placeholder="Search your location..." name="kota" id="kota">
						<input type="submit" value="Search">
					</form>

				</div>
			</div>
			<div class="forecast-table">
				<div class="container">
					<div class="forecast-container">
						<div class="today forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day0->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="location"><?php echo $city. " /". $region." / ". $country; ?></div>
								<div class="degree">
									<div class="num"><?php echo FtoC($condition); ?><sup>o</sup>C</div>
									<div class="forecast-icon">
										<img src=<?php echo strval($theday); ?> alt="" width=90>
									</div>	
								</div>
								<span><img src="images/icon-humidity.png" alt=""><?php echo $atmosphere; ?>%</span>
								<span><img src="images/icon-wind2.png" alt=""><?php echo $wind; ?> km/h</span>
								<span><img src="images/icon-air-quality.png" alt=""><?php echo $quality; ?></span>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day1->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day1->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day1->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day0->low); ?><sup>o</sup></small>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day2->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day2->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day2->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day2->low); ?><sup>o</sup></small>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day3->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day3->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day3->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day3->low); ?><sup>o</sup></small>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day4->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day4->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day4->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day4->low); ?><sup>o</sup></small>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day5->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day5->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day5->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day5->low); ?><sup>o</sup></small>
							</div>
						</div>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day"><?php echo $day6->day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
									<img src=<?php echo "icon/".strval($day6->code).".png"; ?>  alt="" width=48>
								</div>
								<div class="degree"><?php echo FtoC($day6->high); ?><sup>o</sup>C</div>
								<small><?php echo FtoC($day6->low); ?><sup>o</sup></small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<main class="main-content">
				<div class="fullwidth-block">
					<div class="container"><br><br><br>
						
					</div>
				</div>

			<footer class="site-footer">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<form action="#" class="subscribe-form">
								<input type="text" placeholder="Risha Amara - 10120020" readonly>
							</form>
						</div>
						<div class="col-md-3 col-md-offset-1">
						</div>
					</div>

					<p class="colophon">Copyright &copy 2022 Risha Amara - 10120020</p>
				</div>
			</footer> <!-- .site-footer -->
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		
	</body>

</html>