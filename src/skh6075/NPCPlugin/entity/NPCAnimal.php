<?php


namespace skh6075\NPCPlugin\entity;

use pocketmine\entity\Monster;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;

class NPCAnimal extends Monster {

    const NETWORK_ID = 5877;
    
    public $width = 0.61;
    
    public $height = 1.8;
    
    protected $data = [];
    
    protected $target = null;
    
    
    
    public function getName (): string{
        return "NPCAnimal";
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
    
    public function entityBaseTick (int $tickDiff = 1): bool{
        $hasTick = parent::entityBaseTick ($tickDiff);
        if ($tickDiff % 4 === 0) {
            $this->look ();
        }
        return $hasTick;
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
        if ($this->target === null) {
            $distance = 100;
            foreach ($this->getRadiusInPlayers () as $player) {
                if ($distance > $this->distance ($player)) {
                    $distance = $this->distance ($player);
                    $this->target = $player;
                }
            }
        } else if ($this->target instanceof Player) {
            if ($this->target->level->getFolderName () === $this->level->getFolderName ()) {
                if ($this instanceof Vector3 and $this->target instanceof Vector3) {
                    if ($this->target->distance ($this) > 5) {
                        $this->target = null;
                    } else {
                        $this->lookAt ($this->target);
                    }
                } else {
                    $this->target = null;
                }
            } else {
                $this->target;
            }
        }
    }
    
    public function sendSpawnPacket (Player $player): void{
        $packet = new AddActorPacket ();
        $packet->entityRuntimeId = $this->getId ();
        $packet->type = strtolower ($this->data ['entity']);
        $packet->metadata = $this->getDataPropertyManager ()->getAll ();
        $packet->yaw = $this->yaw;
        $packet->pitch = $this->pitch;
        $packet->motion = $this->getMotion ();
        $packet->position = $this->getPosition ();
        $player->sendDataPacket ($packet);
    }
    
    public function getDrops (): array{
        return [];
    }
}