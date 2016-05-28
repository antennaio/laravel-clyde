<?php

namespace Antennaio\Clyde;

use Illuminate\Config\Repository as Config;
use League\Glide\Urls\UrlBuilderFactory;

class ClydeImage
{
    /**
     * Create a new ClydeImage instance.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Return signed url or a basic url depending on settings.
     *
     * @param string       $filePath
     * @param string|array $manipulations
     *
     * @return string
     */
    public function url($filePath, $manipulations = [])
    {
        $manipulations = (is_string($manipulations)) ?
            $this->handlePresets($manipulations) :
            $manipulations;

        if ($this->config->get('clyde.secure_urls')) {
            return $this->secureUrl($filePath, $manipulations);
        }

        return $this->basicUrl($filePath, $manipulations);
    }

    /**
     * Check if string is matching a preset.
     *
     * @param string $preset
     *
     * @return array
     */
    protected function handlePresets($preset)
    {
        if (array_key_exists($preset, $this->config->get('clyde.presets'))) {
            return ['p' => $preset];
        }

        return [];
    }

    /**
     * Return signed url.
     *
     * @param string $filePath
     * @param array  $manipulations
     *
     * @return string
     */
    protected function secureUrl($filePath, $manipulations)
    {
        $url = route($this->config->get('clyde.route_name'), ['filename' => $filePath], false);

        $urlBuilder = UrlBuilderFactory::create('/', $this->config->get('clyde.sign_key'));

        return $urlBuilder->getUrl($url, $manipulations);
    }

    /**
     * Return basic url.
     *
     * @param string $filePath
     * @param array  $manipulations
     *
     * @return string
     */
    protected function basicUrl($filePath, $manipulations)
    {
        $params = array_merge(['filename' => $filePath], $manipulations);

        return route($this->config->get('clyde.route_name'), $params, false);
    }
}
