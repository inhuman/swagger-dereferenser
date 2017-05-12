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
        $swaggerSpec = Dereferenser::dereferense('tests/_data/withIncludes/index.yml');

        $this->assertEquals([
            'swagger' => '2.0',
            'info' => [
                'version' => '0.0.0',
                'title' => 'Simple API'
            ],
            'paths' => [
                '/foo' => [
                    'get' => [
                        'responses' =>[
                            200 => [
                                'description' => 'OK'
                            ]
                        ]
                    ]
                ],
                '/bar' => [
                    'get' => [
                        'responses' =>[
                            200 => [
                                'description' => 'OK'
                            ]
                        ]
                    ]
                ],
            ],
            'definitions' => [
                'User' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ]
        ], $swaggerSpec);

    }
}

