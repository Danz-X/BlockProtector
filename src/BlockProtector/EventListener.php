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
        /*$file = $this->blockProtector->getDataFolder()."logs/".strtolower($event->getPlayer()->getName()).".json"; //This should not be necessary
        if(!file_exists($file)){
            file_put_contents($file, "[]");
        }*/
        $this->blockProtector->logs[strtolower($event->getPlayer()->getName())][] = [
            "action" => "broke",
            "x" => $event->getBlock()->x,
            "y" => $event->getBlock()->y,
            "z" => $event->getBlock()->z,
            "level" => $event->getBlock()->level->getName()
        ];
    }

    public function onBlockPlace(BlockPlaceEvent $event){
        $this->blockProtector->logs[strtolower($event->getPlayer()->getName())][] = [
            "action" => "placed",
            "x" => $event->getBlock()->x,
            "y" => $event->getBlock()->y,
            "z" => $event->getBlock()->z,
            "level" => $event->getBlock()->level->getName()
        ];
    }

}