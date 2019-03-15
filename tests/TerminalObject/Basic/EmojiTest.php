<?php

namespace League\CLImate\Tests\TerminalObject\Basic;

use League\CLImate\Exceptions\InvalidArgumentException;
use League\CLImate\TerminalObject\Basic\Emoji;
use League\CLImate\Tests\TestBase;

class EmojiTest extends TestBase
{
    public function it_will_display_emojis()
    {
        $this->output->shouldReceive('emoji')->andReturn(true);

        $emojiTest = '  ' . Emoji::TIMER_CLOCK . "\t" . 'Reticulating splines...';

        $this->shouldWrite("\e[m{$emojiTest}\e[0m");
        $this->shouldHavePersisted();

        $this->cli->emoji('Reticulating splines...', Emoji::TIMER_CLOCK);
    }

    public function it_will_throw_exception()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->cli->emoji('Reticulating splines...', 'Invalid Emoji!');
    }
}