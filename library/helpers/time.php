<?php
function time_ago(string|int|\DateTimeInterface $input, \DateTimeInterface|null $now = null): string
{
    // Normalize input to DateTimeImmutable
    if ($input instanceof \DateTimeInterface) {
        $dt = \DateTimeImmutable::createFromInterface($input);
    } elseif (is_int($input)) {
        $dt = (new \DateTimeImmutable())->setTimestamp($input);
    } else {
        // Accept strings like "2025-11-25 18:24:52.159 +0530"
        $dt = new \DateTimeImmutable($input);
    }

    // Normalize "now"
    $now = $now instanceof \DateTimeInterface
        ? \DateTimeImmutable::createFromInterface($now)
        : new \DateTimeImmutable('now', new \DateTimeZone(date_default_timezone_get()));

    // If identical moments
    if ($dt == $now) {
        return 'just now';
    }

    $past = $dt < $now;
    $diff = $past ? $now->diff($dt) : $dt->diff($now);

    // Determine the largest non-zero unit (weeks derived from days)
    if ($diff->y > 0) {
        $value = $diff->y;
        $unit = 'year';
    } elseif ($diff->m > 0) {
        $value = $diff->m;
        $unit = 'month';
    } elseif ($diff->d >= 7) {
        $value = intdiv($diff->d, 7);
        $unit = 'week';
    } elseif ($diff->d > 0) {
        $value = $diff->d;
        $unit = 'day';
    } elseif ($diff->h > 0) {
        $value = $diff->h;
        $unit = 'hour';
    } elseif ($diff->i > 0) {
        $value = $diff->i;
        $unit = 'minute';
    } else {
        $value = $diff->s;
        $unit = 'second';
    }

    // Friendly formatting for singular values (optional: "a week" vs "1 week")
    $textValue = $value === 1 ? '1' : (string) $value;
    $plural = $value === 1 ? '' : 's';
    $result = "{$textValue} {$unit}{$plural}";

    return $past ? "{$result} ago" : "in {$result}";
}

function getChildTypeFromAge(string $dob): string
{
    try {
        $dt  = new \DateTimeImmutable($dob);
    } catch (\Exception $e) {
        throw new \InvalidArgumentException('Invalid date string provided: ' . $e->getMessage());
    }

    $now = new \DateTimeImmutable('now');

    if ($dt > $now) {
        throw new \InvalidArgumentException('Date of birth is in the future.');
    }

    $diff = $now->diff($dt);

    // Age components
    $years  = $diff->y;
    $months = $diff->m;
    $days   = $diff->d;

    // Configurable thresholds (tweak as needed)
    $NEWBORN_MAX_DAYS      = 28;   // less than 28 days => newborn
    $INFANT_MAX_MONTHS     = 12;   // < 12 months => infant
    $TODDLER_MAX_YEARS     = 3;    // < 3 years => toddler
    $PRESCHOOL_MAX_YEARS   = 6;    // < 6 years => preschool
    $CHILD_MAX_YEARS       = 13;   // < 13 years => child
    $TEEN_MAX_YEARS        = 18;   // < 18 years => teen

    // Determine category; check the most-specific conditions first
    if ($years === 0 && $months === 0 && $days < $NEWBORN_MAX_DAYS) {
        return 'newborn';
    }

    if ($years === 0 && ($months < $INFANT_MAX_MONTHS || ($months === ($INFANT_MAX_MONTHS - 1) && $days >= 0))) {
        // still under 12 months
        return 'infant';
    }

    if ($years < $TODDLER_MAX_YEARS) {
        return 'toddler';
    }

    if ($years < $PRESCHOOL_MAX_YEARS) {
        return 'preschool';
    }

    if ($years < $CHILD_MAX_YEARS) {
        return 'child';
    }

    if ($years < $TEEN_MAX_YEARS) {
        return 'teen';
    }

    return 'adult';
}

function calculateAge(string $dob, ?\DateTimeImmutable $asOf = null): string
{
    try {
        $birth = new \DateTimeImmutable($dob);
    } catch (\Throwable $e) {
        throw new \InvalidArgumentException('Invalid date string provided: ' . $e->getMessage());
    }

    $now = $asOf ?? new \DateTimeImmutable('now');

    if ($birth > $now) {
        throw new \InvalidArgumentException('Date of birth is in the future.');
    }

    // $diff->y, ->m, ->d are the canonical components
    $diff = $now->diff($birth);

    // DateInterval::days gives total days as an integer when available
    // (DateTimeImmutable::diff always sets days when both operands are DateTimeImmutable)
    $totalDays = $diff->days ?? (int) floor(($now->getTimestamp() - $birth->getTimestamp()) / 86400);

    $age = "0 years";

    if ($diff->y > 0) {
        $age = "{$diff->y} years";
    } else if ($diff->m > 0) {
        $age = "{$diff->m} months";
    } else if ($diff->d) {
        $age = "{$diff->d} days";
    }

    return $age;
}