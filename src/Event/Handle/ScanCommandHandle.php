<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/12/11
 * Time: 下午12:38
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Dispatcher\Handle;

use FastD\Console\Command\Command;
use FastD\Framework\Bundle\Commands\CommandAware;
use FastD\Framework\Dispatcher\Dispatch;
use Symfony\Component\Finder\Finder;
use FastD\Framework\Bundle\Bundle;

class ScanCommandHandle extends Dispatch
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'handle.scan.commands';
    }

    /**
     * @param array|null $parameters
     * @return mixed
     */
    public function dispatch(array $parameters = null)
    {
        list($application) = $parameters;

        $bundles = array_merge($application->getKernel()->getBundles(), [new Bundle()]);

        foreach ($bundles as $bundle) {
            $dir = $bundle->getRootPath() . '/Commands';
            if (!is_dir($dir)) {
                continue;
            }

            $finder = new Finder();

            foreach ($finder->in($dir)->name('*Command.php')->files() as $file) {
                $class = $bundle->getNamespace() . '\\Commands\\' . pathinfo($file, PATHINFO_FILENAME);
                $command = new $class();
                if ($command instanceof CommandAware) {
                    $command->setContainer($this->getContainer());
                    $application->addCommand($command);
                }
            }
        }

        return ;
    }
}