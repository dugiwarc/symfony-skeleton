<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->$locale = $locale;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter'])
        ];
    }

    public function priceFilter($number): string
    {
        return '$'.number_format($number, '2', '.','.');
    }

    public function getGlobals(): array
    {
        // TODO: Implement getGlobals() method.
        return [
            'locale' => $this->locale
        ];
    }
}