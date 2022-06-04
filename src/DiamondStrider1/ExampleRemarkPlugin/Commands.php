<?php

declare(strict_types=1);

namespace DiamondStrider1\ExampleRemarkPlugin;

use DiamondStrider1\Remark\Command\Arg\enum;
use DiamondStrider1\Remark\Command\Arg\remaining;
use DiamondStrider1\Remark\Command\Arg\sender;
use DiamondStrider1\Remark\Command\Arg\text;
use DiamondStrider1\Remark\Command\Cmd;
use DiamondStrider1\Remark\Command\CmdConfig;
use DiamondStrider1\Remark\Command\Guard\permission;
use DiamondStrider1\Remark\Form\CustomFormElement\Input;
use DiamondStrider1\Remark\Form\CustomFormElement\Label;
use DiamondStrider1\Remark\Form\Forms;
use DiamondStrider1\Remark\Form\MenuFormElement\MenuFormButton;
use DiamondStrider1\Remark\Form\MenuFormElement\MenuFormImage;
use Generator;
use pocketmine\player\Player;

#[CmdConfig(
    name: 'myplugin',
    description: "Access my plugin through subcommands",
    aliases: ['mp'],
    // Multiple permissions are separated by `;`.
    // $command->setPermission() is called with this value
    permission: 'example.command;example.command.see',
)]
final class Commands
{
    /**
     * Sends a Custom Form, the result of which is
     * automatically deserialized into MySurveyForm
     *
     * @see MySurveyForm
     */
    // Register a command at /myplugin survey
    #[Cmd('myplugin', 'survey'), permission('example.command.survey')]
    // Require that the sender is a player, and a text argument "name".
    #[sender(), text()] // Every Arg maps to its own method parameter
    public function survey(Player $sender, string $name): Generator
    {
        // This is a generator function, and Remark will automatically
        // pass the generator to `Await::g2c()` to run it.

        // Send a custom form with multiple elements
        /** @var ?MySurveyForm $response */
        $response = yield from MySurveyForm::custom2gen($sender, "Want to fill out this form, $name?");
        if (null === $response) {
            $sender->sendMessage('You chose to skip the survey!');
        } else {
            $sender->sendMessage("You said your name is $response->name.");
        }

        // You can also create a custom form in a more
        // traditional way.
        $response = yield from Forms::custom2gen($sender, 'Another Custom Form', [
            new Label('What is your name?'),
            new Input('Your Name', 'name...')
        ]);
        if (null === $response) {
            $sender->sendMessage('You chose to skip the survey!');
        } else {
            $sender->sendMessage("You said your name is $response[1].");
        }
    }

    /**
     * Sends a Menu Form and Modal Form in an asynchronous loop.
     *
     * @param string[] $message
     */
    #[Cmd('myplugin', 'dance')] // /myplugin dance
    #[permission('example.command.dance')]
    // There is one Arg for every parameter.
    #[sender(), enum('dance', 'dig', 'mine'), remaining()]
    public function showOff(Player $sender, string $dance, array $message): Generator
    {
        $message = implode(' ', $message);
        $photoDirt = 'textures/blocks/dirt.png';
        $photoGold = 'textures/blocks/gold_block.png';
        $photoAwesome = 'https://unsplash.com/photos/3k9PGKWt7ik/download?ixid=MnwxMjA3fDB8MXxhbGx8fHx8fHx8fHwxNjU0MDg0MTAy&force=true&w=640';
        do {
            // Send a menu form with a list of buttons to choose from
            $choice = yield from Forms::menu2gen(
                $sender,
                "How do you like the dance, §a{$dance}§r?",
                "The message you sent will be logged:\n$message",
                [
                    new MenuFormButton('bland', new MenuFormImage('path', $photoDirt)),
                    new MenuFormButton('amazing', new MenuFormImage('path', $photoGold)),
                    new MenuFormButton('awesome', new MenuFormImage('url', $photoAwesome)),
                ]
            );

            // Send a modal form that results in true or false
            $isSure = yield from Forms::modal2gen($sender, 'Are you sure though?', 'Last chance to change your mind!');
        } while (!$isSure);
        $choice = ['bland', 'amazing', 'AWESOME'][$choice] ?? "Very Undecidable";
        $sender->sendMessage("You found the dance, §a{$dance}§r, §g{$choice}§r!");
    }
}
