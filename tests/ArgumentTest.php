<?php

namespace League\CLImate\Tests;

use League\CLImate\Argument\Argument;
use League\CLImate\Exceptions\InvalidArgumentException;
use League\CLImate\Exceptions\UnexpectedValueException;

class ArgumentTest extends TestBase
{
    /** @test */
    public function it_throws_an_exception_when_setting_an_unknown_cast_type()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("An argument may only be cast to the data type 'string', 'int', 'float', or 'bool'.");

        Argument::createFromArray('invalid-cast-type', [
            'castTo' => 'invalid',
        ]);
    }

    /** @test */
    public function it_throws_an_exception_when_building_arguments_from_an_unknown_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Please provide an argument name or object.");

        $this->cli->arguments->add(new \stdClass);
    }

    protected function getFullArguments()
    {
        return [
            'only-short-prefix' => [
                'prefix'      => 's',
                'description' => 'Only short prefix',
            ],
            'only-long-prefix' => [
                'longPrefix'  => 'long',
                'description' => 'Only long prefix',
            ],
            'both-prefixes' => [
                'prefix'      => 'b',
                'longPrefix'  => 'both',
                'description' => 'Both short and long prefixes',
            ],
            'no-prefix' => [
                'description' => 'Not defined by a prefix',
            ],
            'defined-only' => [
                'prefix'      => 'd',
                'longPrefix'  => 'defined',
                'description' => 'True when defined',
                'noValue'     => true,
            ],
            'required' => [
                'prefix'      => 'r',
                'description' => 'Required',
                'required'    => true,
            ],
            'default-value' => [
                'prefix'       => 'v',
                'description'  => 'Has a default value',
                'defaultValue' => 'test',
            ],
            'default-value2' => [
                'prefix'       => 'x',
                'description'  => 'Has also a default value',
                'defaultValue' => ['test2', 'test3'],
            ],
        ];
    }


    public function testCanCastToString(): void
    {
        $argument = Argument::createFromArray('test', [
            'castTo' => 'string',
        ]);

        $argument->setValue('a string');
        self::assertSame('a string', $argument->value());
    }


    public function testCanCastToInteger(): void
    {
        $argument = Argument::createFromArray('test', [
            'castTo' => 'int',
        ]);

        $argument->setValue('1234');
        self::assertSame(1234, $argument->value());
    }


    public function testCanCastToFloat(): void
    {
        $argument = Argument::createFromArray('test', [
            'castTo' => 'float',
        ]);

        $argument->setValue('12.34');
        self::assertSame(12.34, $argument->value());
    }


    public function testCanCastToBool(): void
    {
        $argument = Argument::createFromArray('test', [
            'castTo' => 'bool',
        ]);

        $argument->setValue('1');
        self::assertSame(true, $argument->value());
    }


    /** @test */
    public function it_casts_to_bool_when_defined_only()
    {
        $argument = Argument::createFromArray('invalid-cast-type', [
            'noValue' => true,
        ]);

        $this->assertEquals('bool', $argument->castTo());
    }

    /** @test */
    public function it_builds_arguments_from_a_single_array()
    {
        // Test Description
        //
        // Usage: test-script [-b both-prefixes, --both both-prefixes] [-d, --defined] [--long only-long-prefix] [-r required] [-s only-short-prefix] [-v default-value (default: test)] [-x default-value2 (defaults: test2, test3)] [no-prefix]
        //
        // Required Arguments:
        //     -r required
        //         Required
        //
        // Optional Arguments:
        //     -b both-prefixes, --both both-prefixes
        //         Both short and long prefixes
        //     -d, --defined
        //         True when defined
        //     -s only-short-prefix
        //         Only short prefix
        //     --long only-long-prefix
        //         Only long prefix
        //     -v default-value (default: test)
        //         Has a default value
        //     -x default-value2 (defaults: test2, test3)
        //         Has also a default value
        //     no-prefix
        //         Not defined by a prefix

        $this->output->shouldReceive("sameLine");
        $this->shouldWrite("\e[mTest Description\e[0m");
        $this->shouldWrite("\e[m\e[0m");
        $this->shouldWrite("\e[mUsage: test-script "
                            . "[-b both-prefixes, --both both-prefixes] [-d, --defined] "
                            . "[--long only-long-prefix] [-r required] [-s only-short-prefix] "
                            . "[-v default-value (default: test)] [-x default-value2 (defaults: test2, test3)] [no-prefix]\e[0m");

        $this->shouldWrite("\e[m\e[0m");
        $this->shouldWrite("\e[mRequired Arguments:\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-r required\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mRequired\e[0m");

        $this->shouldWrite("\e[m\e[0m");
        $this->shouldWrite("\e[mOptional Arguments:\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-b both-prefixes, --both both-prefixes\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mBoth short and long prefixes\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-d, --defined\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mTrue when defined\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m--long only-long-prefix\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mOnly long prefix\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-s only-short-prefix\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mOnly short prefix\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-v default-value (default: test)\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mHas a default value\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[m-x default-value2 (defaults: test2, test3)\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mHas also a default value\e[0m");

        $this->shouldWrite("\e[m\t\e[0m");
        $this->shouldWrite("\e[mno-prefix\e[0m");
        $this->shouldWrite("\e[m\t\t\e[0m");
        $this->shouldWrite("\e[mNot defined by a prefix\e[0m");
        $this->shouldHavePersisted(39);

        $this->cli->description('Test Description');
        $this->cli->arguments->add($this->getFullArguments());

        $command = 'test-script';
        $this->cli->usage([$command]);
    }

    /** @test */
    public function it_can_parse_arguments()
    {
        $this->cli->arguments->add([
            'only-short-prefix' => [
                'prefix' => 's',
            ],
            'only-long-prefix' => [
                'longPrefix' => 'long',
            ],
            'both-prefixes' => [
                'prefix'     => 'b',
                'longPrefix' => 'both',
            ],
            'both-equals' => [
                'longPrefix' => 'both-equals',
            ],
            'no-prefix' => [],
            'defined-only' => [
                'prefix'     => 'd',
                'longPrefix' => 'defined',
                'noValue'    => true,
            ],
        ]);

        $argv = [
            'test-script',
            '-s=baz',
            '-s',
            'foo',
            '--long',
            'bar',
            '-b=both',
            '-d',
            '--both-equals=both_equals',
            'no_prefix_value',
            '-unknown',
            'after_non_prefixed'
        ];

        $this->cli->arguments->parse($argv);
        $processed = $this->cli->arguments->toArray();

        $this->assertCount(6, $processed);
        $this->assertEquals('foo', $processed['only-short-prefix']);
        $this->assertEquals('bar', $processed['only-long-prefix']);
        $this->assertEquals('both', $processed['both-prefixes']);
        $this->assertEquals('both_equals', $processed['both-equals']);
        $this->assertEquals('no_prefix_value', $processed['no-prefix']);
        $this->assertTrue($processed['defined-only']);
        $this->assertEquals('foo', $this->cli->arguments->get('only-short-prefix'));
        $this->assertEquals(['baz', 'foo'], $this->cli->arguments->getArray('only-short-prefix'));
    }

    /** @test */
    public function it_will_get_a_default_value_for_a_long_prefix_with_no_value()
    {
        $this->cli->arguments->add([
            'only-long-prefix' => [
                'longPrefix' => 'long',
                'defaultValue' => 'HEY',
            ],
        ]);

        $argv = [
            'test-script',
            '--long',
        ];

        $this->cli->arguments->parse($argv);
        $processed = $this->cli->arguments->toArray();

        $this->assertEquals('HEY', $this->cli->arguments->get('only-long-prefix'));
    }

    /** @test */
    public function it_throws_an_exception_when_required_arguments_are_not_defined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The following arguments are required: [-r required-value] [-r1 required-value-1].");

        $this->cli->arguments->add([
            'required-value' => [
                'prefix'   => 'r',
                'required' => true,
            ],
            'required-value-1' => [
                'prefix'   => 'r1',
                'required' => true,
            ],
            'optional-value' => [
                'prefix' => 'o',
            ],
        ]);

        $argv = ['test-script', '-o', 'foo'];
        $this->cli->arguments->parse($argv);
    }

    /** @test */
    public function it_can_detect_when_arguments_are_defined()
    {
        $this->cli->arguments->add([
            'argument' => [
                'prefix' => 'a',
            ],
            'another-argument' => [
                'prefix' => 'b',
            ],
            'long-argument' => [
                'longPrefix' => 'c',
            ],
        ]);

        $argv = ['test-script', '-a', 'foo', '--c=bar'];

        $this->assertTrue($this->cli->arguments->defined('argument', $argv));
        $this->assertTrue($this->cli->arguments->defined('long-argument', $argv));
        $this->assertFalse($this->cli->arguments->defined('another-argument', $argv));
        $this->assertFalse($this->cli->arguments->defined('nonexistent', $argv));
    }

    /** @test */
    public function it_can_grab_the_trailing_arguments()
    {
        $this->cli->arguments->add([
            'argument' => [
                'prefix' => 'a',
            ],
            'another-argument' => [
                'prefix' => 'b',
            ],
            'long-argument' => [
                'longPrefix' => 'c',
            ],
        ]);

        $argv = ['test-script', '-a', 'foo', '--c=bar', '--', '-the', 'trailing', '--arguments=here'];

        $this->cli->arguments->parse($argv);

        $this->assertTrue($this->cli->arguments->defined('argument', $argv));
        $this->assertTrue($this->cli->arguments->defined('long-argument', $argv));
        $this->assertFalse($this->cli->arguments->defined('another-argument', $argv));
        $this->assertFalse($this->cli->arguments->defined('nonexistent', $argv));
        $this->assertSame($this->cli->arguments->trailing(), '-the trailing --arguments=here');
    }
}
