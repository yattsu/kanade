<?php

require_once 'model/banan.php';
$banan = new Banan($message);

// echo $banan->message_history();

if (!$banan->isBotMessage()) {
	$banan->randomHarrass();
	$banan->storepsychopass();

	if (!$banan->nini()) {
		$banan->momo();
	}

	if ($banan->isCommand()) {
		if ($banan->isUserAllowed()) {
			if (!$banan->isCommandAllowed()) {
				$banan->say('command doesn\'t exist.');
			} else {
				switch ($banan->command)
				{
					case 'harrass':
						$banan->harrass();
					break;
					case 'compliment':
						$banan->compliment();
					break;
					case 'whosyourdaddy':
						$banan->whosyourdaddy();
					break;
					case 'selfdefense':
						$banan->selfdefense();
					break;
					case 'gj':
						$banan->gj();
					break;
					case 'kick':
						$banan->kick();
					break;
					case 'bitchslap':
						$banan->bitchslap();
					break;
					case 'marco':
						$banan->marco();
					break;
					case 'dead':
						$banan->dead();
					break;
					case '8ball':
						$banan->ball8();
					break;
					case 'psychopass':
						$banan->psychopass();
					break;
				}
			}
		} else {
			$banan->say('i only take orders from my masters');
		} 
	}
}
?>