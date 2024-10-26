<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\world\weather\WeatherType;
use function count;
use function mt_rand;
use function strtolower;

class WeatherCommand extends Command {

	public function __construct() {
		parent::__construct("weather", "Change the weather in the current world", "/weather <clear|rain|thunder>", ["setweather"]);
		$this->setPermission(DefaultPermissionNames::COMMAND_WEATHER);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
		if (!$this->testPermission($sender)) {
			return false;
		}

		if (!$sender instanceof Player) {
			$sender->sendMessage("This command can only be used in-game.");
			return false;
		}

		if (count($args) < 1) {
			$sender->sendMessage("Usage: /weather <clear|rain|thunder>");
			return false;
		}

		$weatherType = strtolower($args[0]);
		$world = $sender->getWorld();

		switch ($weatherType) {
			case "clear":
				$world->getWeatherManager()->setWeather(WeatherType::CLEAR, mt_rand(6000, 12000));
				$sender->sendMessage("Weather set to clear.");
				break;
			case "rain":
				$world->getWeatherManager()->setWeather(WeatherType::RAIN, mt_rand(6000, 12000));
				$sender->sendMessage("Weather set to rain.");
				break;
			case "thunder":
				$world->getWeatherManager()->setWeather(WeatherType::THUNDER, mt_rand(6000, 12000));
				$sender->sendMessage("Weather set to thunder.");
				break;
			default:
				$sender->sendMessage("Invalid weather type. Usage: /weather <clear|rain|thunder>");
				return false;
		}

		return true;
	}
}
