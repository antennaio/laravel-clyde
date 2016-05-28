Laravel Clyde
=============

_Image uploads and manipulation for Laravel, a wrapper around Glide_

You can use the package to upload your images to local filesystem or S3 and display them on your site
in various sizes. Thanks to [Glide](http://glide.thephpleague.com/) all manipulated images will be cached
and reserved on subsequent visits.

![Clyde The Glide](https://s3-eu-west-1.amazonaws.com/laravel-clyde/Clyde-Drexler.png "Clyde The Glide")

## Installation

Install through composer:

```
composer require antennaio/laravel-clyde
```

Add the service provider to config:

```
// config/app.php
'provider' => [
    ...
    Antennaio\Clyde\ClydeServiceProvider::class,
    ...
];
```

If you intend to use facades, install those as well:

```
// config/app.php
'aliases' => [
    ...
    'ClydeUpload' => Antennaio\Clyde\Facades\ClydeUpload::class,
    'ClydeImage' => Antennaio\Clyde\Facades\ClydeImage::class,
    ...
];
```

Publish configuration:

```
php artisan vendor:publish --provider="Antennaio\Clyde\ClydeServiceProvider"
```

## Uploads

You can use dependency injection or facades, it's up to you.

```php
use Antennaio\Clyde\ClydeUpload;

...

protected $uploads;

public function __construct(ClydeUpload $uploads)
{
    $this->uploads = $uploads;
}

public function upload(Request $request)
{
    if ($request->hasFile('image')) {
        $filename = $this->uploads->upload($request->file('image'));
    }
}
```

```php
use Antennaio\Clyde\Facades\ClydeUpload;

...

public function upload(Request $request)
{
    if ($request->hasFile('image')) {
        $filename = ClydeUpload::upload($request->file('image'));
    }
}
```

Each filename generated by Clyde is unique. Make sure to store the filename, so that you can display
the image at a later time.

You can control the location of where the uploaded file will be saved by passing an additional argument
to the `upload` method. Below is an example of how to save an image in a subdirectory and keep its original name:

```php
ClydeUpload::upload(
    $request->file('image'),
    'profile-images'.DIRECTORY_SEPARATOR.$request->file('image')->getClientOriginalName()
);
```

You can also check if an image already exists:

```php
// returns true or false
ClydeUpload::exists('image.jpg');
```

Or delete a previously uploaded image:

```php
ClydeUpload::delete('image.jpg');
```

## Displaying images

```php
<img src="{{ ClydeImage::url('56a1472beca5d.jpg') }}">
```

You can pass various image manipulations as the second parameter:

```php
<img src="{{ ClydeImage::url('56a1472beca5d.jpg', ['w' => 800, 'h' => 600, 'fit' => 'crop']) }}">
```

For the full list of available manipulations take a look at the Glide docs:

[http://glide.thephpleague.com/1.0/api/quick-reference/](http://glide.thephpleague.com/1.0/api/quick-reference/)

Additionally, you can setup presets and use them as a quicker way to apply manipulations to the images:

```
// config/clyde.php
'presets' => [
    [
        'thumbnail' => [
            'w' => 100,
            'h' => 100,
            'fit' => 'crop'
        ]
    ]
],
```

```
<img src="{{ ClydeImage::url('56a1472beca5d.jpg', 'thumbnail') }}">
```

## Watermarks

Watermarks are stored on the local filesystem by default. To use watermarks put the watermark files
in `storage/app/watermarks` directory. To adjust the location where watermark files are stored you can
edit `watermarks` and `watermarks_path_prefix` entries in the config.

```
ClydeImage::url('56a1472beca5d.jpg', [
    'mark' => 'watermark.png',
    'markpos' => 'top-right',
    'markw' => '50',
    'markh' => '50',
    'markpad' => '10'
]);
```

## Security

All URLs generated by Clyde are signed by default. This means that there is always a signature
appended to all URLs and verified when an image is displayed. To turn this feature off set the `secure_urls`
key to false in the config (not recommended).

## Clyde?

The package name is a tribute to Clyde "The Glide" Drexler.
