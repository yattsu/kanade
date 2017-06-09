<?php
$guild = $this->MESSAGE->channel->guild;
$members = $guild->member_count;

$this->say('Total members: `' . $members . '`');
?>