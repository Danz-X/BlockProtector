<?php

namespace BlockProtector;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class EventListener implements Listener{

    public function __construct(Main $blockProtector){
        $this->blockProtector = $blockProtector;
    }

    public function onBlockBreak(BlockBreakEvent $event){
        if($this->blockProtector->checkInspect($event->getBlock(), $event->getPlayer())){
            $event->setCancelled();
        }else{
            $this->blockProtector->log("broke", $event->getBlock(), $event->getPlayer());
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event){
        if($this->blockProtector->checkInspect($event->getBlock(), $event->getPlayer())){
            $event->setCancelled();
        }else{
            $this->blockProtector->log("placed", $event->getBlock(), $event->getPlayer());
        }
    }

}