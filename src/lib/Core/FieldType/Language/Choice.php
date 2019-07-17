<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibrary\Core\FieldType\Language;

final class Choice
{
    /** @var string */
    private $code;

    /** @var string */
    private $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Returns lowercase ISO 639-1 two-letter language code if possible, otherwise ISO 639-2 three-letter code
     * is returned.
     *
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->code;
    }
}
