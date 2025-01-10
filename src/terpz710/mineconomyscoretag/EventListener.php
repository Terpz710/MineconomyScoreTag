<?php

declare(strict_types=1);

namespace terpz710\mineconomyscoretag;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\player\Player;

use terpz710\mineconomy\Mineconomy;

use terpz710\mineconomy\event\BalanceChangeEvent;

use Ifera\ScoreHud\ScoreHud;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;

class EventListener implements Listener {

    protected function updateTag(Player $player) {
        if (class_exists(ScoreHud::class)) {
            $eco = Mineconomy::getInstance();
            $name = $player->getName();
            $balance = $eco->getBalance($name);

            $ev = new PlayerTagsUpdateEvent(
                $player,
                [
                    new ScoreTag("mineconomy.balance", (string)$balance),
                ]
            );
            $ev->call();
        }
    }

    public function onJoin(PlayerJoinEvent $event) : void{
        $this->updateTag($event->getPlayer());
    }

    public function onBalanceChange(BalanceChangeEvent $event) : void{
        $this->updateTag($event->getPlayer());
    }

    public function onTagResolve(TagsResolveEvent $event) : void{
        $player = $event->getPlayer();
        $tag = $event->getTag();
        $eco = Mineconomy::getInstance();
        $name = $player->getName();
        $balance = $eco->getBalance($name);

        match ($tag->getName()) {
            "mineconomy.balance" => $tag->setValue((string)$balance),
            default => null,
        };
    }
}