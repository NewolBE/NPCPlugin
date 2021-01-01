<?php


namespace skh6075\NPCPlugin\form;

use pocketmine\form\Form;
use pocketmine\Player;

use skh6075\NPCPlugin\form\npchuman\NPCHumanMenuForm;
use skh6075\NPCPlugin\form\npcanimal\NPCAnimalMenuForm;

class NPCMenuForm implements Form {


    public function jsonSerialize (): array{
        return [
            "type" => "form",
            "title" => "§l엔피시",
            "content" => "\n원하시는 메뉴를 선택 해주세요.",
            "buttons" => [
                [ "text" => "§l일반 엔피시§r\n일반 엔피시 메뉴 입니다." ],
                [ "text" => "§l동물 엔피시§r\n동물 엔피시 메뉴 입니다." ]
            ]
        ];
    }
    
    public function handleResponse (Player $player, $data): void{
        if (is_null ($data)) {
            return;
        }
        if ($data === 0) {
            $player->sendForm (new NPCHumanMenuForm ());
        } else if ($data === 1) {
            $player->sendForm (new NPCAnimalMenuForm ());
        }
    }
    
}