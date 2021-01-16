<?php
declare(strict_types=1);

namespace ipad54\shulkerbox\block;

use ipad54\shulkerbox\tile\ShulkerBox as TileShulkerBox;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;
use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Container;
use pocketmine\tile\Tile;

class ShulkerBox extends Transparent
{
    protected $id = self::SHULKER_BOX;

    public function __construct(int $meta = 0)
    {
        $this->meta = $meta;
    }

    public function getBlastResistance(): float
    {
        return 30;
    }

    public function getHardness(): float
    {
        return 2;
    }

    public function getToolType(): int
    {
        return BlockToolType::TYPE_PICKAXE;
    }

    public function getName(): string
    {
        return ColorBlockMetaHelper::getColorFromMeta($this->meta) . " Shulker Box";
    }

    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
    {
        $this->getLevelNonNull()->setBlock($this, $this, true, true);
        $nbt = TileShulkerBox::createNBT($this, $face, $item, $player);
        $items = $item->getNamedTag()->getTag(Container::TAG_ITEMS);
        if ($items != null) {
            $nbt->setTag($items);
        }
        Tile::createTile(TileShulkerBox::SHULKER_BOX, $this->getLevelNonNull(), $nbt);
        return true;
    }

    public function onBreak(Item $item, Player $player = null): bool
    {
        $t = $this->getLevelNonNull()->getTile($this);
        if ($t instanceof TileShulkerBox) {
            $item = ItemFactory::get($this->id, $this instanceof UndyedShulkerBox ? 0 : $this->meta);
            $nbt = clone $item->getNamedTag();
            $nbt->setTag($t->getCleanedNBT()->getTag(Container::TAG_ITEMS));
            $item->setNamedTag($nbt);
            $this->getLevelNonNull()->dropItem($this->add(0.5, 0.5, 0.5), $item);
            $t->getInventory()->clearAll();
            $t->close();
        }
        $this->getLevelNonNull()->setBlock($this, new Air(), true, true);
        return true;
    }

    public function onActivate(Item $item, Player $player = null): bool
    {
        if ($player instanceof Player) {
            $t = $this->getLevelNonNull()->getTile($this);
            $shulker = null;
            if ($t instanceof TileShulkerBox) {
                $shulker = $t;
            } else {
                $shulker = Tile::createTile(TileShulkerBox::SHULKER_BOX, $this->getLevelNonNull(), TileShulkerBox::createNBT($this));
                if (!($shulker instanceof TileShulkerBox)) {
                    return true;
                }
            }
            if (
                !$this->getSide(Vector3::SIDE_UP)->isTransparent() or
                !$shulker->canOpenWith($item->getCustomName())
            ) {
                return true;
            }
            $player->addWindow($shulker->getInventory());
        }
        return true;
    }

    public function getDrops(Item $item): array
    {
        return [];
    }
}