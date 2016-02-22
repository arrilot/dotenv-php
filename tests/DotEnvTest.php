<?php

namespace Arrilot\Tests\DotEnv;

use Arrilot\DotEnv\DotEnv;
use PHPUnit_Framework_TestCase;

class DotEnvTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        DotEnv::flush();
    }

    public function test_it_can_load_env_file_and_gives_access_to_vars_using_all()
    {
        DotEnv::load(__DIR__.'/fixtures/.env.php');

        $expected = [
            'DB_USER' => 'root',
            'DB_PASSWORD' => 'secret',
        ];

        $this->assertSame($expected, DotEnv::all());
    }

    public function test_it_can_load_array()
    {
        $array = ['DB_USER' => 'root'];
        DotEnv::load($array);

        $this->assertSame($array, DotEnv::all());
    }

    public function test_flush_method()
    {
        DotEnv::load(['DB_USER' => 'root']);
        DotEnv::flush();

        $this->assertSame([], DotEnv::all());
    }

    public function test_get_method()
    {
        DotEnv::load(['DB_USER' => 'root']);

        $this->assertSame('root', DotEnv::get('DB_USER'));
        $this->assertSame('root', DotEnv::get('DB_USER', 'foo'));
        $this->assertSame(null, DotEnv::get('DB_PASSWORD'));
        $this->assertSame('foo', DotEnv::get('DB_PASSWORD', 'foo'));
    }

    public function test_env_helper()
    {
        DotEnv::load(['DB_USER' => 'root']);

        $this->assertSame('root', env('DB_USER'));
        $this->assertSame('root', env('DB_USER', 'foo'));
        $this->assertSame(null, env('DB_PASSWORD'));
        $this->assertSame('foo', env('DB_PASSWORD', 'foo'));
    }

    public function test_set_method_with_two_args()
    {
        DotEnv::load(['DB_USER' => 'root']);

        DotEnv::set('DB_PASSWORD', 'secret');
        $this->assertSame('root', DotEnv::get('DB_USER'));
        $this->assertSame('secret', DotEnv::get('DB_PASSWORD'));
    }

    public function test_set_method_with_array()
    {
        DotEnv::load(['DB_USER' => 'root']);

        DotEnv::set([
            'DB_PASSWORD' => 'secret',
            'DB_NAME' => 'test',
        ]);

        $this->assertSame('root', DotEnv::get('DB_USER'));
        $this->assertSame('secret', DotEnv::get('DB_PASSWORD'));
        $this->assertSame('test', DotEnv::get('DB_NAME'));
    }

    public function test_it_throws_missing_var_exception()
    {
        $this->setExpectedException('Arrilot\DotEnv\Exceptions\MissingVariableException');

        DotEnv::setRequired(['DB_USER', 'DB_PASSWORD']);
        DotEnv::load(['DB_USER' => 'root']);

    }

    public function test_it_does_not_throw_missing_var_exception_if_all_required_vars_are_set()
    {
        DotEnv::setRequired(['DB_USER', 'DB_PASSWORD']);
        DotEnv::load(['DB_USER' => 'root', 'DB_PASSWORD' => 'secret']);
    }
}
