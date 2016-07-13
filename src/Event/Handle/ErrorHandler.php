<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/27
 * Time: 下午3:25
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

use FastD\Framework\Dispatcher\Dispatch;
use FastD\Debug\Debug;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class ErrorHandler
 *
 * @package FastD\Framework\Dispatcher\Handle
 */
class ErrorHandler extends Dispatch
{
    const LOG_ACCESS = 1;
    const LOG_ERROR = 2;

    const LOG_PATH = '/storage/logs';

    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.error';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        Debug::enable(false)->setLogger($this->getLogger(self::LOG_ERROR));
    }

    /**
     * @param $type
     * @return Logger
     */
    public function getLogger($type)
    {
        $log = $this->getContainer()->singleton('kernel')->getRootPath() . self::LOG_PATH . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR . 'error.log';

        $logger = new Logger('error');
        $stream = new StreamHandler($log);

        return $logger->pushHandler($stream);
    }
}