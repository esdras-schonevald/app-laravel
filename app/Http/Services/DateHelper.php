<?php

declare(strict_types=1);

namespace App\Http\Services;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DateHelper
{
    public static function dateToBr(?\DateTimeInterface $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return $date->format('d/m/Y H\hi');
    }

    public static function dateFromBr(?string $date): ?\DateTimeInterface
    {
        if ($date === null) {
            return null;
        }

        if (self::isDateBR($date)) {
            return \DateTimeImmutable::createFromFormat('d/m/Y', $date);
        }

        if (self::isDateTimeBR($date)) {
            return \DateTimeImmutable::createFromFormat('d/m/Y H\hi', $date);
        }

        throw new UnprocessableEntityHttpException('Invalid date format');
    }

    public static function brToDb(?string $date): ?string
    {
        if ($date === null) {
            return null;
        }

        if (self::isDateBR($date)) {
            $datePHP = \DateTimeImmutable::createFromFormat('d/m/Y', $date);

            return $datePHP->format('Y-m-d H:i:s');
        }

        if (self::isDateTimeBR($date)) {
            $datePHP = \DateTimeImmutable::createFromFormat('d/m/Y H\hi', $date);
            return $datePHP?->format('Y-m-d H:i:s');
        }

        throw new UnprocessableEntityHttpException('Invalis date format');
    }

    public static function dbToBr(?string $date): ?string
    {
        if ($date === null) {
            return null;
        }

        $datePHP = new \DateTimeImmutable($date);

        return $datePHP?->format('d/m/Y H\hi');
    }

    public static function isDateBR(string $date): bool
    {
        return (bool) preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/(19|20)([\d]{2})$/', $date);
    }

    public static function isDateTimeBR(string $date): bool
    {
        return (bool) preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/(19|20)([\d]{2})\s([0-1][\d]|2[0-4])h([0-5]\d|60)$/', $date);
    }
}
