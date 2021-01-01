<?php


namespace skh6075\NPCPlugin\form\npcanimal;

use pocketmine\form\Form;
use pocketmine\Player;

use skh6075\NPCPlugin\NPCPlugin;
use skh6075\NPCPlugin\entity\EntityFactory;

class NPCAnimalSpawnForm implements Form {


    public function jsonSerialize (): array{
        return [
            "type" => "custom_form",
            "title" => "§l엔피시",
            "content" => [
                [
                    "type" => "input",
                    "text" => "- 엔피시 이름을 적어주세요."
                ],
                [
                    "type" => "dropdown",
                    "text" => "- 엔피시 종류를 선택해주세요.",
                    "options" => array_keys (EntityFactory::$entities)
                ],
                [
                    "type" => "input",
                    "text" => "- 엔피시 크기를 적어주세요.",
                    "default" => "1.0"
                ],
                [
                    "type" => "input",
                    "text" => "- 엔피시 명령어를 적어주세요.",
                    "default" => "x"
                ],
                [
                    "type" => "input",
                    "text" => "- 엔피시 메세지를 적어주세요.",
                    "default" => "x"
                ]
            ]
        ];
    }
    
    public function handleResponse (Player $player, $data): void{
        $name = $data [0] ?? "";
        $entity = "minecraft:" . strtolower (array_keys (EntityFactory::$entities) [$data [1]]) ?? "pig";
        $scale = (float) $data [2] ?? 1.0;
        $command = $data [3] ?? "";
        $message = $data [4] ?? "";
        
        if (trim ($name) === "" or trim ($command) === "" or trim ($message) === "") {
            $player->sendMessage (NPCPlugin::$prefix . '모든칸을 적어주셔야 합니다.');
            return;
        }
        $data = [
            'name' => $name,
            "entity" => $entity,
            'scale' => $scale,
            'command' => $command,
            'message' => $message,
            'nametag' => ''
        ];
        EntityFactory::createNPCAnimal ($player, $data);
        $player->sendMessage (NPCPlugin::$prefix . '엔피시를 소환 했습니다.');
    }
    
}