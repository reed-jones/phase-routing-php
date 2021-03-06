<?php

declare(strict_types=1);

namespace Phased\Routing\Factories;

use Illuminate\Http\JsonResponse;

class PhaseFactory
{
    /** @var array */
    protected $routes = [];

    /**
     * Adds the supplied route to the 'phase' section of the app.
     *
     * @param string $uri
     * @param array $action
     *
     * @return void
     */
    public function addRoute($uri, $action)
    {
        array_push($this->routes, [
            'uri' => $uri,
            'action' => $action,
        ]);
    }

    /**
     * Gets the currently registered routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Automatically switches between JSON api response &
     * Blade views for SPA's.
     *
     * @param string $blade (blade view)
     * @param array $jsonArgs (json data)
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function view(string $blade = null, ...$jsonArgs)
    {
        return request()->expectsJson()
            ? $this->api(...$jsonArgs)
            : view($blade ?? config('phase.entry'));
    }

    /**
     * Api only calls.
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function api(array $data = [], int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        $jsonResponse = config('phase.state') ? 'phase' : 'json';

        return response()->{$jsonResponse}($data, $status, $headers, $options);
    }
}
