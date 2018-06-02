<?php

namespace BlockProtector\Providers;

use pocketmine\block\Block;
use pocketmine\Player;

interface Provider{

	/**
	 * @param Block $block
	 * @return array
	 */
	public function getLogsAt(Block $block) : array;

	/**
	 * @param string $action
	 * @param Block $block
	 * @param Player $player
	 * @return void
	 */
	public function log($action, Block $block, Player $player) : void;

	public function close() : void;

}