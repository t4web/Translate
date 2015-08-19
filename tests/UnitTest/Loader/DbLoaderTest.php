<?php

namespace T4webTranslateTest\UnitTest\Loader;

use Translate\I18n\Translator\Loader\DbLoader;
use Zend\Feed\Reader\Collection;
use Zend\I18n\Translator\TextDomain;
use Translate\Words\Words;

class DbLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $wordsServiceFinderMock;
    /**
     * @var DbLoader
     */
    private $dbLoader;

    public function setUp()
    {

        $this->wordsServiceFinderMock = $this->getMockBuilder('T4webBase\Domain\Service\BaseFinder')
            ->disableOriginalConstructor()->getMock();

        $this->dbLoader = new DbLoader($this->wordsServiceFinderMock);
    }

    public function testLoad_EmptyWords_ReturnEmptyObjectTextDomain()
    {
        $locale = 'en_US';
        $textDomain = 'default';

        $messages[$textDomain][$locale] = [];

        $collectionMock = $this->getMockBuilder('T4webBase\Domain\Collection')->disableOriginalConstructor()->getMock();
        $this->wordsServiceFinderMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(['Translate' => ['Languages' => ['Locale' => $locale]]]))
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('count')
            ->willReturn(0);

        $result = $this->dbLoader->load($locale, $textDomain);

        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $result);

        $textDomainClass = new TextDomain();
        $this->assertEquals($textDomainClass, $result);
    }

    public function testLoad_NotEmptyWords_ReturnTextDomain()
    {
        return;
        $locale = 'en_US';
        $textDomain = 'default';

        $messages[$textDomain][$locale] = [];
        $data = [
            'id' => 1,
            'lang_id' => 1,
            'key' => 'word key',
            'translate' => 'word translate'
        ];

        $entity1 = new Words($data);
        $collection = new Collection();
        $collection->append($entity1);

        $collectionMock = $this->getMockBuilder('T4webBase\Domain\Collection')->disableOriginalConstructor()->getMock();
        $this->wordsServiceFinderMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(['Translate' => ['Languages' => ['Locale' => $locale]]]))
            ->willReturn($collection);

        $collectionMock->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $textDomainClass = new TextDomain();

        $result = $this->dbLoader->load($locale, $textDomain);

        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $result);
        $this->assertEquals($textDomainClass, $result);
    }
}