<?php
declare(strict_types=1);

namespace ipad54\shulkerbox\tile;

use ipad54\shulkerbox\inventory\ShulkerBoxInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Nameable;
use pocketmine\tile\NameableTrait;
use pocketmine\tile\Spawnable;

class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable
{
    use NameableTrait, ContainerTrait;

    public const SHULKER_BOX = "ShulkerBox";

    protected $facing = self::SIDE_UP;
    protected $inventory;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
    }

    protected static function createAdditionalNBT(CompoundTag $nbt, Vector3 $pos, ?int $face = null, ?Item $item = null, ?Player $player = null): void
    {
        if ($face === null) {
            $face = 1;
        }
        $nbt->setByte("facing", $face);
    }

    public function getDefaultName(): string
    {
        return "Shulker Box";
    }

    public function close(): void
    {
        if ($this->isClosed()) {
            $this->inventory->removeAllViewers(true);
            $this->inventory = null;
            parent::close();
        }
    }

    public function getRealInventory()
    {
        return $this->inventory;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function getFacing(): int
    {
        return $this->facing;
    }

    public function setFacing(int $face): void
    {
        if ($face < 0 or $face > 5) {
            throw new \InvalidArgumentException("Facing must be in range 0-5, not " . $face);
        }
        $this->facing = $face;
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        $nbt->setByte("facing", $this->facing);
    }

    protected function readSaveData(CompoundTag $nbt): void
    {
        $this->loadName($nbt);
        $this->inventory = new ShulkerBoxInventory($this);
        $this->loadItems($nbt);
        $this->facing = $nbt->getByte("facing", 1);
    }

    protected function writeSaveData(CompoundTag $nbt): void
    {
        $this->saveName($nbt);
        $this->saveItems($nbt);
        $nbt->setByte("facing", $this->facing);
    }
}