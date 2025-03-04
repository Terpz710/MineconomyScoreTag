<?php

declare(strict_types=1);

namespace terpz710\mineconomyscoretag;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\player\Player;

use pocketmine\Server;

use terpz710\mineconomy\Mineconomy;

use terpz710\mineconomy\event\MoneyBalanceChangeEvent;

use Ifera\ScoreHud\ScoreHud;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;

class EventListener implements Listener {

    protected function updateTag(Player|string $player) : void{
        if (class_exists(ScoreHud::class)) {
            if ($player instanceof Player) {
                $balance = Mineconomy::getInstance()->getFunds($player);
            } else {
                $balance = Mineconomy::getInstance()->getFunds($player);
                $player = Server::getInstance()->getPlayerExact($player);
            }

            if ($player instanceof Player) {
                $ev = new PlayerTagsUpdateEvent(
                    $player,
                    [
                        new ScoreTag("mineconomy.balance", number_format((float)$balance)),
                    ]
                );
                $ev->call();
            }
        }
    }

    public function join(PlayerJoinEvent $event) : void{
        $this->updateTag($event->getPlayer());
    }

    public function change(MoneyBalanceChangeEvent $event) : void{
        $this->updateTag($event->getPlayer());
    }

    public function resolve(TagsResolveEvent $event) : void{
        $player = $event->getPlayer();
        $tag = $event->getTag();

        $balance = Mineconomy::getInstance()->hasBalance($player)
            ? Mineconomy::getInstance()->getFunds($player)
            : 0;

        match ($tag->getName()) {
            "mineconomy.balance" => $tag->setValue(number_format((float)$balance)),
            default => null,
        };
    }
}
