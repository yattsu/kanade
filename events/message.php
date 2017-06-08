<?php

require_once 'model/kanade.php';
$kanade = new kanade($message);

$kanade->storeguild();

if (!$kanade->isBotMessage()) {
	// $kanade->randomHarrass();
	$kanade->storepsychopass();
	$kanade->storeemoji();
	$kanade->storemessages();
	$kanade->checkafk();

	if (!$kanade->nini()) {
		$kanade->momo();
	}

	if ($kanade->isCommand()) {
		if ($kanade->isUserAllowed()) {
			if (!$kanade->isCommandAllowed()) {
				$kanade->say('command doesn\'t exist.');
			} else {
				switch ($kanade->command)
				{
					case 'harrass':
						$kanade->harrass();
					break;
					case 'compliment':
						$kanade->compliment();
					break;
					case 'whosyourdaddy':
						$kanade->whosyourdaddy();
					break;
					case 'selfdefense':
						$kanade->selfdefense();
					break;
					case 'gj':
						$kanade->gj();
					break;
					case 'kick':
						$kanade->kick();
					break;
					case 'bitchslap':
						$kanade->bitchslap();
					break;
					case 'marco':
						$kanade->marco();
					break;
					case 'dead':
						$kanade->dead();
					break;
					case '8ball':
						$kanade->ball8();
					break;
					case 'psychopass':
						$kanade->psychopass();
					break;
					case 'emoji':
						$kanade->emoji_leaderboard();
					break;
					case 'time':
						$kanade->time();
					break;
					case 'settime':
						$kanade->set_time();
					break;
					case 'afk':
						$kanade->afk();
					break;
					case 'afkoff':
						$kanade->afkoff();
					break;
					case 'daily':
						$kanade->daily();
					break;
					case 'give':
						$kanade->give();
					break;
					case 'schmeckles':
						$kanade->schmeckles();
					break;
					case 'startbet':
						$kanade->startbet();
					break;
					case 'bet':
						$kanade->bet();
					break;
					case 'betresults':
						$kanade->betresults();
					break;
					case 'lol':
						$kanade->lol();
					break;
					case 'getguildid':
						$kanade->getguildid();
					break;
					case 'members':
						$kanade->members();
					break;
				}
			}
		} else {
			$kanade->say('i only take orders from my masters');
		} 
	}
}
?>