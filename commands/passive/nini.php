<?php
// $nini_list = [
// 	'Good night!',
// 	'Sleep well!',
// 	'Nini.'
// ];
// $nini_random = rand(0, count($nini_list) - 1);

$message_content = $this->MESSAGE->content;

$message = explode(' ', $message_content);
$keyword = 'ni';

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

	$query = 'SELECT last_nini FROM nini_momo ORDER BY id DESC';
	$result = $database->MYSQL->query($query);
	$row = $result->fetch_assoc();

	$current_time = time();
	$last_nini = $row['last_nini'];

	if (($current_time - $last_nini) < 600) {
		return;
	}

	$query = 'UPDATE nini_momo SET last_nini = ' . $current_time . ' ORDER BY id DESC LIMIT 1';
	$result = $database->MYSQL->query($query);

	$this->say('Nini');

	return true;
}

return false;
?>