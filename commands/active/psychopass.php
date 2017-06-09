<?php
$author_id = $this->MESSAGE->author->id;
$command_argument = $this->command_argument;

$sibyl_error = '`[SIBYL SYSTEM]: ERROR - The psychopass cannot be determined.`';

if ($command_argument == '@everyone' || $command_argument == '@here') {
	$this->say($sibyl_error);

	return;
}

require_once 'model/database.php';
$database = new Database;

if (!$command_argument) {
	$query = 'SELECT * FROM users WHERE user_id = ' . $author_id;
} else {
	$target_user_id = str_replace(array('<@', '>', '!'), '', $command_argument);
	$query = 'SELECT * FROM users WHERE user_id = ' . $target_user_id;
}
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number == 0) {
	$this->say($sibyl_error);

	return;
}

$row = $result->fetch_assoc();
$psychopass = $row['psychopass'];
$verdict = '';
$target_user_id = isset($target_user_id) ? $target_user_id : $author_id;

if ($psychopass < 100) {
	$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is not a target for enforcement action. The trigger of Dominator will be locked.`';
} elseif ($psychopass < 300) {
	$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is classified as a latent criminal and is a target for enforcement action. Dominator is set to Non-Lethal Paralyzer mode. Suspect can then be knocked out using the Dominator.`';
} else {
	$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect poses a serious threat to the society. Lethal force is authorized. Dominator will automatically switch to Lethal Eliminator.`';
}

$this->say($verdict);
?>