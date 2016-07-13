<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 上午11:58
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

/**
 * Class ForwardHandler
 *
 * @package FastD\Framework\Dispatcher\Handle
 */
class ForwardHandler extends RequestHandler
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.forward';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        list($name, $params) = $parameters;

        $route = $this->getContainer()->singleton('kernel.routing')->getRoute($name);

        $route->mergeParameters($params);

        return $this->handleRoute($route);
    }
}