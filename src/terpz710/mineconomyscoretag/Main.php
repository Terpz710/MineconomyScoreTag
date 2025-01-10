<?php

declare(strict_types=1);

namespace terpz710\mineconomyscoretag;

use pocketmine\plugin\PluginBase;

final class Main extends PluginBase {

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}