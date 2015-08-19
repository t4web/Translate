<?php

namespace T4webTranslateTest\UnitTest\Factory\Loader;

use T4webBaseTest\Factory\AbstractServiceFactoryTest;
use Translate\Factory\Loader\DbLoaderFactory;

class DbLoaderFactoryTest extends AbstractServiceFactoryTest
{

    public function testFactory()
    {
        return;
        $factory = new DbLoaderFactory();

        $this->serviceManager->setService(
            'T4webTranslate\Words\Service\Finder',
            $this->getMockBuilder('T4webBase\Domain\Service\BaseFinder')->disableOriginalConstructor()->getMock()
        );
        $dbLoaderService = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('T4webTranslate\I18n\Translator\Loader\DbLoader', $dbLoaderService);
    }
}