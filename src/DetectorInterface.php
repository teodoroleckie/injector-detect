<?php

namespace Tleckie\InjectorDetect;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface DetectorInterface
 *
 * @package Tleckie\InjectorDetect
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
interface DetectorInterface
{
    /**
     * @param ServerRequestInterface $request
     * @throws InvalidArgumentException
     */
    public function check(ServerRequestInterface $request): void;

    /**
     * @param RulesInterface $rule
     * @return DetectorInterface
     */
    public function addRule(RulesInterface $rule): DetectorInterface;
}
