<?php
$author_id = $this->MESSAGE->author->id;
$master_id = $this->MASTER_ID;

if ($author_id == $master_id) {
	$this->reply('You :heart:');
} else {
	$this->say('<@' . $master_id . '> Is my senpai.');
}
?>