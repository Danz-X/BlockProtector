<?php

namespace BlockProtector;

use BlockProtector\Providers\JsonProvider;
use BlockProtector\Providers\SQLite3Provider;
use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    public $inspect = [];
    /**@var Providers\Provider*/
    public $provider;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder()."logs/");
        $provider = strtolower((new Config($this->getDataFolder()."config.yml", Config::YAML, ["provider" => "json"]))->get("provider"));
        if($provider === "json"){
            $this->provider = new JsonProvider($this);
            $this->getLogger()->info("Data provider set to json");
        }elseif($provider === "sqlite3"){
            $this->provider = new SQLite3Provider($this);
            $this->getLogger()->info("Data provider set to sqlite3");
        }else{
            $this->provider = new JsonProvider($this);
            $this->getLogger()->info("Data provider set to json");
        }
    }

    public function onDisable(){
        $this->provider->close();
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

    public function checkInspect(Block $block, Player $player){
        if(isset($this->inspect[$player->getName()])){
            $logs = $this->provider->getLogsAt($block);
            if(count($logs) === 0){
                $player->sendMessage("No logs found at this position");
            }else{
                foreach($logs as $log){
                    $player->sendMessage("[Log] ".$log["player"]." ".$log["action"]." ".$log["block"]." here");
                }
            }
            return true;
        }
        return false;
    }

}