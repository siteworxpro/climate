<?php

namespace League\CLImate\Tests;

use League\CLImate\Exceptions\InvalidArgumentException;

class TableTest extends TestBase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_a_basic_table()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'Cell 1',
                    'Cell 2',
                    'Cell 3',
                    'Cell 4',
                ],
        ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_an_array_of_objects_table()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m=====================================\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                (object) [
                    'cell1' => 'Cell 1',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_output_an_array_of_associative_arrays_table()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m=====================================\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'cell1' => 'Cell 1',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_persist_a_style_on_the_table()
    {
        $this->shouldWrite("\e[31m-------------------------------------\e[0m");
        $this->shouldWrite("\e[31m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[31m=====================================\e[0m");
        $this->shouldWrite("\e[31m| Cell 1 | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[31m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->redTable([
                [
                    'cell1' => 'Cell 1',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_handle_tags_within_the_data()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m=====================================\e[0m");
        $this->shouldWrite("\e[m| Cell \e[31m1\e[0m | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'cell1' => 'Cell <red>1</red>',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_handle_multi_byte_characters()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m=====================================\e[0m");
        $this->shouldWrite("\e[m| Cell Ω | Cell 2 | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'cell1' => 'Cell Ω',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_handle_the_same_value_more_than_once()
    {
        $this->shouldWrite("\e[m-------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2  | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m=====================================\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell 2 | Cell 3 | Cell 3 |\e[0m");
        $this->shouldWrite("\e[m-------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'cell1' => 'Cell 1',
                    'cell2' => 'Cell 2',
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 3',
                ],
            ]);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testTableWithPrefix1()
    {
        $this->shouldWrite("\e[m\t-----------\e[0m");
        $this->shouldWrite("\e[m\t| Field 1 |\e[0m");
        $this->shouldWrite("\e[m\t===========\e[0m");
        $this->shouldWrite("\e[m\t| Value 1 |\e[0m");
        $this->shouldWrite("\e[m\t-----------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
            ["Field 1"   =>  "Value 1"],
        ], "\t");
    }


    /**
     * Ensure a sensible error is thrown when invalid items are passed.
     */
    public function testInvalidData1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid table data, you must pass an array of arrays or objects");

        $this->cli->table([
            "whoops",
        ]);
    }

    public function testTableWithNewline()
    {
        $this->shouldWrite("\e[m-----------------------------------\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m|        | 2    |        |        |\e[0m");
        $this->shouldWrite("\e[m-----------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'Cell 1',
                    "Cell\n2",
                    'Cell 3',
                    'Cell 4',
                ],
        ]);
    }

    public function testTableWithNewlineAndObjects()
    {
        $this->shouldWrite("\e[m------------------------------------\e[0m");
        $this->shouldWrite("\e[m| cell1  | cell2 | cell3  | cell4  |\e[0m");
        $this->shouldWrite("\e[m====================================\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell  | Cell 3 | Cell 4 |\e[0m");
        $this->shouldWrite("\e[m|        | 2     |        |        |\e[0m");
        $this->shouldWrite("\e[m------------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                (object) [
                    'cell1' => 'Cell 1',
                    'cell2' => "Cell\n2",
                    'cell3' => 'Cell 3',
                    'cell4' => 'Cell 4',
                ],
            ]);
    }

    public function testTableWithMultipleNewlines()
    {
        $this->shouldWrite("\e[m---------------------------------\e[0m");
        $this->shouldWrite("\e[m| Cell 1 | Cell | Cell   | Cell |\e[0m");
        $this->shouldWrite("\e[m|        | 2    | 3      | 4    |\e[0m");
        $this->shouldWrite("\e[m|        |      | Cell 3 | Cell |\e[0m");
        $this->shouldWrite("\e[m|        |      |        | 4    |\e[0m");
        $this->shouldWrite("\e[m---------------------------------\e[0m");

        $this->shouldHavePersisted();

        $this->cli->table([
                [
                    'Cell 1',
                    "Cell\n2",
                    "Cell\n3\nCell 3",
                    "Cell\n4\nCell\n4",
                ],
        ]);
    }
}
