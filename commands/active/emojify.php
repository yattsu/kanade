<?php
$message_exploded = explode(' ', $this->MESSAGE->content);
array_shift($message_exploded);

$words = [
	'time' => 'clock1',
	'person' => 'person_with_blond_hair',
	'year' => 'calendar_spiral',
	'way' => 'motorway',
	'day' => 'sunrise',
	'thing' => 'grey_question',
	'man' => 'man_in_tuxedo',
	'world' => 'map',
	'life' => 'pregnant_woman',
	'hand' => 'hand_splayed',
	'child' => 'baby',
	'eye' => 'eye',
	'woman' => 'woman',
	'place' => 'first_place',
	'work' => 'construction_worker',
	'week' => 'calendar',
	'case' => 'briefcase',
	'point' => 'point_up',
	'number' => 'hash',
	'be' => 'bee',
	'do' => 'thumbsup',
	'say' => 'speaking_head',
	'make' => 'construction_site',
	'go' => 'runner',
	'know' => 'thinking',
	'see' => 'see_no_evil',
	'think' => 'thinking',
	'look' => 'eye',
	'want' => 'money_mouth',
	'give' => 'gift',
	'tell' => 'speaking_head',
	'ask' => 'question',
	'work' => 'construction_worker',
	'leave' => 'runner',
	'call' => 'calling',
	'good' => 'thumbsup',
	'new' => 'new',
	'first' => 'first_place',
	'last' => 'last',
	'long' => 'third_place',
	'great' => 'ok_hand',
	'little' => 'baby_symbol',
	'old' => 'older_man',
	'right' => 'arrow_right',
	'big' => 'whale',
	'high' => 'airplane_small',
	'different' => 'bust_in_silhouette',
	'small' => 'baby_symbol',
	'next' => 'track_next',
	'early' => 'sunrise',
	'young' => 'baby',
	'important' => 'exclamation',
	'bad' => 'name_badge',
	'same' => 'busts_in_silhouette',
	'to' => 'two',
	'for' => 'four',
	'on' => 'on',
	'up' => 'up',
	'above' => 'arrow_heading_up',
	'a' => 'regional_indicator_a',
	'I' => 'regional_indicator_i',
	'not' => 'no_entry_sign',
	'he' => 'boy',
	'this' => 'point_right',
	'they' => 'couple',
	'her' => 'girl',
	'she' => 'girl',
	'will' => 'soon',
	'one' => 'one',
	'all' => 'family_mwgb',
	'show' => 'eye',
	'something' => 'question',
	'potato' => 'potato',
	'fast' => 'runner',
	'too' => 'two'
];

$final_message = [];

foreach ($message_exploded as $word) {
	if (array_key_exists(strtolower($word), $words)) {
		$final_message[] = ':' . strtolower($words[$word]) . ':';
	} else {
		$final_message[] = strtolower($word);
	}
}

$final_message = implode(' ', $final_message);

$this->say('**Emoji text:** ' . $final_message);
?>