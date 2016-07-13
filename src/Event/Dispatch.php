<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 上午12:11
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher;

use FastD\Container\Aware;
use FastD\Container\Container;

/**
 * Class Dispatch
 *
 * @package FastD\Framework\Dispatcher
 */
abstract class Dispatch implements DispatchInterface
{
    use Aware;
    
    /**
     * Dispatch constructor.
     *
     * @param Container|null $container
     */
    public function __construct(Container $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param array|null $parameters
     * @return mixed
     */
    abstract public function dispatch(array $parameters = null);
}