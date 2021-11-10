# sbsedv/twig-bundle

A [Symfony ^5.2](https://symfony.com/) bundle that adds some commonly used filters and functions to the twig templating language.

---

### **Functions**

This bundle registers the following twig functions:

#### **call_static**

As the name implies, this forwards a static call to the given class and method.

This function requires two arguments, the class name and the method name.
Any extra parameters are passed to the static call as arguments.

This function returns the return value of the forwarded call.

```php
call_static(string $class, string $method, mixed ...$args): mixed
```

#### **parameter**

Wrapper for Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface.

```php
parameter(string $key): mixed
```

---

### **Filters**

This bundle also registers the following native PHP functions as twig filters:

```php
'123'|str_pad(5, '0') // => '00123'
'/var/www/index.html'|basename // => 'index.html'
'/var/www/index.html'|pathinfo(constant(PATHINFO_EXTENSION)) // => html
'test%40example.com'|url_decode // => 'test@example.com'
```

---

### Cookie Config

Many applications save user preferences in a json object in cookies (e.g color scheme, cookie compliance, menu opened / closed etc.).

This bundle has a convinient method to access those values stored in this cookie but it requires that you use a JSON-object as cookie value:

```php
{{ cookie_config('key') }} // => value OR null
```

By default, this method looks for a cookie named "`cookieconfig`".
You can overwrite the cookie name in your application with:

```yaml
# config/packages/sbsedv_twig.yaml
sbsedv_twig:
    cookie_config:
        cookie_name: cookieconfig
```

---

### **TimezoneListener**

Sets the default timezone globally by calling `setTimeZone()` on every DateTimeInterface object.
[More information can be found here.](https://twig.symfony.com/doc/3.x/filters/date.html#timezone)

```yaml
# config/packages/sbsedv_twig.yaml

sbsedv_twig:
    event_listeners:
        timezone_listener:
            enabled: true # Enabled by default
            priority: 100 # Symfony EventListener priority
            cookie_name: timezone # the cookie to look for
            session_name: timezone # the session key to look for
            header_name: X-Timezone # the header to look for
```

---

### **IAM Login Page**

This bundle provides a generic login page for [IAM](https://github.com/sbsedv/iam).

The route name is `sbsedv_twig_iam_login`.

**Attention**: This route will only function correctly if you have the following bundles enabled:

-   [sbsedv/iam-bundle](https://github.com/sbsedv/iam-bundle)
-   [sbsedv/extra-bundle](https://github.com/sbsedv/extra-bundle) (with company_info: true)
-   [symfony/asset](https://github.com/symfony/asset)

```yaml
# config/packages/sbsedv_twig.yaml

sbsedv_twig:
    iam_login:
        background_url: "your_asset_path_http_uri"
        stylesheets: ["fonts.scss", "app.scss"] # Default values

# config/routes.yaml
sbsedv_twig:
    resource: '@SBSEDVTwigBundle/Resources/config/routes.xml'
```
