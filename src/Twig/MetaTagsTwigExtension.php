<?php

namespace retrobon\MetaTagsBundle\Twig;

use retrobon\MetaTagsBundle\Service\MetaTags;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MetaTagsTwigExtension extends AbstractExtension
{
    /**
     * @var MetaTags
     */
    private MetaTags $metaTags;


    /**
     * SeoExtension constructor.
     * @param MetaTags $metaTags
     */
    public function __construct(MetaTags $metaTags)
    {
        $this->metaTags = $metaTags;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('meta', [$this, 'meta'], ['is_safe' => ['html']]),
        );
    }

    /**
     *
     * @return string
     */
    public function meta(): string
    {

        $meta = $this->metaTags;



//        if ($this->autoUrl) {
//
//        }

        return $meta;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'meta.twig.meta_extension';
    }
}
