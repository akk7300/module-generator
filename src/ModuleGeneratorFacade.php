<?php

namespace Akk7300\ModuleGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Akk7300\ModuleGenerator\Skeleton\SkeletonClass
 */
class ModuleGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'module-generator';
    }
}
