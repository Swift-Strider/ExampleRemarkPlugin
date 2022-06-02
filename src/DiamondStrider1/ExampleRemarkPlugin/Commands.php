<?php

declare(strict_types=1);

namespace DiamondStrider1\ExampleRemarkPlugin;

use DiamondStrider1\Remark\Command\Arg\sender;
use DiamondStrider1\Remark\Command\Cmd;
use DiamondStrider1\Remark\Command\CmdConfig;
use DiamondStrider1\Remark\Form\Forms;
use Generator;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\player\Player;

#[CmdConfig('example', 'The example command')]
final class Commands
{
    #[Cmd('example'), sender]
    public function openForm(CommandSender $sender): Generator
    {
        if (!$sender instanceof Player) {
            // PM will catch this and send a usage message
            throw new InvalidCommandSyntaxException();
        }

        // We asynchronously wait for
        // the player to answer the form
        $answer = yield from Forms::modal2gen(
            $sender,
            'Confirm the day', 'Is today monday?'
        );
        $answer = $answer ? "§aYes" : "§cNo";
        $sender->sendMessage("You answered {$answer}§r.");
    }
}
