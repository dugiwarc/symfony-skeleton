<?php


namespace App\Twig;

use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use \Twig\TwigTest;

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

    public function getTests(): array
    {
        return [
            new TwigTest(
                'like',
                function($obj) { return $obj instanceof LikeNotification; }
            )
        ];
    }

    public function getGlobals(): array
    {
        // TODO: Implement getGlobals() method.
        return [
            'locale' => $this->locale
        ];
    }
}