<?php

require_once 'model/banan.php';
$banan = new Banan($message);

if (!$banan->isBotMessage()) {
	$banan->randomHarrass();
	$banan->storepsychopass();
	$banan->storeemoji();
	$banan->storemessages();
	$banan->checkafk();

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
					case 'emoji':
						$banan->emoji_leaderboard();
					break;
					case 'time':
						$banan->time();
					break;
					case 'settime':
						$banan->set_time();
					break;
					case 'afk':
						$banan->afk();
					break;
					case 'afkoff':
						$banan->afkoff();
					break;
					case 'daily':
						$banan->daily();
					break;
					case 'give':
						$banan->give();
					break;
					case 'schmeckles':
						$banan->schmeckles();
					break;
					case 'startbet':
						$banan->startbet();
					break;
					case 'bet':
						$banan->bet();
					break;
					case 'betresults':
						$banan->betresults();
					break;
					case 'lol':
						$banan->lol();
					break;
				}
			}
		} else {
			$banan->say('i only take orders from my masters');
		} 
	}
}
?>