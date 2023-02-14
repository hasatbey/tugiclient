### Installation

Require this package with Composer
    
    "repositories": [
       {
         "url": "https://github.com/hasatbey/tugiclient",
          "type": "git"
        }
     ],
    "require": {
        "hasatbey/tugiclient": "master@dev"
    }
Add the ServiceProvider to the providers array in app/config/app.php

```php
Hasatbey\Tugiclient\TugiclientServiceProvider::class
```

You need to copy the assets to the public folder, using the following artisan command:

    php artisan vendor:publish --tag=tugiclient --ansi
	
Remember to publish the assets after each update (or add the command to your post-update-cmd in composer.json)

