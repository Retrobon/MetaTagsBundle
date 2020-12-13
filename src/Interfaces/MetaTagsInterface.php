<?php

namespace retrobon\MetaTagsBundle\Interfaces;


interface MetaTagsInterface
{

    public function meta(string $name, string $value): MetaTagsInterface;

    public function getTags(): array;

    public function push(string $name, array $attrs): MetaTagsInterface;

    public function build(array $tags): string;

    public function __toString(): string;
}
