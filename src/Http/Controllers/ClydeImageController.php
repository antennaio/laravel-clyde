<?php

namespace Antennaio\Clyde\Http\Controllers;

use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class ClydeImageController extends Controller
{
    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Display image.
     *
     * @param Request $request
     * @param string $filename
     * @return mixed
     */
    public function show(Request $request, $filename)
    {
        if (config('clyde.secure_urls')) {
            try {
                SignatureFactory::create(config('clyde.sign_key'))
                    ->validateRequest($request->path(), $request->all());

            } catch (SignatureException $e) {
                return response()->json('Sorry, URL signature is invalid.');
            }
        }

        $server = ServerFactory::create([
            'source' => $this->files->disk(config('clyde.source'))->getDriver(),
            'source_path_prefix' => config('clyde.source_path_prefix'),
            'cache' => $this->files->disk(config('clyde.cache'))->getDriver(),
            'cache_path_prefix' => config('clyde.cache_path_prefix'),
            'watermarks' => $this->files->disk(config('clyde.watermarks'))->getDriver(),
            'watermarks_path_prefix' => config('clyde.watermarks_path_prefix'),
            'max_image_size' => config('clyde.max_image_size'),
            'presets' => config('clyde.presets'),
            'response' => new SymfonyResponseFactory()
        ]);

        return $server->outputImage($filename, $request->all());
    }
}
