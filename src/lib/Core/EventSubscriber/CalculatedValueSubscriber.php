<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\EventSubscriber;

use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\CalculatedValue\Type;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\CalculatedValue\Value;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Events\Content\BeforeCreateContentEvent;
use eZ\Publish\API\Repository\Events\Content\BeforeUpdateContentEvent;
use eZ\Publish\API\Repository\Values\Content\ContentStruct;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class CalculatedValueSubscriber implements EventSubscriberInterface
{
    /** @var \eZ\Publish\API\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \eZ\Publish\SPI\FieldType\FieldType */
    private $calculatedValueFieldType;

    /** @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage */
    private $expressionLanguage;

    public function __construct(
        ContentTypeService $contentTypeService,
        Type $calculatedValueFieldType
    ) {
        $this->contentTypeService = $contentTypeService;
        $this->calculatedValueFieldType = $calculatedValueFieldType;
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCreateContentEvent::class => 'onCreateContent',
            BeforeUpdateContentEvent::class => 'onUpdateContent',
        ];
    }

    public function onCreateContent(BeforeCreateContentEvent $event): void
    {
        $this->doUpdateFields(
            $event->getContentCreateStruct()->contentType,
            $event->getContentCreateStruct()
        );
    }

    public function onUpdateContent(BeforeUpdateContentEvent $event): void
    {
        $contentType = $this->contentTypeService->loadContentType(
            $event->getVersionInfo()->getContentInfo()->contentTypeId
        );

        $this->doUpdateFields(
            $contentType,
            $event->getContentUpdateStruct()
        );
    }

    private function doUpdateFields(ContentType $contentType, ContentStruct $contentStruct): void
    {
        $calculatedFields = $this->getCalculatedFields($contentType);
        if (empty($calculatedFields)) {
            return;
        }

        $evaluateContext = $this->createEvaluateContext($contentStruct);
        foreach ($calculatedFields as $identifier => $field) {
            $expression = (string)$field->fieldSettings['expression'];
            if ($expression === '') {
                continue;
            }

            $value = $this->expressionLanguage->evaluate($expression, $evaluateContext);

            $contentStruct->setField(
                $identifier,
                $evaluateContext[$identifier] = new Value($value)
            );
        }
    }

    private function createEvaluateContext(ContentStruct $contentStruct): array
    {
        $context = [];
        foreach ($contentStruct->fields as $field) {
            $context[$field->fieldDefIdentifier] = $field->value;
        }

        return $context;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\ContentType\ContentType $contentType
     *
     * @return \eZ\Publish\API\Repository\Values\ContentType\FieldDefinition[]
     */
    private function getCalculatedFields(ContentType $contentType): iterable
    {
        $fieldTypeIdentifier = $this->calculatedValueFieldType->getFieldTypeIdentifier();

        $calculatedFields = [];
        foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->fieldTypeIdentifier === $fieldTypeIdentifier) {
                $calculatedFields[$fieldDefinition->identifier] = $fieldDefinition;
            }
        }

        return $calculatedFields;
    }
}
