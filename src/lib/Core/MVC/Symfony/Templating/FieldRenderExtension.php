<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\MVC\Symfony\Templating;

use eZ\Publish\API\Repository\FieldTypeService;
use eZ\Publish\API\Repository\Values\Content\Field;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

final class FieldRenderExtension extends AbstractExtension
{
    /** @var \eZ\Publish\API\Repository\FieldTypeService */
    private $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    public function getTests(): array
    {
        return [
            /**
             * {% if field is empty %}
             */
            new TwigTest('empty', function ($value) {
                if ($value instanceof Field) {
                    return $this->isFieldEmpty($value);
                }

                return twig_test_empty($value);
            }),
        ];
    }

    private function isFieldEmpty(Field $field): bool
    {
        return $this->fieldTypeService->getFieldType(
            $field->fieldTypeIdentifier
        )->isEmptyValue($field->value);
    }
}
