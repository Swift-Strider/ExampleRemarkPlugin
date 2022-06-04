<?php

declare(strict_types=1);

namespace DiamondStrider1\ExampleRemarkPlugin;

use DiamondStrider1\Remark\Form\CustomFormElement\Dropdown;
use DiamondStrider1\Remark\Form\CustomFormElement\Input;
use DiamondStrider1\Remark\Form\CustomFormElement\Label;
use DiamondStrider1\Remark\Form\CustomFormElement\Slider;
use DiamondStrider1\Remark\Form\CustomFormElement\StepSlider;
use DiamondStrider1\Remark\Form\CustomFormElement\Toggle;
use DiamondStrider1\Remark\Form\CustomFormResultTrait;

/**
 * By using CustomFormResultTrait, the static functions
 * `custom2gen()` and `custom2then()` are added. The
 * functions both resolve with a new instance of
 * `MySurveyForm` with properties filled in with a player's
 * response.
 */
final class MySurveyForm
{
    use CustomFormResultTrait;

    #[Label('This form will be logged!')]
    #[Dropdown('Best Game?', ['MyGame', 'YourGame'], -1)]
    public int $bestGame;
    #[Input('What is your name?')]
    public string $name;
    #[Slider('level', 0, 5)]
    public int $level;
    #[StepSlider('World:', ['mine', 'yours'])]
    public int $world;
    #[Label('Important!'), Toggle('On?', true), Label('Important^')]
    public bool $on;
}
