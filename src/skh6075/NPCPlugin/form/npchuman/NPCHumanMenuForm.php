<?php


namespace skh6075\NPCPlugin\form\npchuman;

use pocketmine\form\Form;
use pocketmine\Player;

class NPCHumanMenuForm implements Form {


    public function jsonSerialize (): array{
        return [
            "type" => "form",
            "title" => "§l엔피시",
            "content" => "",
            "buttons" => [
                [ "text" => "§l엔피시 소환§r\n엔피시를 소환 합니다." ]
            ]
        ];
    }
    
    public function handleResponse (Player $player, $data): void{
        if (is_null ($data)) {
            return;
        }
        if ($data === 0) {
            $player->sendForm (new NPCHumanSpawnForm ());
        }
    }
    
}