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

class MainForm implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "form",
			"title" => PlayerAdmin::getInstance()->getDescription()->getName() . " v" . PlayerAdmin::getInstance()->getDescription()->getVersion(),
			"content" => "Select the type.",
			"buttons" => [
				["text" => "Exit"],
				["text" => "Find in Online Players"],
				["text" => "Find by typing"]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			switch($data){
				case 0:
					// exit
					break;
				case 1:
					$player->sendForm(new SelectPlayerForm());
					break;
				case 2:
					$player->sendForm(new InputPlayerForm());
					break;
			}
		}
	}
}