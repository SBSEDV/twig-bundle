<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhpFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('str_pad', str_pad(...)),
            new TwigFilter('url_decode', urldecode(...)),
            new TwigFilter('basename', basename(...)),
            new TwigFilter('pathinfo', pathinfo(...)),
        ];
    }
}
