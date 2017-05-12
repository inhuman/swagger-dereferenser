<?php


use Orbitum\SwaggerDereferenser\Dereferenser;

class DereferenserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testDereferenser()
    {
        $dereferenser = new Dereferenser('tests/_data/withIncludes/index.yml');
    }
}