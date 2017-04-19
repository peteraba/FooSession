<?php

namespace Foo\Session;

use Opulence\Sessions\Session;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class FlashServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var FlashService */
    protected $sut;

    /** @var Session|MockObject */
    protected $sessionStub;

    /** @var array */
    protected $oldValues = [
        'foo' => [
            'bar' => 1,
            'baz' => 2,
        ],
        'bar' => [
            'bar' => 3,
            'baz' => 4,
        ],
    ];

    /** @var array */
    protected $newValues = [
        'foo' => [
            'bar' => 2,
            'baz' => 3,
        ],
        'baz' => [
            'bar' => 5,
            'baz' => 6,
        ],
    ];

    /** @var array */
    protected $mergedValues = [
        'foo' => [
            'bar' => 2,
            'baz' => 3,
        ],
        'bar' => [
            'bar' => 3,
            'baz' => 4,
        ],
        'baz' => [
            'bar' => 5,
            'baz' => 6,
        ],
    ];

    public function setUp()
    {
        $this->sessionStub = $this->getMockBuilder(Session::class)
            ->setMethods(['get', 'flash'])
            ->getMock();

        $this->sut = new FlashService($this->sessionStub);
    }

    public function testMergeSuccessMessages()
    {
        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $this->sessionStub
            ->expects($this->once())
            ->method('flash')
            ->with('success', $this->mergedValues);

        $this->sut->mergeSuccessMessages($this->newValues);
    }

    public function testMergeErrorMessages()
    {
        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $this->sessionStub
            ->expects($this->once())
            ->method('flash')
            ->with('error', $this->mergedValues);

        $this->sut->mergeErrorMessages($this->newValues);
    }

    public function testMergeFlashMessages()
    {
        $key = 'abc';

        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $this->sessionStub
            ->expects($this->once())
            ->method('flash')
            ->with($key, $this->mergedValues);

        $this->sut->mergeFlashMessages($key, $this->newValues);
    }

    public function testRetrieveSuccessMessages()
    {
        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $result = $this->sut->retrieveSuccessMessages();

        $this->assertEquals($this->oldValues, $result);
    }

    public function testRetrieveErrorMessages()
    {
        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $result = $this->sut->retrieveErrorMessages();

        $this->assertEquals($this->oldValues, $result);
    }

    public function testRetrieveFlashMessages()
    {
        $key = 'abc';

        $this->sessionStub
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->oldValues);

        $result = $this->sut->retrieveFlashMessages($key);

        $this->assertEquals($this->oldValues, $result);
    }
}
