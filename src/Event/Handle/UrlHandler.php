<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 上午11:40
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

use FastD\Framework\Dispatcher\Dispatch;

/**
 * Framework route url generator.
 *
 * Class UrlHandler
 *
 * @package FastD\Framework\Dispatcher\Handle
 */
class UrlHandler extends Dispatch
{
    protected $router;

    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.url';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        list($name, $params, $format) = $parameters;

        $request = $this->getContainer()->singleton('kernel.request');

        $path = $this->getContainer()->singleton('kernel.routing')->generateUrl($name, $params, $format);

        return $request->getScheme() . '://' . $request->getHost() . str_replace('//', '/', $request->getBaseUrl() . $path);
    }
}