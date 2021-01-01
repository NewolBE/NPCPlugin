<?php


namespace skh6075\NPCPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\Player;

use skh6075\NPCPlugin\NPCPlugin;
use skh6075\NPCPlugin\form\NPCMenuForm;

class NPCCommand extends Command {


    public function __construct () {
        parent::__construct ('npc', 'npc 명령어 입니다.');
        $this->setPermission ("npc.permission");
    }
    
    public function execute (CommandSender $player, string $label, array $args): bool{
        if (!$player instanceof Player) {
            $player->sendMessage (NPCPlugin::$prefix . '인게임에서만 사용이 가능합니다.');
            return false;
        }
        if (!$player->hasPermission ($this->getPermission ())) {
            $player->sendMessage (NPCPlugin::$prefix . '당신은 이 명령어를 사용할 권한이 없습니다.');
            return false;
        }
        $player->sendForm (new NPCMenuForm ());
        return true;
    }
    
}