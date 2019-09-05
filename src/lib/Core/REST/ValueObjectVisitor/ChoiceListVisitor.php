<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\REST\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;

final class ChoiceListVisitor extends ValueObjectVisitor
{
    /**
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \AdamWojs\EzPlatformFieldTypeLibrary\Core\REST\Value\ChoiceList $choiceList
     */
    public function visit(Visitor $visitor, Generator $generator, $choiceList): void
    {
        $generator->startObjectElement('ChoiceList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ChoiceList'));

        $generator->valueElement('count', $choiceList->getCount());

        $generator->startList('items');
        foreach ($choiceList as $item) {
            $generator->startObjectElement('ChoiceListItem');
            $generator->valueElement('label', $item->getLabel());
            $generator->valueElement('value', $item->getValue());
            $generator->endObjectElement('ChoiceListItem');
        }
        $generator->endList('items');

        $generator->endObjectElement('ChoiceList');
    }
}
