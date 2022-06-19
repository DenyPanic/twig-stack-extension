Twig Stack Extension, used by Crate CMS
=======================================

Crate's Twig Stack Extension is a modified fork of filhocodes [Twig Stack Extension](https://github.com/filhocodes/twig-stack-extension),
which itself is HEAVILY related to the `aygon/twig-stack` package for Twig 2.*. However, this Twig 
extension has been especially designed for the use within Crate CMS, providing the following changes 
compared to filhocodes' version:

-   Supports initial stack content (`{% stack %} ... {% endstack %}`).
-   Requires an `{% endstack %}` tag or the `empty` keyword after the stack name.
-   Supports declaring `push` and `unshift` __outside__ the main block on `extends` templates.
-   Replaces the `prepend` tag name with `unshift` (configurable).
-   Replaces push/unshift ids with `pushOnce` and `unshiftOnce`.
-   Drops support for PHP < 8.0 (due to Crate's requirements).


Installation
------------

This package is not meant to be used outside of Crate's ecosystem and thus also not available on 
Composer's packagist. Of course you can still download and include this package manually, but you'd 
have to life with the really specific namespace path `Crate\View\Twig\...`.

More information about the integration of custom Twig extensions can be found on the official 
[Twig Documentation](https://twig.symfony.com/doc/3.x/) 


Run Tests
---------

The following command starts the PHPUnit testing service:

```
.\vendor\bin\phpunit tests
```


Basic Usage
-----------

Stacks are declared with the `{% stack [identifier] %}` directive and can already contain the basic 
elements, which should always be present. The end-tag `{% endstack %}` can be ommited, when the 
`[identifier]` part is followed by the keyword `empty`.

```twig
{# @layouts/base.twig #}
<!DOCTYPE html>
<html lang="en">
    <head>
        {% stack stylesheets %}
            <link type="text/css" href="basic.css" />
        {% endstack %}

        {% stack javascripts %}
            <script type="module" src="basic.js" defer></script>
        {% endstack %}
    </head>
    <body>
        {% stack js_top %}
            <script> console.log('Second Item'); </script>
        {% endstack %}

        {% block content %}{% endblock %}

        {% stack js_bottom empty %}
    </body>
</html>
```

Template files which extends the above template, or are included by any other one, are now able 
to add additional content to the declared stacks. The additional lines can either be added by using 
the `push` or the `unshift` method to either append or prepend the custom code respectively.

```twig
{% extends '@layouts/base' %}

{% push js_top %}
    <script> console.log('Third Item'); </script>
{% endpush %}

{% unshift js_top %}
    <script> console.log('First Item'); </script>
{% endunshift %}

{% block content %}
    Your Page Content
{% endblock %}
```

Using the `{% pushOnce %}` or `{% unshiftOnce %}` directives also allows you to prevent embedding 
the declared content multiple times. This can be useful, when such partials are included within a 
loop or similar.

```twig
{% pushOnce js_top %}
    <script> console.log('Just one time'); </script>
{% endpushOnce %}

{% unshiftOnce js_top %}
    <script> console.log('Just one time'); </script>
{% endunshiftOnce %}

{#
    Be aware: The above code will print 'Just one time' two times. The *Once 
    tags are filepath:linenumber related, not content related.
#}
```

License
-------
Published unter the MIT license.
Developer: DenyPanic (rat.md), [filhocodes](https://github.com/filhocodes)