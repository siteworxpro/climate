<?php

namespace League\CLImate\Tests\TerminalObject\Basic;

use League\CLImate\Tests\TestBase;

class BorderTest extends TestBase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_a_basic_border()
    {
        $this->shouldWrite("\e[m" . str_repeat('-', $this->util->width() ?: 100) . "\e[0m");
        $this->shouldHavePersisted();
        $this->cli->border();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_a_border_with_a_different_character()
    {
        $this->shouldWrite("\e[m" . str_repeat('@', $this->util->width() ?: 100) . "\e[0m");
        $this->shouldHavePersisted();
        $this->cli->border('@');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_a_border_with_a_different_length()
    {
        $this->shouldWrite("\e[m" . str_repeat('-', 60) . "\e[0m");
        $this->shouldHavePersisted();
        $this->cli->border('-', 60);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_a_border_with_an_odd_length_character_and_still_be_the_correct_length()
    {
        $this->shouldWrite("\e[m-*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*\e[0m");
        $this->shouldHavePersisted();
        $this->cli->border('-*-', 50);
    }
}
