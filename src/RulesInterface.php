<?php

namespace Tleckie\InjectorDetect;

use InvalidArgumentException;

/**
 * Interface RulesInterface
 *
 * @package Tleckie\InjectorDetect
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
interface RulesInterface
{
    /**
     * @param mixed $valueToCheck
     * @return RulesInterface
     * @throws InvalidArgumentException
     */
    public function check(mixed $valueToCheck): RulesInterface;
}
