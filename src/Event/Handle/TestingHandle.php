<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 下午3:54
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

use FastD\Framework\Dispatcher\Dispatch;
use FastD\Framework\Tests\TestClient;

/**
 * Class TestingHandle
 *
 * @package FastD\Framework\Dispatcher\Handle
 */
class TestingHandle extends Dispatch
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.testing';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        $request = $this->getContainer()->singleton('kernel')->createHttpRequestClient();

        return new TestClient($this->getContainer(), $request);
    }
}