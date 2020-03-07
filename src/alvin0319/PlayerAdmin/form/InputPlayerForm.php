<?php

/*
 *       _       _        ___ _____ _  ___
 *   __ _| |_   _(_)_ __  / _ \___ // |/ _ \
 * / _` | \ \ / / | '_ \| | | ||_ \| | (_) |
 * | (_| | |\ V /| | | | | |_| |__) | |\__, |
 *  \__,_|_| \_/ |_|_| |_|\___/____/|_|  /_/
 *
 * Copyright (C) 2020 alvin0319
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);
namespace alvin0319\PlayerAdmin\form;

use alvin0319\PlayerAdmin\PlayerAdmin;
use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;

class InputPlayerForm implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "custom_form",
			"title" => "Select Player",
			"content" => [
				[
					"type" => "input",
					"text" => "Write the nickname of the player you want to view info about."
				]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			if(($info = PlayerAdmin::getInstance()->getPlayerInfo(Server::getInstance()->getOfflinePlayer($data[0] ?? ""))) !== null){
				$player->sendForm(new PlayerInfoForm($data[0]));
			}else{
				$player->sendMessage(PlayerAdmin::$prefix . "Player {$data[0]}'s data could not be found.");
			}
		}
	}
}