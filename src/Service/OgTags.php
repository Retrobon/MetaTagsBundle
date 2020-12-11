<?php

namespace retrobon\MetaTagsBundle\Service;

use retrobon\MetaTagsBundle\Interfaces\MetaTagsInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MetaTags implements MetaTagsInterface
{
    protected bool $canonical = false;
    protected array $supported = ['title', 'description'];
    protected array $tw = [];
    protected array $og = [];
    protected array $tags = [];
    private array $conf;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * MetaTags constructor.
     * @param RequestStack $requestStack
     * @param array $conf
     */
    public function __construct(RequestStack $requestStack, array $conf = [])
    {
        $this->conf = $conf;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        foreach ($tags as $n => $v) {
            $this->meta($n, $v);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @return MetaTagsInterface
     */
    public function meta(string $name, string $value): MetaTagsInterface
    {
//        if (in_array($name, $this->supported)) {
//            if ($this->conf['og']) {
//                $this->facebook($name, $value);
//            }
//            if ($this->conf['tw']) {
//                $this->twitter($name, $value);
//            }
//        }
        switch ($name) {
            case 'title':
                return $this->title($value);
                break;
            case 'canonical':
                return $this->canonical($value);
                break;
            case 'url':
                return $this->url($value);
                break;
            default:
                $this->push('meta', ['name' => $name, 'content' => $value], false);
        }
        return $this;
    }

    public function title(string $value): MetaTagsInterface
    {
        return $this->push(
            'title', ['name' => 'title', 'content' => $value], true
        );
    }

    /**
     * @param string $url
     * @return MetaTagsInterface
     */
    public function canonical(string $url): MetaTagsInterface
    {
        $this->canonical = true;
        return $this->push('link', ['rel' => 'canonical', 'href' => $url], false);
    }

    /**
     * @param string $url
     * @return MetaTagsInterface
     */
    public function shortlink(string $url): MetaTagsInterface
    {
        return $this->push('link', ['rel' => 'shortlink', 'href' => $url], false);
    }

    /**
     * @param string $url
     * @return MetaTagsInterface
     */
    public function url(string $url): MetaTagsInterface
    {
        if ($this->canonical === false) {
            $this->canonical($url);
        }
//        if ($this->conf['og']) {
//            $this->facebook('url', $url);
//        }
//        if ($this->conf['tw']) {
//            $this->twitter('url', $url);
//        }
        return $this;
    }

    /**
     * @param string $name
     * @param array $attrs
     * @param bool $endTag
     * @return MetaTagsInterface
     */
    public function push(string $name, array $attrs, $endTag = false): MetaTagsInterface
    {
        $this->tags[$name][$attrs[array_key_first($attrs)]] = [$name, $attrs, $endTag];
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return MetaTagsInterface
     */
    public function facebook(string $name, string $value): MetaTagsInterface
    {
        $this->og['meta'][] = ['meta', ['property' => "og:{$name}", 'content' => $value], false];
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return MetaTagsInterface
     */
    public function twitter(string $name, string $value): MetaTagsInterface
    {
        $this->tw['meta'][] = ['meta', ['property' => "twitter:{$name}", 'content' => $value], false];
        return $this;
    }

    /**
     * @param string $url
     * @param string $card
     * @return MetaTagsInterface
     */
    public function image(string $url, string $card = 'summary_large_image'): MetaTagsInterface
    {
        return $this->facebook('image', $url)->twitter('card', $card)->twitter('image', $url);
    }

    /**
     * @param array $tags
     * @return string
     */
    public function build(array $tags): string
    {
        $out = '';
        foreach ($tags as $group) {
            foreach ($group as $tag) {
                if ($tag[2]) {
                    $out .= "\n<{$tag[0]}>" . $tag[1]['content'] . "</{$tag[0]}>";
                } else {
                    $out .= "\n<{$tag[0]} ";
                    foreach ($tag[1] as $a => $v) {
                        $out .= $a . '="' . $v . '" ';
                    }
                    $out .= "/>";
                }
            }
        }
        return $out;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        dump($this->conf);
        if (!$this->tags) {
            $this->setTags($this->conf['tags']);
        }

        if ($this->conf['auto_url']) {
            $request = $this->requestStack->getCurrentRequest();
            $this->url($request->getSchemeAndHttpHost() . $request->getPathInfo());
        }
        return $this->build($this->tags) . $this->build($this->tw) . $this->build($this->og);
    }
}
