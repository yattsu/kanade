<?php
$command = shell_exec('free -m');
$output = explode(' ', trim($command));
$memory = [];

foreach ($output as $item) {
	if (!empty($item)) {
		$memory[] = $item;
	}
}

$total = number_format(intval($memory[6]) / 1000, 2, '.', '');
$used = number_format(intval($memory[7]) / 1000, 2, '.', '');
$free = number_format(intval($memory[6] - $memory[7]) / 1000, 2, '.', '');

if (intval($total) == 0) {
	$this->say('`Memory usage unavailable`');

	return;
}

$this->say('```' .
	"Memory usage" . 
	"\n\nTotal: " . $total . " GB" . 
	"\nUsed: " . $used . " GB" . 
	"\nFree: " . $free . " GB" 
	. '```');
?>