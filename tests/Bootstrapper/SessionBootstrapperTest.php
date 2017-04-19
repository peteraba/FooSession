<?php

namespace Foo\Session\Bootstrapper;

use Foo\Session\FlashService;
use Opulence\Ioc\Container;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;

class SessionBootstrapperTest extends \PHPUnit\Framework\TestCase
{
    /** @var SessionBootstrapper */
    protected $sut;

    public function setUp()
    {
        $this->sut = new SessionBootstrapper();
    }

    public function testRegisterBindings()
    {
        $containerMock = $this->getMockBuilder(Container::class)
            ->setMethods(['resolve', 'bindInstance'])
            ->getMock();

        $sessionStub = $this->getMockBuilder(Session::class)
            ->getMock();

        $containerMock
            ->expects($this->any())
            ->method('resolve')
            ->with(ISession::class)
            ->willReturn($sessionStub);

        $flashService = new FlashService($sessionStub);

        $containerMock
            ->expects($this->once())
            ->method('bindInstance')
            ->with(FlashService::class, $flashService);

        $this->sut->registerBindings($containerMock);
    }
}
