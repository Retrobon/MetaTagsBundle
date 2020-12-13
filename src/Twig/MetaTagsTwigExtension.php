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

    private array $conf;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * SeoExtension constructor.
     * @param RequestStack $requestStack
     * @param MetaTags $metaTags
     * @param array $conf
     */
    public function __construct(RequestStack $requestStack, MetaTags $metaTags, array $conf = [])
    {
        $this->metaTags = $metaTags;
        $this->conf = $conf;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('metaTags', [$this, 'meta'], ['is_safe' => ['html']]),
        );
    }

    /**
     *
     * @param array $tags
     * @return string
     */
    public function meta(array $tags = []): string
    {
        $meta = $this->metaTags;
        if ($tags) {
            $meta->setTags($tags);
        }
        if ($this->conf['rewrite_default']) {
            $userMeta = $meta->getTags();
            $diff = array_diff(array_keys($this->conf['tags']), array_keys($userMeta));
            foreach ($diff as $item) {
                $meta->meta($item, $this->conf['tags'][$item]);
            }
        } elseif (!$meta->getTags()) {
            $meta->setTags($this->conf['tags']);
        }
        if (!isset($meta->getTags()['canonical'])) {
            $request = $this->requestStack->getCurrentRequest();
            $meta->canonical($request->getSchemeAndHttpHost() . $request->getPathInfo());
        }

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
