<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('stylesheet', [$this, 'createStylesheet'], ['is_safe' => ['html']]),
            new TwigFunction('javascript', [$this, 'createJavascript'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Create a link-stylesheet import tag.
     *
     * @param string $href    Path to the stylesheet.
     * @param string $type    [optional] The type of stylesheet (defaults to text/css).
     * @param string $rel     [optional] The relationship attribute (defaults to stylesheet).
     * @param array  $options [optional] More options that will be appended as attributes.
     *
     * @return string The link-tag
     */
    public function createStylesheet(string $href, string $type = 'text/css', string $rel = 'stylesheet', array $options = []): string
    {
        return trim("<link type='{$type}' rel='{$rel}' href='{$href}' {$this->arrayToProperties($options)}").' />';
    }

    /**
     * Create a script tag.
     *
     * @param string $src     Path to the script file.
     * @param string $type    [optional] The type of script (defaults to text/javascript).
     * @param array  $options [optional] More options that will be appended as attributes (defaults to [defer]).
     *
     * @return string The script-tag
     */
    public function createJavascript(string $src, string $type = 'text/javascript', array $options = ['defer']): string
    {
        return trim("<script type='{$type}' src='{$src}' {$this->arrayToProperties($options)}").'></script>';
    }

    /**
     * Helper method that converts an array to html attributes.
     *
     * @param array $options The array to convert.
     *
     * @return string The html-attributes as string
     */
    private function arrayToProperties(array $options): string
    {
        $properties = [];

        foreach ($options as $key => $value) {
            if (is_int($key)) {
                $properties[] = $value;
                continue;
            }

            $properties[] = "{$key}=\"{$value}\"";
        }

        return implode(' ', $properties);
    }
}
