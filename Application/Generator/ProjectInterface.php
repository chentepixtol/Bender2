<?php

namespace Application\Generator;

/**
 *
 * @author chente
 *
 */
interface ProjectInterface
{

    /**
     * @return ModuleCollection
     */
    public function getModules();

}