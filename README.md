## Installation
Install the bundle with the command:

## Configuration

Default values

There are two sections in the configuration:
* `tags`: array meta tags
 * special values:
   * `title` - displayed with a closing tag;
   * `canonical` - with link attribute, if not specified, a link with Request will be displayed;
   * `shortlink` - with link attribute;
* `rewrite_default` - if specified, values will be added to the default values

**In your `meta_tags.yaml`:**
```yml
meta_tags:
    tags:
        title: 'default title'
        description: 'default description'
        keywords: 'default, keywords'
        robots: 'index, follow, all'
        author: 'default author'
    rewrite_default: true
```
**In your view:**
```twig
<head>
    {{ metaTags() }}
</head>
```
or add array value in twig:
```twig
<head>
    {{ metaTags({'title': 'test'}) }}
</head>
```
## Todo
* Packagist
