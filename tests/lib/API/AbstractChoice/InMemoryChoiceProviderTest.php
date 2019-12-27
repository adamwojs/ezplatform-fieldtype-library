<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Tests\API\AbstractChoice;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceList;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\InMemoryChoiceProvider\Choice;
use PHPUnit\Framework\TestCase;

final class InMemoryChoiceProviderTest extends TestCase
{
    private const EXAMPLE_LABEL = 'label';
    private const EXAMPLE_VALUE = 'value';

    /**
     * @dataProvider dataProviderForGetChoiceList
     */
    public function testGetChoiceList(array $availableChoices, array $args, ChoiceList $expectedChoices): void
    {
        $provider = new InMemoryChoiceProvider($availableChoices);

        $this->assertEquals($expectedChoices, $provider->getChoiceList(...$args));
    }

    public function testGetValueForChoice(): void
    {
        $provider = new InMemoryChoiceProvider([]);

        $actualValue = $provider->getValueForChoice(
            new Choice(self::EXAMPLE_VALUE, self::EXAMPLE_LABEL)
        );

        $this->assertEquals(self::EXAMPLE_VALUE, $actualValue);
    }

    public function testGetLabelForChoice(): void
    {
        $provider = new InMemoryChoiceProvider([]);

        $actualLabel = $provider->getLabelForChoice(
            new Choice(self::EXAMPLE_VALUE, self::EXAMPLE_LABEL)
        );

        $this->assertEquals(self::EXAMPLE_LABEL, $actualLabel);
    }

    public function dataProviderForGetChoiceList(): array
    {
        $availableChoices = [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
        ];

        return [
            'all' => [
                $availableChoices,
                [
                    new ChoiceCriteria(),
                    null,
                    null,
                ],
                new ChoiceList([
                    new Choice('AL', 'Alabama'),
                    new Choice('AK', 'Alaska'),
                    new Choice('AZ', 'Arizona'),
                    new Choice('AR', 'Arkansas'),
                    new Choice('CA', 'California'),
                    new Choice('CO', 'Colorado'),
                    new Choice('CT', 'Connecticut'),
                    new Choice('DE', 'Delaware'),
                    new Choice('FL', 'Florida'),
                    new Choice('GA', 'Georgia'),
                    new Choice('HI', 'Hawaii'),
                    new Choice('ID', 'Idaho'),
                ], 12),
            ],
            'pagination' => [
                $availableChoices,
                [
                    new ChoiceCriteria(),
                    3,
                    3,
                ],
                new ChoiceList([
                    new Choice('AR', 'Arkansas'),
                    new Choice('CA', 'California'),
                    new Choice('CO', 'Colorado'),
                ], 12),
            ],
            'filter_value' => [
                $availableChoices,
                [
                    ChoiceCriteria::withValues(['AL', 'GA', 'ID']),
                    null,
                    null,
                ],
                new ChoiceList([
                    new Choice('AL', 'Alabama'),
                    new Choice('GA', 'Georgia'),
                    new Choice('ID', 'Idaho'),
                ], 3),
            ],
            'filter_search_term' => [
                $availableChoices,
                [
                    ChoiceCriteria::withSearchTerm('ala'),
                    null,
                    null,
                ],
                new ChoiceList([
                    new Choice('AL', 'Alabama'),
                    new Choice('AK', 'Alaska'),
                ], 2),
            ],
            'filter_search_term_and_pagination' => [
                $availableChoices,
                [
                    ChoiceCriteria::withSearchTerm('l'),
                    3,
                    3,
                ],
                new ChoiceList([
                    new Choice('CO', 'Colorado'),
                    new Choice('DE', 'Delaware'),
                    new Choice('FL', 'Florida'),
                ], 6),
            ],
        ];
    }
}
