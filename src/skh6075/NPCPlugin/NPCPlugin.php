<?php


namespace skh6075\NPCPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;

use skh6075\NPCPlugin\command\NPCCommand;
use skh6075\NPCPlugin\entity\EntityFactory;
use skh6075\NPCPlugin\entity\NPCHuman;
use skh6075\NPCPlugin\entity\NPCAnimal;

class NPCPlugin extends PluginBase {

    public static $prefix = '§l§b[알림]§r§7 ';
    
    private static $instance = null;
    
    
    public static function getInstance (): ?NPCPlugin{
        return self::$instance;
    }
    
    public function onLoad (): void{
        self::$instance = $this;
        
        EntityFactory::init ();
        Entity::registerEntity (NPCHuman::class, true, [ 'NPCHuman' ]);
        Entity::registerEntity (NPCAnimal::class, true, [ 'NPCAnimal' ]);
    }
    
    public function onEnable (): void{
        $this->getServer()->getCommandMap ()->register(strtolower($this->getName()), new NPCCommand ());
    }
    
}