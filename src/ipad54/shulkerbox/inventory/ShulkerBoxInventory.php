<?php
declare(strict_types=1);

namespace ipad54\shulkerbox\inventory;

use ipad54\shulkerbox\tile\ShulkerBox;
use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

class ShulkerBoxInventory extends ContainerInventory
{

    protected $holder;

    public function __construct(ShulkerBox $tile)
    {
        parent::__construct($tile);
    }

    public function getName(): string
    {
        return "Shulker Box";
    }

    public function getDefaultSize(): int
    {
        return 27;
    }

    public function getNetworkType(): int
    {
        return WindowTypes::CONTAINER;
    }

    public function getHolder()
    {
        return $this->holder;
    }

    public function getOpenSound(): int
    {
        return LevelSoundEventPacket::SOUND_SHULKERBOX_OPEN;
    }

    public function getCloseSound(): int
    {
        return LevelSoundEventPacket::SOUND_SHULKERBOX_CLOSED;
    }

    public function onOpen(Player $who): void
    {
        parent::onOpen($who);
        if (count($this->getViewers()) === 1 and $this->getHolder()->isValid()) {
            $this->broadcastBlockEventPacket(true);
            $this->getHolder()->getLevelNonNull()->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), $this->getOpenSound());
        }
    }

    public function onClose(Player $who): void
    {
        if (count($this->getViewers()) === 1 and $this->getHolder()->isValid()) {
            $this->broadcastBlockEventPacket(false);
            $this->getHolder()->getLevelNonNull()->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), $this->getCloseSound());
        }
      parent::onClose($who);
    }

    public function broadcastBlockEventPacket(bool $isOpen): void
    {
        $holder = $this->getHolder();

        $pk = new BlockEventPacket();
        $pk->x = (int)$holder->x;
        $pk->y = (int)$holder->y;
        $pk->z = (int)$holder->z;
        $pk->eventType = 1;
        $pk->eventData = $isOpen ? 1 : 0;
        $holder->getLevelNonNull()->broadcastPacketToViewers($holder, $pk);
    }
}