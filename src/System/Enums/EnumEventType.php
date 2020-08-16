<?php

use Symfony\Component\VarDumper\Caster\EnumStub;

class EnumEventType extends EnumStub
{
    const MUSIC = 'music';
    const MOVIE = 'movie';
    const BUSINESS = 'business';

    public static function getEnumValues()
    {        
        return [
            self::MUSIC,
            self::MOVIE,
            self::BUSINESS 
        ];
    }

    protected static $typeName = [
        self::MUSIC    => 'Music',
        self::MOVIE => 'Movie',
        self::BUSINESS => 'Business',
    ];
    
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

}
