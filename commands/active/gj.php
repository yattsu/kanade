<?php
$author_id = $this->MESSAGE->author->id;
$master_id = $this->MASTER_ID;

if ($author_id == $master_id) {
	$this->reply(':flushed: T-thank you senpai!');
} else {
	$this->reply('Lol thanks.');
}
?>