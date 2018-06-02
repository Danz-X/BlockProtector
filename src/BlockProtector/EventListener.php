<?php

namespace BlockProtector;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class EventListener implements Listener{

	/** @var Main */
	private $blockProtector;

	public function __construct(Main $blockProtector){
		$this->blockProtector = $blockProtector;
	}

	public function onBlockBreak(BlockBreakEvent $event) : void{
		if($this->blockProtector->checkInspect($event->getBlock(), $event->getPlayer())){
			$event->setCancelled();
		}elseif(!$event->isCancelled() and in_array($event->getPlayer()->getLevel()->getFolderName(), $this->blockProtector->getConfig()->get("worlds"))){
			$this->blockProtector->provider->log("broke", $event->getBlock(), $event->getPlayer());
		}
	}

	public function onBlockPlace(BlockPlaceEvent $event) : void{
		if($this->blockProtector->checkInspect($event->getBlock(), $event->getPlayer())){
			$event->setCancelled();
		}elseif(!$event->isCancelled() and in_array($event->getPlayer()->getLevel()->getFolderName(), $this->blockProtector->getConfig()->get("worlds"))){
			$this->blockProtector->provider->log("placed", $event->getBlock(), $event->getPlayer());
		}
	}

}