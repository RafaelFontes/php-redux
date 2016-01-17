<?php

namespace Rb\Rephlux\Middleware;

use Rb\Rephlux\StoreInterface;
use React\Promise\PromiseInterface;

/*
 * todo: Move to separate package
 */

class PromiseMiddleware implements MiddlewareInterface
{
    /**
     * @var StoreInterface
     */
    private $parent;

    /**
     * {@inheritdoc}
     */
    public function __construct(StoreInterface $store)
    {
        $this->parent = $store;
    }

    /**
     * {@inheritdoc}
     *
     * @param array|PromiseInterface $action
     */
    public function dispatch($action)
    {
        $wrapped  = $this->parent;
        $dispatch = [$wrapped, 'dispatch'];

        if ($action instanceof PromiseInterface) {
            return $action->then($dispatch);
        }

        return call_user_func($dispatch, $action);
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        $wrapped = $this->parent;

        return call_user_func([$wrapped, 'getState']);
    }
}