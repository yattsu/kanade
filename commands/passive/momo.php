<?php
// $momo_list = [
// 	'Good morning!',
// 	'Morning sunshine!',
// 	'Momo.'
// ];
// $momo_random = rand(0, count($momo_list) - 1);

$message = explode(' ', $this->MESSAGE->content);
$keyword = 'mo';

$counter = 0;

foreach($message as $word) {
	$keyword_instances = substr_count(strtolower($word), strtolower($keyword));
	if($keyword_instances == 2) {
		$counter++;
	}
}

if ($counter >= 1) {
	require_once 'model/database.php';
	$database = new Database;

	$query = 'SELECT last_momo FROM nini_momo ORDER BY id DESC';
	$result = $database->MYSQL->query($query);
	$row = $result->fetch_assoc();

	$current_time = time();
	$last_momo = $row['last_momo'];

	if (($current_time - $row['last_momo']) < 600) {
		return;
	}

	$query = 'UPDATE nini_momo SET last_momo = ' . $current_time . ' ORDER BY id DESC LIMIT 1';
	$result = $database->MYSQL->query($query);

	$this->say('Momo');

	return true;
}

return false;
?>