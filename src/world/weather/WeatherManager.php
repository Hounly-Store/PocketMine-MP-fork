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

namespace pocketmine\world\weather;

use pocketmine\event\world\WeatherChangeEvent;
use pocketmine\world\World;
use function array_rand;
use function mt_rand;

class WeatherManager {
	private World $world;
	private Weather $currentWeather;

	public function __construct(World $world) {
		$this->world = $world;
		$this->currentWeather = new Weather(WeatherType::CLEAR, mt_rand(6000, 12000)); // default
	}

	public function getCurrentWeather() : Weather {
		return $this->currentWeather;
	}

	public function updateWeather() : void {
		if ($this->currentWeather->getDuration() > 0) {
			$this->currentWeather->setDuration($this->currentWeather->getDuration() - 1);
		} else {
			$this->changeWeather();
		}
	}

	private function changeWeather() : void {
		$weatherTypes = [WeatherType::CLEAR, WeatherType::RAIN, WeatherType::THUNDER];
		$newWeather = $weatherTypes[array_rand($weatherTypes)];
		$duration = mt_rand(6000, 12000);

		$this->setWeather($newWeather, $duration);
	}

	public function setWeather(string $type, int $duration) : void {
		$this->currentWeather = new Weather($type, $duration);
		$event = new WeatherChangeEvent($this->world, $type);
		$this->world->getServer()->getPluginManager()->callEvent($event);
	}
}
