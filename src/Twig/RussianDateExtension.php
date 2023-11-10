<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RussianDateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('russian_date', [$this, 'russianDate']),
        ];
    }

    public function russianDate(\DateTime $date = null)
    {
        if ($date) {
            $months = [1=>'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];
            //$date = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $key = $date->format('n');
            return $date->format('j ' . $months[$key] . ' Y');
        }
        else
            return null;
    }

    public function getName()
    {
        return 'russian_date_extension';
    }
}