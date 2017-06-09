<?php
$api_key = $this->API;
$message_exploded = explode(' ', $this->MESSAGE->content);
array_shift($message_exploded);
$arguments = implode(' ', $message_exploded);
$city = str_replace(' ', '%20', strtolower($arguments));
$url = 'http://api.waqi.info/feed/' . $city . '/?token=' . $api_key['pollution'];

if (!$city) {
	return;
}

$content = file_get_contents($url);
$content = json_decode($content, true);
$status = $content['status'];

if ($status !== 'ok') {
	$this->reply('No data found for the given city');

	return;
}

$level = $content['data']['aqi'];

if ($level < 51) {
	$verdict = 'Good';
	$message = 'Air quality is considered satisfactory, and air pollution poses little or no risk.';
} elseif ($level < 101) {
	$verdict = 'Moderate';
	$message = 'Air quality is acceptable; however, for some pollutants there may be a moderate health concern for a very small number of people who are unusually sensitive to air pollution.';
} elseif ($level < 151) {
	$verdict = 'Unhealthy for Sensitive Groups';
	$message = 'Members of sensitive groups may experience health effects. The general public is not likely to be affected.';
} elseif ($level < 201) {
	$verdict = 'Unhealthy';
	$message = 'Everyone may begin to experience health effects; members of sensitive groups may experience more serious health effects.';
} elseif ($level < 301) {
	$verdict = 'Very Unhealthy';
	$message = 'Health warnings of emergency conditions. The entire population is more likely to be affected.';
} else {
	$verdict = 'Hazardous';
	$message = 'Health alert: everyone may experience more serious health effects.';
}

$this->say("```Data for " . ucwords($arguments) . "\n\nAQI: " . $level . "\nPollution: " . $verdict . "\n\nHealth Implications: " . $message . "```");
?>