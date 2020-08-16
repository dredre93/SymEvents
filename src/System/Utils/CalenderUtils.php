<?php
namespace App\System\Utils;

use Symfony\Component\Validator\Constraints\Date;

class CalenderUtils
{
    public function getDaysOfMonth()
    {
        $today = new Date();
        
        return $today;
    }

}