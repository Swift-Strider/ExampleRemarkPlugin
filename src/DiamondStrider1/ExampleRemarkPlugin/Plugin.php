<?php

declare(strict_types=1);

namespace DiamondStrider1\ExampleRemarkPlugin;

use DiamondStrider1\Remark\Remark;
use pocketmine\plugin\PluginBase;

final class Plugin extends PluginBase
{
    public function onEnable(): void
    {
        Remark::command($this, new Commands());
        Remark::activate($this);
    }
}
