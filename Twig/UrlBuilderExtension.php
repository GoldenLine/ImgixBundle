<?php

namespace GoldenLine\ImgixBundle\Twig;

use Imgix\UrlBuilder;
use Symfony\Component\Routing\RequestContext;

class UrlBuilderExtension extends \Twig_Extension
{
    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @var UrlBuilder[]
     */
    private $urlBuilders;
    /**
     * @var
     */
    private $defaultSource;

    /**
     * @param RequestContext $requestContext
     * @param string $defaultSource
     */
    public function __construct(RequestContext $requestContext, $defaultSource)
    {
        $this->context = $requestContext;
        $this->urlBuilders = [];
        $this->defaultSource = $defaultSource;
    }

    /**
     * @param string $name
     * @param UrlBuilder $urlBuilder
     */
    public function addBuilder($name, UrlBuilder $urlBuilder)
    {
        $this->urlBuilders[$name] = $urlBuilder;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('imgix', array($this, 'getImgixUrl')),
        );
    }

    /**
     * Returns a public URL to the asset
     *
     * @param string $path
     * @param null|string $source
     * @param null|int $width
     * @param null|int $height
     * @param null|string $fit
     * @return string
     */
    public function getImgixUrl($path, $source = null, $width = null, $height = null, $fit = null)
    {
        $builder = $this->getUrlBuilder($source);

        // HTTP/HTTPS
        $isSecure = 'https' === $this->context->getScheme();
        $builder->setUseHttps($isSecure);

        // Size (optional)
        $params = [];

        if ($width > 0) {
            $params['w'] = (int) $width;
        }

        if ($height > 0) {
            $params['h'] = (int) $height;
        }

        if ($fit !== null) {
            $params['fit'] = $fit;
        }

        return $builder->createURL($path, $params);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'goldenline_imgix_url_builder_extension';
    }

    /**
     * Retruns an URL builder by source
     *
     * @param null|string $source
     * @return UrlBuilder
     */
    private function getUrlBuilder($source = null)
    {
        if (is_null($source)) {
            $source = $this->defaultSource;
        }

        if (!isset($this->urlBuilders[$source])) {
            throw new \InvalidArgumentException(sprintf('Invalid source "%s"', $source));
        }

        return $this->urlBuilders[$source];
    }
}
