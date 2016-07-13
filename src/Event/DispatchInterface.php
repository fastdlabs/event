<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/19
 * Time: 下午11:41
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher;

/**
 * Interface DispatchInterface
 *
 * @package FastD\Framework\Dispatcher
 */
interface DispatchInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null);
}