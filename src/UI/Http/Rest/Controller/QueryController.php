<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller;


use App\Shared\Application\Query\Collection;
use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UI\Http\Rest\Response\OpenApi;

abstract class QueryController
{
    const CACHE_MAX_AGE = 86400; // One day

    private QueryBusInterface $queryBus;

    private UrlGeneratorInterface $router;

    /**
     * @param QueryBusInterface $queryBus
     * @param UrlGeneratorInterface $router
     */
    public function __construct(QueryBusInterface $queryBus, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->router = $router;
    }

    /**
     * @param QueryInterface $query
     * @return Item|Collection|string|null
     */
    protected function ask(QueryInterface $query): Item|Collection|string|null
    {
        return $this->queryBus->ask($query);
    }

    protected function jsonCollection(Collection $collection, int $status = OpenApi::HTTP_OK, bool $isImmutable = false): OpenApi
    {
        $response = OpenApi::collection($collection, $status);

        $this->decorateWithCache($response, $collection, $isImmutable);

        return $response;
    }

    protected function json(Item $resource, int $status = OpenApi::HTTP_OK): OpenApi
    {
        return OpenApi::one($resource, $status);
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    private function decorateWithCache(OpenApi $response, Collection $collection, bool $isImmutable): void
    {
        if ($isImmutable && $collection->limit === \count($collection->data)) {
            $response
                ->setMaxAge(self::CACHE_MAX_AGE)
                ->setSharedMaxAge(self::CACHE_MAX_AGE);
        }
    }
}