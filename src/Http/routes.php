<?php

Route::get('/'.config('clyde.url_prefix').'/{path}', [
    'as' => config('clyde.route_name'),
    'uses' => 'Antennaio\Clyde\Http\Controllers\ClydeImageController@show',
])->where('path', '(.*)');
