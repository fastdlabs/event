<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 上午12:07
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

use FastD\Framework\Dispatcher\Dispatch;
use FastD\Framework\Extensions\Preset;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Framework template generator.
 *
 * Class TplHandler
 *
 * @package FastD\Framework\Dispatcher\Handle
 */
class TplHandler extends Dispatch
{
    /**
     * @var \Twig_Environment
     */
    protected $tpl;

    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.tpl';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        if (null !== $this->tpl) {
            return $this->tpl;
        }

        $appKernel = $this->getContainer()->singleton('kernel');

        $extensions = [];
        $paths = [
            $appKernel->getRootPath() . '/../src'
        ];

        $appPath = $appKernel->getRootPath() . '/views';

        if (file_exists($appPath)) {
            $paths[] = $appPath;
        }

        $extensions['system'] = [new Preset()];

        $bundles = $appKernel->getBundles();
        foreach ($bundles as $bundle) {
            if (file_exists(($path = $bundle->getRootPath() . '/Resources/views'))) {
                $paths[] = $path;
            }

            $extensions[$bundle->getName()] = $bundle->registerExtensions();
        }

        $options = ['debug' => true];
        if (!($isDebug = $appKernel->isDebug())) {
            $options = [
                'cache' => $appKernel->getRootPath() . '/storage/templates',
                'debug' => $isDebug,
            ];
        }

        $this->tpl = new Twig_Environment(new Twig_Loader_Filesystem($paths), $options);

        $this->getContainer()->set('kernel.template', $this->tpl);

        foreach ($extensions as $extension) {
            foreach ($extension as $value) {
                $value->setContainer($this->getContainer());
                $this->tpl->addExtension($value);
            }
        }

        restore_error_handler();
        restore_error_handler();

        return $this->tpl;
    }
}