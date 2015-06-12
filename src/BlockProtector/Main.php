<?php

namespace BlockProtector;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public $logs = [];

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

}