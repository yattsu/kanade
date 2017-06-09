<?php
$gifs = [
	'http://gph.is/1LyAEQ2'
];

$random_gif = rand(0, count($gifs) - 1);

$this->say(' ' . $gifs[$random_gif]);
?>