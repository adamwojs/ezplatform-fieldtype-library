<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\Controller;

use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceCriteria;
use AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProviderRegistry;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\Pagerfanta\ChoiceProviderAdapter;
use AdamWojs\EzPlatformFieldTypeLibrary\Core\REST\Value\ChoiceList;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformRest\Server\Controller as BaseController;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ChoiceAutoCompleteController extends BaseController
{
    private const TERM_QUERY_PARAM = 'term';
    private const PAGE_QUERY_PARAM = 'page';

    /** @var \AdamWojs\EzPlatformFieldTypeLibrary\API\FieldType\AbstractChoice\ChoiceProviderRegistry */
    private $choiceProviderRegistry;

    /** @var int */
    private $maxPerPage;

    public function __construct(ChoiceProviderRegistry $choiceProviderRegistry, int $maxPerPage = 25)
    {
        $this->choiceProviderRegistry = $choiceProviderRegistry;
        $this->maxPerPage = $maxPerPage;
    }

    public function getChoicesAction(Request $request, string $identifier): ChoiceList
    {
        try {
            $provider = $this->choiceProviderRegistry->getChoiceProvider($identifier);
            $criteria = ChoiceCriteria::withSearchTerm($request->query->get(self::TERM_QUERY_PARAM));

            $results = new Pagerfanta(new ChoiceProviderAdapter($provider, $criteria));
            $results->setCurrentPage($request->query->getInt(self::PAGE_QUERY_PARAM, 1));
            $results->setMaxPerPage($this->maxPerPage);

            return ChoiceList::createFromAPI($provider, $results->getCurrentPageResults());
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
