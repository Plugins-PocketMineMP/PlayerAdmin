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

class PlayerInfoForm implements Form{

	protected $username;

	public function __construct(string $username){
		$this->username = $username;
	}

	public function jsonSerialize() : array{
		$off = Server::getInstance()->getOfflinePlayer($this->username);
		$data = PlayerAdmin::getInstance()->getPlayerInfo($off);
		return [
			"type" => "form",
			"title" => "Manage player " . $off->getName(),
			"content" => "Player {$off->getName()}'s info\n\nAddress: {$data["address"]}\n\nDeviceOS: {$data["device"]}\n\nDeviceModel: {$data["model"]}\n\nWarn: " . PlayerAdmin::getInstance()->getWarn($off),
			"buttons" => [
				["text" => "Exit"],
				["text" => "Add warn"],
				["text" => "Reduce warn"],
				["text" => "Ban"],
				["text" => "IP Ban"]
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
					// add warn
					$player->sendForm(new AddWarnForm($this->username));
					break;
				case 2:
					// reduce warn
					$player->sendForm(new ReduceWarnForm($this->username));
					break;
				case 3:
					// ban
					$target = Server::getInstance()->getOfflinePlayer($this->username);
					$target->setBanned(true);
					$player->sendMessage(PlayerAdmin::$prefix . "Successfully banned.");
					break;
				case 4:
					// ip ban
					$target = Server::getInstance()->getOfflinePlayer($this->username);
					$info = PlayerAdmin::getInstance()->getPlayerInfo($target);
					Server::getInstance()->getIPBans()->addBan($info["address"], "Automatic banned by " . PlayerAdmin::getInstance()->getDescription()->getName() . " v" . PlayerAdmin::getInstance()->getDescription()->getVersion());
					Server::getInstance()->getNetwork()->blockAddress($info["address"], -1);
					$player->sendMessage(PlayerAdmin::$prefix . "Successfully banned.");
					$this->checkBan($info["address"]);
					break;
			}
		}
	}

	private function checkBan(string $address) : void{
		foreach(array_values(Server::getInstance()->getOnlinePlayers()) as $player){
			if($player->getAddress() === $address){
				$player->kick("IP Banned.");
			}
		}
	}
}