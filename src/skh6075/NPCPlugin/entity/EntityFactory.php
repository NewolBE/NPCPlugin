<?php


namespace skh6075\NPCPlugin\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ByteArrayTag;

class EntityFactory {

    /** @var array */
    public static $entities = [];
    
    
    
    public static function init (): void{
        $networkIds = [];
        $ref = new \ReflectionClass (EntityIds::class);
        foreach ($ref->getConstants () as $name => $value) {
            $networkIds [$name] = strtolower ($value);
        }
        foreach ($networkIds as $name => $entity) {
            self::$entities [$name] = strtolower ($entity);
        }
    }
    
    public static function createNPCHuman (Player $player, array $data): void{
        $nbt = Entity::createBaseNBT ($player, null, $player->yaw, $player->pitch);
        $nbt->setTag (new CompoundTag ('Skin', [
            new StringTag ('Name', $player->getSkin ()->getSkinId ()),
            new ByteArrayTag ('Data', $player->getSkin ()->getSkinData ()),
            new ByteArrayTag ('CapeData', $player->getSkin ()->getCapeData ()),
            new StringTag ('GeometryName', $player->getSkin ()->getGeometryName ()),
            new ByteArrayTag ('GeometryData', $player->getSkin ()->getGeometryData ())
        ]));
        $nbt->setString ('NPCData', json_encode ($data));
        
        $entity = Entity::createEntity ('NPCHuman', $player->level, $nbt);
        $entity->spawnToAll ();
        $entity->setNameTag (str_replace ('(n)', "\n", $data ['name']));
        $entity->setNameTagAlwaysVisible ();
        $entity->setScale ($data ['scale']);
    }
    
    public static function createNPCAnimal (Player $player, array $data): void{
        $nbt = Entity::createBaseNBT ($player, null, $player->yaw, $player->pitch);
        $nbt->setString ('NPCData', json_encode ($data));
        
        $entity = Entity::createEntity ('NPCAnimal', $player->level, $nbt);
        $entity->spawnToAll ();
        $entity->setNameTag (str_replace ('(n)', "\n", $data ['name']));
        $entity->setNameTagAlwaysVisible ();
        $entity->setScale ($data ['scale']);
        
        
    }
    
}