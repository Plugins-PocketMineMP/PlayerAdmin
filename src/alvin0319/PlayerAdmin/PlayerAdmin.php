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
namespace alvin0319\PlayerAdmin;

use alvin0319\PlayerAdmin\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\IPlayer;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PlayerAdmin extends PluginBase implements Listener, DeviceInfo{

	/** @var \SQLite3 */
	protected $mysql;

	protected $data = [];

	public static $prefix = "§b§l[PlayerAdmin] §r§7";

	private static $instance = null;

	public function onLoad(){
		self::$instance = $this;
	}

	public static function getInstance() : PlayerAdmin{
		return self::$instance;
	}

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->mysql = new \SQLite3($this->getDataFolder() . "PlayerAdmin.db");

		$this->mysql->exec("CREATE TABLE IF NOT EXISTS database(player, warn)");
		$res = $this->mysql->prepare("INSERT INTO database(warn) VALUE(:ban)");
		$res->bindValue(":ban", ["ban" => []]);
		$res->execute();
	}

	public function onDisable() : void{
		$this->mysql->close();
	}

	public function setWarnData(array $data) : void{
		$res = $this->mysql->prepare("SELECT warn FROM database");
		$data = [];
		while($info = mysqli_fetch_array($res->result_metadata())){
			$data[] = $info;
		}
		var_dump($data);
	}

	public function setPlayerData(array $data) : void{
	}

	public function handleReceivePacket(DataPacketReceiveEvent $event){
		$packet = $event->getPacket();

		if($packet instanceof LoginPacket){
			$data = [];
			$data["DeviceOS"] = $packet->clientData["DeviceOS"];
			$data["DeviceModel"] = $packet->clientData["DeviceModel"];
			$this->data[strtolower($packet->username)] = $data;
		}
	}

	public function handlePlayerLogin(PlayerLoginEvent $event){
		/*
		$player = $event->getPlayer();
		$this->updatePlayerInfo($player);

		if($this->database["warn"]["ban-count"] <= $this->getWarn($player)){
			$event->setKickMessage("You are banned.\n\nWarn count: " . $this->getWarn($player));
			$event->setCancelled();
		}
		*/
	}

	public function updatePlayerInfo(Player $player) : void{
		if(!isset($this->database["warn"]["player"][$player->getLowerCaseName()])){
			$this->database["warn"]["player"][$player->getLowerCaseName()] = 0;
		}
		$packetData = $this->data[$player->getLowerCaseName()];
		$this->database["player"][$player->getLowerCaseName()] = [
			"device" => self::DEVICES[$packetData["DeviceOS"]],
			"model" => $packetData["DeviceModel"],
			"address" => $player->getAddress()
		];
	}

	public function getWarn(IPlayer $player) : int{
		//return $this->database["warn"]["player"][strtolower($player->getName())];
		return 0;
	}

	public function addWarn(IPlayer $player, int $count, string $reason = "") : void{
		/*
		$this->database["warn"]["player"][strtolower($player->getName())] += $count;
		*/
		$this->getServer()->broadcastMessage(PlayerAdmin::$prefix . "Player " . $player->getName() . " received warn {$count}. reason: " . ($reason === "" ? "Admin Discretion" : $reason));
		$this->checkBan($player);
	}

	public function checkBan(IPlayer $player) : void{
		if(($player = $player->getPlayer()) instanceof Player){
			if($this->getWarn($player) >= $this->database["warn"]["ban-count"]){
				$player->kick("Banned by admin. Reason: warn exceeded.");
			}
		}
	}

	public function reduceWarn(IPlayer $player, int $count) : void{
		//$this->database["warn"]["player"][strtolower($player->getName())] -= $count;
	}

	public function getPlayerInfo(IPlayer $player) : ?array{
		//return isset($this->database["player"][strtolower($player->getName())]) ? $this->database["player"][strtolower($player->getName())] : null;
		return [];
	}

	public function hasPlayerInfo(IPlayer $player) : bool{
		return is_array($this->getPlayerInfo($player));
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($sender instanceof Player){
			$sender->sendForm(new MainForm());
		}
		return true;
	}
}
