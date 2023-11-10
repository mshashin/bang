<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserCaseExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('userscase', [$this, 'usersCase']),
        ];
    }

    public function usersCase (int $count = null)
    {
        if ($count) {
           if ($count % 10 == 1 and $count !=11 ) {
               return $count.' посетитель сайта';
           }
           elseif ($count % 10 == 2 and $count !=12) {
                return $count.' посетителя сайта';
           }
           elseif ($count % 10 == 3 and $count !=13) {
                return $count.' посетителя сайта';
           }
           elseif ($count % 10 == 4 and $count !=14) {
               return $count.' посетителя сайта';
           }
           else {
               return $count.' посетителей сайта';
           }

        }
        else
            return null;
    }

    public function getName()
    {
        return 'users_case_extension';
    }
}