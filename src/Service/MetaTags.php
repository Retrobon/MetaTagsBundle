<?php

namespace retrobon\MetaTagsBundle\Service;

use retrobon\MetaTagsBundle\Interfaces\MetaTagsInterface;

class MetaTags implements MetaTagsInterface
{
    protected array $tags = [];

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
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string $name
     * @param string $value
     * @return MetaTagsInterface
     */
    public function meta(string $name, string $value): MetaTagsInterface
    {
        switch ($name) {
            case 'title':
                return $this->title($value);
                break;
            case 'canonical':
                return $this->canonical($value);
                break;
            case 'shortlink':
                return $this->shortlink($value);
                break;
            default:
                $this->push('meta', ['name' => $name, 'content' => $value], false);
        }
        return $this;
    }

    /**
     * @param string $value
     * @return MetaTagsInterface
     */
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
     * @param string $name
     * @param array $attrs
     * @param bool $endTag
     * @return MetaTagsInterface
     */
    public function push(string $name, array $attrs, $endTag = false): MetaTagsInterface
    {
        if (!isset($attrs['name'])) {
            $index = $attrs['rel'];
        } else {
            $index = $attrs['name'];
        }
        $this->tags[$index] = ['name' => $name, 'value' => $attrs, 'trailing_tag' => $endTag];

        return $this;
    }

    /**
     * @param array $tags
     * @return string
     */
    public function build(array $tags): string
    {

        $out = '';
        foreach ($tags as $tag) {
            if ($tag['trailing_tag']) {
                $out .= "\n<{$tag['name']}>" . $tag['value']['content'] . "</{$tag['name']}>";
            } else {
                $out .= "\n<{$tag['name']} ";
                foreach ($tag['value'] as $a => $v) {
                    $out .= $a . '="' . $v . '" ';
                }
                $out .= "/>";
            }
        }
        return $out;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->build($this->tags);
    }
}
