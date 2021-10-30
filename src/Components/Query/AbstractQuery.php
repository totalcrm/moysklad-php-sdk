<?php

namespace TotalCRM\MoySklad\Components\Query;

use Exception;
use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Components\Fields\MetaField;
use TotalCRM\MoySklad\Components\FilterQuery;
use TotalCRM\MoySklad\Components\Http\RequestConfig;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use TotalCRM\MoySklad\Traits\AccessesSkladInstance;

/**
 * Class AbstractQuery
 * @package TotalCRM\MoySklad\Components\Query
 */
abstract class AbstractQuery
{
    use AccessesSkladInstance;

    protected
        $entityClass,
        $entityName,
        $querySpecs,
        $requestOptions,
        $responseAttributes,
        $responseAttributesMapper;
    protected ?Expand $expand = null;
    private ?string $customQueryUrl = null;
    protected static string $entityListClass;

    /**
     * AbstractQuery constructor.
     * @param MoySklad $skladInstance
     * @param $entityClass
     * @param QuerySpecs|null $querySpecs
     * @throws Exception
     */
    public function __construct(MoySklad $skladInstance, $entityClass, ?QuerySpecs $querySpecs = null)
    {
        $this->skladHashCode = $skladInstance->hashCode();
        $this->entityClass = $entityClass;
        $this->entityName = $entityClass::$entityName;
        if (!$querySpecs) {
            /** @var QuerySpecs $querySpecs */
            $querySpecs = QuerySpecs::create([]);
        }
        $this->querySpecs = $querySpecs;
        $this->responseAttributes = ['meta' => null];
    }

    /**
     * Add expand to query
     * @param Expand $expand
     * @return $this
     */
    public function withExpand(Expand $expand): self
    {
        $this->expand = $expand;
        return $this;
    }

    /**
     * Url that will be used instead of default list url
     * @param $customQueryUrl
     * @return $this
     */
    public function setCustomQueryUrl($customQueryUrl): self
    {
        $this->customQueryUrl = $customQueryUrl;
        return $this;
    }

    /**
     * @param RequestConfig $options
     * @return $this
     */
    public function setRequestOptions(RequestConfig $options): self
    {
        $this->requestOptions = $options;
        return $this;
    }

    /**
     * @param string|callable $fnOrClass
     * @param $method
     * @return $this
     */
    public function setResponseAttributesMapper($fnOrClass, $method = null): self
    {
        if (is_string($fnOrClass)) {
            $fn = "$fnOrClass::$method";
        } else {
            $fn = $fnOrClass;
        }
        $this->responseAttributesMapper = $fn;
        return $this;
    }

    /**
     * Get list of entities
     * @return EntityList
     * @throws Exception
     */
    public function getList(): EntityList
    {
        return $this->filter(null);
    }

    /**
     * Search within list of entities
     * @param string $searchString
     * @return EntityList
     * @throws Exception
     */
    public function search($searchString = ''): EntityList
    {
        $this->attachExpand($this->querySpecs);
        $queryResult = $this->recursiveRequest(function (QuerySpecs $querySpecs, $searchString) {
            $query = array_merge($querySpecs->toArray(), [
                "search" => $searchString
            ]);
            return $this->getSkladInstance()->getClient()->get($this->getQueryUrl(), $query, $this->requestOptions);
        }, $this->querySpecs, [
            $searchString
        ]);
        $queryResult->replaceAttributes($this->mapResponseAttributes());
        return $queryResult;
    }

    /**
     * Filter within list of entities
     * @param FilterQuery|null $filterQuery
     * @return EntityList
     * @throws Exception
     */
    public function filter(FilterQuery $filterQuery = null): EntityList
    {
        $this->attachExpand($this->querySpecs);
        $queryResult = $this->recursiveRequest(function (QuerySpecs $querySpecs, FilterQuery $filterQuery = null) {
            if ($filterQuery) {
                $query = array_merge($querySpecs->toArray(), [
                    "filter" => $filterQuery->getRaw()
                ]);
            } else {
                $query = $querySpecs->toArray();
            }
            return $this->getSkladInstance()->getClient()->get($this->getQueryUrl(), $query, $this->requestOptions);
        }, $this->querySpecs, [
            $filterQuery
        ]);
        $queryResult->replaceAttributes($this->mapResponseAttributes());
        return $queryResult;
    }

    /**
     * Used for sending multiple list requests
     * @param callable $method
     * @param QuerySpecs $queryParams
     * @param array $methodArgs
     * @param int $requestCounter
     * @return EntityList
     * @throws Exception
     */
    protected function recursiveRequest(
        callable $method,
        QuerySpecs $queryParams,
        $methodArgs = [],
        $requestCounter = 1
    ): EntityList
    {
        $res = call_user_func_array($method, array_merge([$queryParams], $methodArgs));
        $resultingMeta = $this->mapIntermediateResponseAttributes($res);
        /**
         * @var EntityList $resultingObjects
         */
        $resultingObjects = (new static::$entityListClass($this->getSkladInstance(), $res->rows, $resultingMeta))
            ->map(function ($e) {
                return new $this->entityClass($this->getSkladInstance(), $e);
            });
        if ($resultingMeta->size > $queryParams->limit + $queryParams->offset) {
            $newQueryParams = $this->recreateQuerySpecs($queryParams);
            if ($queryParams->maxResults === 0 || $queryParams->maxResults > $requestCounter * $queryParams->limit) {
                $resultingObjects = $resultingObjects->merge(
                    static::recursiveRequest($method, $newQueryParams, $methodArgs, ++$requestCounter)
                );
            }
        }
        return $resultingObjects;
    }

    /**
     * Get previous QuerySpecs and increase offset
     * @param QuerySpecs $queryParams
     * @return QuerySpecs
     * @throws Exception
     */
    protected function recreateQuerySpecs(QuerySpecs $queryParams): QuerySpecs
    {
        return QuerySpecs::create([
            "offset" => $queryParams->offset + QuerySpecs::MAX_LIST_LIMIT,
            "limit" => $queryParams->limit,
            "maxResults" => $queryParams->maxResults,
            "expand" => $this->expand
        ]);
    }

    /**
     * Get default list query url, or use custom one
     * @return null|string
     */
    protected function getQueryUrl(): ?string
    {
        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();
        return (!empty($this->customQueryUrl) ?
            $this->customQueryUrl :
            $apiUrlRegistry->getListUrl($this->entityName));
    }

    /**
     * Attach added expand to specs
     * @param QuerySpecs $querySpecs
     * @return QuerySpecs
     */
    protected function attachExpand(QuerySpecs $querySpecs): QuerySpecs
    {
        if ($this->expand !== null) {
            $querySpecs->expand = $this->expand;
        }

        return $querySpecs;
    }

    /**
     * @param $response
     * @return MetaField
     */
    protected function mapIntermediateResponseAttributes(&$response): MetaField
    {
        foreach ($response as $key => $responseAttribute) {
            if ($key === 'meta') {
                $this->responseAttributes['meta'] = new MetaField($responseAttribute);
            } else if ($key !== 'rows') {
                $this->responseAttributes[$key] = $responseAttribute;
            }
        }
        return $this->responseAttributes['meta'];
    }

    public function mapResponseAttributes()
    {
        $result = (object)$this->responseAttributes;
        if ($this->responseAttributesMapper) {
            return call_user_func($this->responseAttributesMapper, $result, $this->getSkladInstance());
        }
        return $result;
    }
}
