<?php

namespace BristolSU\ApiClientImplementation\Control\Clients;

use BristolSU\ApiClientImplementation\Control\Models\DataGroup;
use BristolSU\ApiClientImplementation\Control\Models\Group;
use BristolSU\ApiToolkit\Concerns\UsesPagination;
use BristolSU\ApiToolkit\Concerns\UsesHydration;
use BristolSU\ApiToolkit\Hydration\Hydrate;
use Psr\Http\Client\RequestExceptionInterface;

class GroupClient extends \BristolSU\ApiToolkit\Contracts\ClientResource
{

    /**
     * @var string
     */
    private $path;

    public function __construct(string $path = '/api/control')
    {
        $this->path = $path;
    }

    public function getById(int $id)
    {
        $this->hydrate(
          Hydrate::new()->model(Group::class)
            ->child(
              'data',
              Hydrate::new()->model(DataGroup::class)
            )
        );
        return $this->httpGet(sprintf('%s/group/%s', $this->path, $id));

    }

    public function getAll()
    {
        $this->usesPagination();
        $this->hydrate(
          Hydrate::new()->array(Group::class)
            ->child('data', Hydrate::new()->model(DataGroup::class))
        );

        return $this->httpGet(sprintf('%s/group/', $this->path));
    }

}
