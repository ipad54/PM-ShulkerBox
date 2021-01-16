<?php
declare(strict_types=1);

namespace ipad54\shulkerbox\item;

use ipad54\shukerbox\block\UndyedShulkerBox;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Item;

class ShulkerBox extends Item
{
    public function __construct(int $id, int $meta = 0)
    {
        parent::__construct($id, $meta, $id === Block::UNDYED_SHULKER_BOX ? "Undyed Shulker Box" : ColorBlockMetaHelper::getColorFromMeta($meta)." Shulker Box");
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }

    public function getBlock(): Block
    {
        return BlockFactory::get($this->id, $this->meta);
    }
}