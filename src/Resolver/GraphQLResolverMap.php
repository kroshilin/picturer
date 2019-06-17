<?php

namespace App\Resolver;

use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Overblog\GraphQLBundle\Upload\Type\GraphQLUploadType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GraphQLResolverMap extends ResolverMap
{
    /** @var array[] */
    private $loadedMap;

    /** @var bool */
    private $isMapLoaded = false;

    /** @var bool[] */
    private $memorized = [];

    protected $container;
    protected $mutationControllerFactory;
    protected $queryControllerFactory;

    public function __construct(ContainerInterface $container, QueryControllerFactory $queryControllerFactory, MutationControllerFactory $mutationControllerFactory)
    {
        $this->container = $container;
        $this->queryControllerFactory = $queryControllerFactory;
        $this->mutationControllerFactory = $mutationControllerFactory;
    }

    private function getLoadedMap()
    {
        if (!$this->isMapLoaded) {
            $this->checkMap($map = $this->map());
            $this->loadedMap = $map;
            $this->isMapLoaded = true;
        }

        return $this->loadedMap;
    }

    private function checkMap($map)
    {
        if (!\is_array($map) && !($map instanceof \ArrayAccess && $map instanceof \Traversable)) {
            throw new \RuntimeException(\sprintf(
                '%s::map() should return an array or an instance of \ArrayAccess and \Traversable but got "%s".',
                \get_class($this),
                \is_object($map) ? \get_class($map) : \gettype($map)
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isResolvable($typeName, $fieldName)
    {
        $key = $typeName.'.'.$fieldName;
        if (!isset($this->memorized[$key])) {
            $loadedMap = $this->getLoadedMap();
            $this->memorized[$key] = isset($loadedMap[$typeName]) && isset($loadedMap[$typeName][$fieldName]);
        }

        return $this->memorized[$key];
    }


    protected function map()
    {
        return [
            'Upload' => [
                self::SCALAR_TYPE => function () {
                    return new GraphQLUploadType();
                },
            ],
            'Query' => $this->queryControllerFactory,
            'Mutation' => $this->mutationControllerFactory,
        ];
    }
}

