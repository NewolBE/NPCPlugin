<?php


namespace skh6075\NPCPlugin\entity;

use pocketmine\entity\Human;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\Server;

class NPCHuman extends Human {

    /** @var array */
    protected $data = [];
    
    /** @var Player|null */
    protected $target = null;
    
    
    public function __construct (Level $level, CompoundTag $nbt) {
        parent::__construct ($level, $nbt);
    }
    
    public function initEntity (): void{
        parent::initEntity ();
        $this->data = json_decode ($this->namedtag->getString ('NPCData'), true);
        
        $this->setScale ((float) $this->data ['scale']);
        $this->setNameTag (str_replace ('(n)', "\n", $this->data ['name']));
        $this->setNameTagAlwaysVisible ();
    }
    
    public function saveNBT (): void{
        parent::saveNBT ();
        $this->namedtag->setString ('NPCData', json_encode ($this->data));
    }
    
    public function attack (EntityDamageEvent $source): void{
        $source->setCancelled (true);
        if ($source instanceof EntityDamageByEntityEvent) {
            /** @var Player $player */
            if (($player = $source->getDamager ()) instanceof Player) {
                if ($player->isOp ())
                    if ($player->isSneaking ()) {
                        $this->close ();
                        return;
                    }
                if (($message = $this->data ['message'] ?? "x") !== "x")
                    $player->sendMessage ($message);
                if (($command = $this->data ['command'] ?? "x") !== "x")
                    Server::getInstance ()->getCommandMap ()->dispatch ($player, $command);
            }
        }
    }
    
    public function getRadiusInPlayers (int $radius = 6): array{
        $arr = [];
        foreach ($this->level->getPlayers () as $player) {
            if ($player->distance ($this) < $radius)
                $arr [] = $player;
        }
        return $arr;
    }
    
    public function look (): void{
        $player = null;
        $distance = 100;

        foreach ($this->level->getPlayers() as $players) {
            if ($players->distance($this) <= $distance) {
                $player = $players;
                $distance = $players->distance($this);
            }
        }
        if ($player instanceof Player) {
            if ($player->isOnline()) {
                $this->lookAt($player);
            }
        }
    }
    
    public function onUpdate (int $currentTick): bool{
        $hasUpdate = parent::onUpdate ($currentTick);
        if ($currentTick % 4 === 0) {
            $this->look ();
        }
        return $hasUpdate;
    }

}