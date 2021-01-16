<?php
declare(strict_types=1);

namespace ipad54\shulkerbox;

use ipad54\shulkerbox\block\ShulkerBox;
use ipad54\shulkerbox\block\UndyedShulkerBox;
use ipad54\shulkerbox\tile\ShulkerBox as TileShulkerBox;
use ipad54\shulkerbox\item\ShulkerBox as ShulkerItem;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;

class Main extends PluginBase
{

    public function onLoad(): void
    {
        BlockFactory::registerBlock(new UndyedShulkerBox());
        BlockFactory::registerBlock(new ShulkerBox());
        Tile::registerTile(TileShulkerBox::class, ["ShulkerBox", "minecraft:shulker_box"]);
        ItemFactory::registerItem(new ShulkerItem(Block::UNDYED_SHULKER_BOX), true);
        ItemFactory::registerItem(new ShulkerItem(Block::SHULKER_BOX), true);
        Item::initCreativeItems();
    }
}