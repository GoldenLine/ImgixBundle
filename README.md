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

License
-------

This bundle is released under the MIT license. See the complete license in the
bundle:

    Resources/meta/LICENSE