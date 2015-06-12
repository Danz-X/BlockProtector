<?php

namespace BlockProtector;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public $logs = [];
    public $inspect = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        @mkdir($this->getDataFolder());
        $logsPath = $this->getDataFolder()."logs/";
        @mkdir($logsPath);
        foreach(array_diff(scandir($logsPath), ['..', '.']) as $file){ //http://php.net/manual/en/function.scandir.php
            $name = substr($file, 0, strlen($file) - 4); //remove file extension .json to get the name of the player
            $this->logs[$name] = json_decode(file_get_contents($logsPath.$file), true);
        }
    }

    public function onDisable(){
        foreach($this->logs as $name => $logs){
            file_put_contents($this->getDataFolder()."logs/$name.json", json_encode($logs));
        }
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        if(strtolower($command->getName()) === "blockprotector"){
            if(!isset($args[0])){
                $sender->sendMessage("Use /bp inspect to enable inspector");
                return true;
            }
            $sub = array_shift($args);
            if(strtolower($sub) === "inspect" or strtolower($sub) === "i" or strtolower($sub) === "wand"){
                if(isset($this->inspect[$sender->getName()])){
                    unset($this->inspect[$sender->getName()]);
                    $sender->sendMessage("You disabled the inspector");
                    return true;
                }
                $this->inspect[$sender->getName()] = true;
                $sender->sendMessage("You enabled the inspector");
                $sender->sendMessage("Place or break blocks to see who built at its position");
                return true;
            }
            $sender->sendMessage("Strange argument ".$sub.", use /pb inspect");
            return true;
        }
        return true;
    }

    public function getLogsAt(Block $block){
        $l = [];
        foreach($this->logs as $player => $logs){
            foreach($logs as $log){
                if($log["x"] == $block->x and $log["y"] == $block->y and $log["z"] == $block->z and $log["level"] == $block->level->getName()){
                    $log["player"] = $player;
                    $l[] = $log;
                }
            }
        }
        return $l;
    }

    public function log($action, Block $block, Player $player){
        $this->logs[strtolower($player->getName())][] = [
            "action" => $action,
            "x" => $block->x,
            "y" => $block->y,
            "z" => $block->z,
            "level" => $block->level->getName(),
            "block" => $block->getName()
        ];
    }

    public function checkInspect(Block $block, Player $player){
        if($this->inspect[$player->getName()]){
            $logs = $this->getLogsAt($block);
            if(count($logs) === 0){
                $player->sendMessage("No logs found at this position");
            }else{
                foreach($logs as $log){
                    $player->sendMessage($log["player"]." ".$log["action"]." ".$log["block"]." here");
                }
            }
            return true;
        }
        return false;
    }

}