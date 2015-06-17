<?php

namespace BlockProtector\Providers;

use pocketmine\block\Block;
use pocketmine\Player;

interface Provider{

    /**
     * @param Block $block
     * @return array
     */
    public function getLogsAt(Block $block);

    /**
     * @param string $action
     * @param Block $block
     * @param Player $player
     */
    public function log($action, Block $block, Player $player);

    public function close();

}