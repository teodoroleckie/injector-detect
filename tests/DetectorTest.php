<?php

namespace Tleckie\InjectorDetect\Tests;

use HttpSoft\Message\ServerRequestFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tleckie\InjectorDetect\Detector;
use Tleckie\InjectorDetect\DetectorInterface;
use Tleckie\InjectorDetect\Rules\DefaultRule;

/**
 * Class DetectorTest
 *
 * @package Tleckie\InjectorDetect\Tests
 * @author  Teodoro Leckie Westberg <teodoroleckie@gmail.com>
 */
class DetectorTest extends TestCase
{
    /** @var DetectorInterface */
    private DetectorInterface $detector;

    public function setUp(): void
    {
        $this->detector = new Detector();
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function checkParam(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = "https://www.site.com/?query=string&param=$input" . "otherText";

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        $this->detector->check($request);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function checkArrayParam(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = "https://www.site.com/?query=string&param[]=$input" . "otherText";

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        $this->detector->check($request);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function checkEncodedQueryString(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = sprintf("https://www.site.com/?%s", urlencode("query=string&param[]=$input" . 'otherText'));

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        $this->detector->check($request);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function checkEncodedParam(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);

        $uri = sprintf("https://www.site.com/?query=string&param[0][0]=%s" . "otherText", base64_encode($input));

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        $this->detector->check($request);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function rules(string $input): void
    {
        $this->detector = new Detector([new DefaultRule()]);

        $this->expectException(InvalidArgumentException::class);

        $uri = sprintf("https://www.site.com/?query=string&param[0][0][0][0][0]=%s" . "otherText", base64_encode($input));

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        $this->detector->check($request);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider checkDataProvider
     */
    public function bodyParam(string $input): void
    {
        $this->detector = new Detector();

        $this->expectException(InvalidArgumentException::class);

        $uri = sprintf("https://www.site.com/");

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri)
            ->withParsedBody([[['name' => base64_encode($input)]]]);

        $this->detector->check($request);
    }

    /**
     * @test
     */
    public function checkOk(): void
    {
        $this->detector = new Detector();

        $uri = sprintf("https://www.site.com/?param[0][0][0]=test&param1=true");

        $request = (new ServerRequestFactory())->createServerRequest('get', $uri);

        static::assertEmpty($this->detector->check($request));
    }

    /**
     * @return string[][]
     */
    public function checkDataProvider(): array
    {
        return [
            ['phpinfo('],
            ['system('],
            ['exec('],
            ['shell_exec('],
            ['passthru('],
            ['proc_open('],
            ['proc_nice('],
            ['proc_terminate('],
            ['proc_get_status('],
            ['proc_close('],
            ['pfsockopen('],
            ['apache_child_terminate('],
            ['posix_kill('],
            ['popen('],
            ['curl_exec('],
            ['curl_multi_exec('],
            ['parse_ini_file('],
            ['show_source('],
            ['eval('],
            ['gzinflate('],
            ['str_rot13('],
            ['base64_decode('],
            ['str_replace('],
            ['error_reporting('],
            ['set_time_limit('],
            ['gzuncompress('],
        ];
    }
}
