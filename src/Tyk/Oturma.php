<?php

namespace Tyk;

use pocketmine\block\Stair;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;

class Oturma extends PluginBase implements Listener{
    public $ciftTik = [];
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onTouch(PlayerInteractEvent $t){
        $o = $t->getPlayer();
        if($t->getBlock() instanceof Stair){
            if (!isset($this->ciftTik[$o->getName()])){
                $this->ciftTik[$o->getName()] = $this->tik();
                $o->sendTip("§bOturmak İçin Bir Kez Daha Tıkla");
                $o->sendTip("§eOturmak İçin Bir Kez Daha Tıkla");
            }
            if($this->tik() - $this->ciftTik[$o->getName()] < 0.5){
                $pk1 = new AddEntityPacket;
                $pk1->entityRuntimeId = 1881;
                $pk1->type = 95;
                $pk1->position = new Vector3($t->getBlock()->x + 0.45, $t->getBlock()->y + 1.5, $t->getBlock()->z + 0.1);
                $o->dataPacket($pk1);
                $pk = new SetEntityLinkPacket();
                $pk->link = new EntityLink(1881, $o->getId(), 1, 1);
                $o->dataPacket($pk);
                $this->getServer()->broadcastPacket($o->getLevel()->getPlayers(),$pk);
            }else{
                $this->ciftTik[$o->getName()] = $this->tik();
                $o->sendTip("§fOturmak İçin Bir Kez Daha Tıkla");
            }
        }
    }
    public function tik(){
        return array_sum(explode(' ',microtime()));
    }
}
