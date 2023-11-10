<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InterpretationCaseExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('interscase', [$this, 'interpretationsCase']),
        ];
    }

    public function interpretationsCase (int $count = null)
    {
        if ($count) {
           if ($count % 10 == 1 and $count !=11 ) {
               return $count.' толкование';
           }
           elseif ($count % 10 == 2 and $count !=12) {
                return $count.' толкования';
           }
           elseif ($count % 10 == 3 and $count !=13) {
                return $count.' толкования';
           }
           elseif ($count % 10 == 4 and $count !=14) {
               return $count.' толкования';
           }
           else {
               return $count.' толкований';
           }

        }
        else
            return null;
    }

    public function getName()
    {
        return 'inters_case_extension';
    }
}