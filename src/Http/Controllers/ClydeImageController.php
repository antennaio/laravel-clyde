<?php

namespace Antennaio\Clyde\Http\Controllers;

use Antennaio\Clyde\Facades\Server;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class ClydeImageController extends Controller
{
    /**
     * Display image.
     *
     * @param Request $request
     * @param string  $filename
     *
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

        return Server::outputImage($filename, $request->all());
    }
}
