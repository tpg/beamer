# Cross-browser MP4 Video files for Laravel

[![Tests](https://github.com/tpg/beamer/actions/workflows/php.yml/badge.svg)](https://github.com/tpg/beamer/actions/workflows/php.yml)

Beamer is a simple solution to streaming MP4 videos to any browser. Even Safari.

### Installation

As usual, install Beamer using Composer:

```bash
composer require thepublicgood/beamer
```

You can publish the configuration file with:

```bash
php ./artisan vendor:publish --provider="TPG\Beamer\BeamerServiceProvider"
```

### Where to store videos

By default Beamer will source videos from a `videos` directory inside `storage/app`. You can configure this in the configuration file by changing the `disk` and `path` settings.

### Usage

Beamer is fairly simple. It only really does one thing. Once you have a video in the correct place, you’ll need to create a new route and a controller.

First the controller:

```php
namespace App\Http\Controllers;

use TPG\Beamer\Facades\Beamer;

class BeamerController extends Controller
{
    public function __invoke(string $filename)
    {
        return Beamer::make($filename)->start();
    }
}
```

Then the route:

```php
Route::get('/videos/{video}', BeamerController::class);
```

Open a browser and enter the URL for that route and include the filename of the video:

```
http://localhost:3000/videos/myvideo.mp4
```

Beamer doesn’t include the route or controller by default as you might want to customize this in your apps. For example, you might need to do some sort of authorization in the controller, or you might want to customize what the route looks like.
