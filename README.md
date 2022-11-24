# sbsedv/twig-bundle

A [Symfony ^6.1](https://symfony.com/) bundle that adds some commonly used filters and functions to the twig templating language.

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

### **TimezoneEventListener**

Sets the default timezone used by twigs date filter.
[More information can be found here.](https://twig.symfony.com/doc/3.x/filters/date.html#timezone)

```yaml
# config/packages/sbsedv_twig.yaml

sbsedv_twig:
    event_listeners:
        timezone_listener:
            enabled: true # Enabled by default
            cookie_name: timezone # the cookie to look for
            session_name: timezone # the session key to look for
            header_name: X-Timezone # the header to look for
```

---

### **LocalizationEventListener**

Sets locale specific default options for the "numer_format" and "date" filter.

Values are definied in the `sbsedv_twig` translation domain. See [here](translations/sbsedv_twig.en.yaml).

```yaml
# config/packages/sbsedv_twig.yaml

sbsedv_twig:
    event_listeners:
        localization_listener:
            enabled: true # Enabled by default
```
