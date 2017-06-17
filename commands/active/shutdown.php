<?php
$master_id = $this->MASTER_ID;
$author_id = $this->MESSAGE->author->id;

if ($master_id !== $author_id) {
	$this->reply('You don\'t have permission to do this');

	return;
}

$command = 'shutdown now';

$this->say('`System will shutdown now`');
shell_exec($command);
?>