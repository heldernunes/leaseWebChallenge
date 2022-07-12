<?php

namespace App\Tests\Helper;

use \DateTime;

class Faker
{
    protected static function getRandomWord(int $len = 10): string
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    public static function text(int $wordCount = null): string
    {
        if (!$wordCount) {
            $wordCount = rand(3, 10);
        }

        $text = '';
        for ($i = 0; $i < $wordCount; $i++) {
            $text .= self::getRandomWord(rand(3, 12)) . ' ';
        }

        return trim($text);
    }

    public static function word(): string
    {
        return self::getRandomWord(rand(3, 12));
    }

    public static function integer(int $min = 1, int $max = 1000): int
    {
        return rand($min, $max);
    }

    public static function float(int $min = 1, int $max = 1000): int
    {
        return self::integer($min, $max - 1) + (self::integer(0, PHP_INT_MAX - 1) / PHP_INT_MAX);
    }

    public static function date(int $min = 1, int $max = 1000): DateTime
    {
        return (new DateTime())->modify('+' . self::integer($min, $max) . ' day');
    }

    public static function dateString(int $min = 1, int $max = 1000): string
    {
        return self::date($min, $max)->format('Y-m-d H:i:s');
    }

    public static function bool(): bool
    {
        return (bool)rand(0, 1);
    }
}
