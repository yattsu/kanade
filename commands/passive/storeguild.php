<?php
require_once 'model/database.php';
$database = new Database;

$guild_id = $this->getguildid();

$query = 'SELECT guild_id FROM guilds WHERE guild_id = "' . $guild_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

// $guild_invite = $this->MESSAGE->channel->createInvite(0, 0, false, false);
// var_dump($guild_invite);

if ($rows_number > 0) {
	return;
}

$guild_name = $this->MESSAGE->channel->guild->name;
$guild_icon = $this->MESSAGE->channel->guild->icon;
$guild_region = $this->MESSAGE->channel->guild->region;
$guild_owner_id = $this->MESSAGE->channel->guild->owner_id;
$guild_owner = $this->MESSAGE->channel->guild->owner;
$guild_owner_username = $guild_owner->username;
$guild_owner_discriminator = $guild_owner->discriminator;
$guild_member_count = $this->MESSAGE->channel->guild->member_count;

$query = 'INSERT INTO guilds (guild_id, name, icon, region, owner_id, owner_username, owner_discriminator, member_count) VALUES ("' . $guild_id . '", "' . $guild_name . '", "' . $guild_icon . '", "' . $guild_region . '", "' . $guild_owner_id . '", "' . $guild_owner_username . '", "' . $guild_owner_discriminator . '", "' . $guild_member_count . '")';

if (!$result = $database->MYSQL->query($query)) {
	echo "\n\n\n" . 'ERROR IN INSERTING GUILD' . "\n\n\n";

	return;
}

echo "\n\n\n" . '[NEW RECORD IN THE DATABASE]: GUILD!' . "\n\n\n";
?>