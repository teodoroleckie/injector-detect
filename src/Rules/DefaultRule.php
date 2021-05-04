<?php

namespace Tleckie\InjectorDetect\Rules;

use InvalidArgumentException;
use Tleckie\InjectorDetect\RulesInterface;

/**
 * Class DefaultRule
 *
 * @package Tleckie\InjectorDetect\Rules
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class DefaultRule implements RulesInterface
{
    /** @var array */
    private array $rules = [
        'phpinfo\(',
        'system\(',
        'exec\(',
        'shell_exec\(',
        'passthru\(',
        'proc_open\(',
        'proc_nice\(',
        'proc_terminate\(',
        'proc_get_status\(',
        'proc_close\(',
        'pfsockopen\(',
        'apache_child_terminate\(',
        'posix_kill\(',
        'popen\(',
        'curl_exec\(',
        'curl_multi_exec\(',
        'parse_ini_file\(',
        'show_source\(',
        'eval\(',
        'gzinflate\(',
        'str_rot13\(',
        'base64_decode\(',
        'str_replace\(',
        'error_reporting\(',
        'set_time_limit\(',
        'gzuncompress\(',
    ];

    /**
     * @inheritdoc
     */
    public function check(mixed $valueToCheck): RulesInterface
    {
        $rules = implode('|', $this->rules);

        if (preg_match("#$rules#i", $valueToCheck, $matches)) {
            throw new InvalidArgumentException(
                sprintf('Insecure parameter detected with value [%s]', $valueToCheck)
            );
        }

        return $this;
    }
}
