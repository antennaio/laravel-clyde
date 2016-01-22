<?php

namespace Antennaio\Clyde\Test;

use Antennaio\Clyde\ClydeImage;
use Illuminate\Config\Repository as Config;
use Mockery;

class ClydeImageTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock(Config::class);

        $this->images = new ClydeImage($this->config);
    }

    public function testBasicUrl()
    {
        $this->config->shouldReceive('get')->with('clyde.secure_urls')->andReturn(false);
        $this->config->shouldReceive('get')->with('clyde.route_name')->andReturn('laravel-clyde');

        $url = $this->images->url('test1.jpg');

        $this->assertEquals('/imgcache/test1.jpg', $this->extractPath($url));
    }
   
    public function testSecureUrl()
    {
        $this->config->shouldReceive('get')->with('clyde.secure_urls')->andReturn(true);
        $this->config->shouldReceive('get')->with('clyde.route_name')->andReturn('laravel-clyde');
        $this->config->shouldReceive('get')->with('clyde.sign_key')->andReturn('dN39Dms0-paB1~102n');

        $url = $this->images->url('test2.jpg', ['w' => 100]);

        $queryParams = $this->extractQueryParams($url);

        $this->assertEquals('/imgcache/test2.jpg', $this->extractPath($url));
        $this->assertTrue(array_key_exists('s', $queryParams));
        $this->assertEquals($queryParams['w'], 100);
    }

    public function testUrlWithPreset()
    {
        $presets = ['xl' => ['w' => 1200, 'h' => 800]];

        $this->config->shouldReceive('get')->with('clyde.secure_urls')->andReturn(false);
        $this->config->shouldReceive('get')->with('clyde.route_name')->andReturn('laravel-clyde');
        $this->config->shouldReceive('get')->with('clyde.presets')->andReturn($presets);

        $url = $this->images->url('image.jpg', 'xl');

        $queryParams = $this->extractQueryParams($url);

        $this->assertEquals($queryParams['p'], 'xl');
    }

    private function extractPath($url)
    {
        return parse_url($url, PHP_URL_PATH);
    }

    private function extractQueryParams($url)
    {
        $queryString = parse_url($url, PHP_URL_QUERY);

        parse_str($queryString, $queryParams);

        return $queryParams;
    }
}
