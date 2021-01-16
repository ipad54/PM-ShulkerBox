<?php
declare(strict_types=1);

namespace ipad54\shulkerbox\block;


class UndyedShulkerBox extends ShulkerBox
{
    protected $id = self::UNDYED_SHULKER_BOX;

    public function getName(): string
    {
        return "Undyed Shulker Box";
    }
}