<?php

namespace Tleckie\InjectorDetect;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Tleckie\InjectorDetect\Rules\DefaultRule;
use function base64_decode;
use function is_array;
use function parse_str;
use function urldecode;

/**
 * Class Detector
 *
 * @package Tleckie\InjectorDetect
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class Detector implements DetectorInterface
{
    /** @var RulesInterface[] */
    private array $rules = [];

    /**
     * Detector constructor.
     */
    public function __construct()
    {
        $this->addRule(new DefaultRule());
    }

    /**
     * @param RulesInterface $rule
     * @return DetectorInterface
     */
    public function addRule(RulesInterface $rule): DetectorInterface
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @throws InvalidArgumentException
     */
    public function check(ServerRequestInterface $request): void
    {
        $requestParams = $this->parseQueryString(urldecode($request->getUri()->getQuery()));

        $bodyParams = $request->getParsedBody() ?? [];

        foreach ($this->rules as $rule) {
            foreach ($requestParams as $item) {
                $this->arrayRecursive($rule, $item);
            }

            foreach ($bodyParams as $item) {
                $this->arrayRecursive($rule, $item);
            }
        }
    }

    /**
     * @param string $queryString
     * @return array
     */
    private function parseQueryString(string $queryString): array
    {
        $data = [];

        parse_str($queryString, $data);

        return $data;
    }

    /**
     * @param RulesInterface $rule
     * @param mixed          $valueToCheck
     * @return DetectorInterface
     */
    private function arrayRecursive(RulesInterface $rule, mixed $valueToCheck): DetectorInterface
    {
        if (is_array($valueToCheck)) {
            foreach ($valueToCheck as $value) {
                return $this->arrayRecursive($rule, $value);
            }
        }

        $rule->check(base64_decode($valueToCheck))->check($valueToCheck);

        return $this;
    }
}
