# API Info

## Generating OpenAPI Docs

If you want to be able to modify the code and generate your own OpenAPI document you will need to set a few things up.

First, make sure you have Composer installed. There seem to be backward compatibility issues so I always get the latest version from here:

<https://getcomposer.org/doc/00-intro.md>

Follow the instructions the site gives to get it installed.

Now go into `/vulnerabilities/api` directory and run:

```
composer.phar install
```

If you did not install Composer to the system path, make sure you reference its full location.

With this installed, you should now be able to browse to `/vulnerabilities/api/gen_openapi.php` and download a dynamically generated OpenAPI file

## Mark Up

The OpenAPI document is generated using [swagger-php](https://github.com/zircote/swagger-php).

The file is marked up using the newer PHP attributes method, for more information on that, see their [documentation](https://zircote.github.io/swagger-php/guide/attributes.html).
