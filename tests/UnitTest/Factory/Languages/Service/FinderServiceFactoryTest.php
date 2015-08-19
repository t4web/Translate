<?php

namespace T4webTranslateTest\UnitTest\Factory\Languages\Service;

use T4webBaseTest\Factory\AbstractServiceFactoryTest;
use Translate\Factory\Languages\Service\FinderServiceFactory;

class FinderServiceFactoryTest extends AbstractServiceFactoryTest
{

    public function testFactory()
    {
        $factory = new FinderServiceFactory();

        $this->serviceManager->setService(
            'T4webTranslate\Languages\Repository\DbRepository',
            $this->getMockBuilder('T4webBase\Domain\Repository\DbRepository')->disableOriginalConstructor()->getMock()
        );
        $this->serviceManager->setService(
            'T4webTranslate\Languages\Criteria\CriteriaFactory',
            $this->getMockBuilder('T4webBase\Domain\Criteria\Factory')->disableOriginalConstructor()->getMock()
        );

        $this->assertInstanceOf('T4webBase\Domain\Service\BaseFinder', $factory->createService($this->serviceManager));
    }
}