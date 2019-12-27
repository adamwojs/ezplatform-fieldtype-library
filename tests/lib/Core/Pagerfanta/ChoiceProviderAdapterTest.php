<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Tests\Core\Pagerfanta;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Pagerfanta\ChoiceProviderAdapter;
use PHPUnit\Framework\TestCase;

final class ChoiceProviderAdapterTest extends TestCase
{
    private const EXAMPLE_OFFSET = 10;
    private const EXAMPLE_LENGTH = 100;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProvider|\PHPUnit\Framework\MockObject\MockObject */
    private $provider;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria */
    private $criteria;

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\Core\Pagerfanta\ChoiceProviderAdapter */
    private $adapter;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ChoiceProvider::class);
        $this->criteria = new ChoiceCriteria();
        $this->adapter = new ChoiceProviderAdapter($this->provider, $this->criteria);
    }

    public function testGetSlice(): void
    {
        $expectedResult = new ChoiceList(['a', 'b', 'c']);

        $this->provider
            ->method('getChoiceList')
            ->with($this->criteria, self::EXAMPLE_OFFSET, self::EXAMPLE_LENGTH)
            ->willReturn($expectedResult);

        $actualResult = $this->adapter->getSlice(self::EXAMPLE_OFFSET, self::EXAMPLE_LENGTH);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetNbResults(): void
    {
        $this->provider
            ->method('getChoiceList')
            ->with($this->criteria, 0, 0)
            ->willReturn(new ChoiceList(['a', 'b', 'c'], 3));

        $this->assertEquals(3, $this->adapter->getNbResults());
    }
}
