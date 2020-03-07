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

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;

class SelectPlayerForm implements Form{

	/** @var Player[] */
	protected $players = [];

	public function jsonSerialize() : array{
		$this->players = array_values(Server::getInstance()->getOnlinePlayers());
		return [
			"type" => "form",
			"title" => "Select Player",
			"content" => "",
			"buttons" => array_map(function(Player $player) : array{
				return ["text" => $player->getName()];
			}, $this->players)
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			$target = $this->players[$data];
			if(!$target->isConnected()){
				$target = Server::getInstance()->getOfflinePlayer($target->getName());
			}
			$player->sendForm(new PlayerInfoForm($target->getName()));
		}
	}
}