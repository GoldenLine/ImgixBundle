GoldenLineImgixBundle
=====================

Integration of the imgix library into Symfony.

Installation
-------------

The best way to install this bundle is by using [Composer](http://getcomposer.org). Simply run:

``` bash
$ php composer.phar require goldenline/imgix-bundle dev-master
```

Then, enable the bundle:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new GoldenLine\ImgixBundle\GoldenLineImgixBundle(),
    );
}
```

Finally add your sources:
```yml
golden_line_imgix:
    default_source: folder
    sources:
        folder:
            domains:  [ acme.imgix.net ]
        proxy:
            domains:  [ acme-proxy.imgix.net ]
            sign_key: abcd1234
```

Usage
-----

In your Twig template just do:

```twig
<!-- Absolute URL with a web proxy source -->
<img src="{{ imgix('https://assets-cdn.github.com/images/modules/logos_page/Octocat.png', source='proxy', width=200, height=166) }}" width="200" height="166"/>

<!-- Absolute path with a web folder source -->
<img src="{{ imgix('images/modules/logos_page/Octocat.png', width=200, height=166) }}" width="200" height="166"/>
```

License
-------

This bundle is released under the MIT license. See the complete license in the
bundle:

    Resources/meta/LICENSE
