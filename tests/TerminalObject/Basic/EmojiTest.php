<?php

namespace League\CLImate\Tests\TerminalObject\Basic;

use League\CLImate\TerminalObject\Basic\Emoji;
use League\CLImate\Tests\TestBase;

class EmojiTest extends TestBase
{
    public function test_we_display_emoji()
    {
        $this->output->shouldReceive('emoji')->andReturn(true);

        $emojiTest = Emoji::TIMER_CLOCK . '  ' . 'Reticulating splines...';

        $this->shouldWrite("\e[m{$emojiTest}\e[0m");
        $this->shouldHavePersisted();

        $this->cli->emoji(Emoji::TIMER_CLOCK, 'Reticulating splines...');
    }
}