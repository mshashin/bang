<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RussianDayWeekExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('russian_day_week', [$this, 'russianDayweek']),
        ];
    }

    public function russianDayweek(\DateTime $date = null)
    {
        if ($date) {
            $days = [ 'Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
            return $days[$date->format('w')];
        }
        else
            return null;
    }

    public function getName()
    {
        return 'russian_day_week_extension';
    }
}