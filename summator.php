<?php declare(strict_types = 1);

function sumBigNumbers(string $numberA, string $numberB): string
{
    $isNumberFormatCorrect = function (string $number): bool {
        return (bool)preg_match('/^\-?\d+$/', $number);
    };

    $compareNumbers = function (string $numberA, string $numberB): int {
        $length1 = strlen($numberA);
        $length2 = strlen($numberB);

        if ($length1 > $length2) {
            return 1;
        }

        if ($length1 < $length2) {
            return -1;
        }

        for ($i = 0; $i < $length1; $i++) {
            if ((int)$numberA[$i] > (int)$numberB[$i]) {
                return 1;
            }

            if ((int)$numberA[$i] < (int)$numberB[$i]) {
                return -1;
            }
        }

        return 0;
    };

    $abs = function (string $number): string {
        return $number[0] === '-' ? substr($number, 1) : $number;
    };

    $operatorPlus = function (string $numberA, string $numberB): string {
        $result = '';

        $length1 = strlen($numberA);
        $length2 = strlen($numberB);

        $maxLength = max($length1, $length2);
        $carry = 0;

        for ($i = 1; $i <= $maxLength; $i++) {
            $digit1 = $numberA[$length1 - $i] ?? 0;
            $digit2 = $numberB[$length2 - $i] ?? 0;
            $sum = $digit1 + $digit2 + $carry;

            $carry = $sum < 10 ? 0 : 1;
            $result = $sum % 10 . $result;
        }

        return $carry === 1 ? '1' . $result : $result;
    };

    $operatorMinus = function (string $numberA, string $numberB): string {
        $result = '';

        $length1 = strlen($numberA);
        $length2 = strlen($numberB);

        $maxLength = max($length1, $length2);
        $carry = 0;

        for ($i = 1; $i <= $maxLength; $i++) {
            $digit1 = $numberA[$length1 - $i] ?? 0;
            $digit2 = $numberB[$length2 - $i] ?? 0;
            $sum = 10 + $digit1 - $digit2 - $carry;

            $carry = $sum < 10 ? 1 : 0;
            $result = $sum % 10 . $result;
        }

        return $result;
    };

    if (!$isNumberFormatCorrect($numberA)) {
        throw new InvalidArgumentException("Passed number {$numberA} is incorrect value");
    }

    if (!$isNumberFormatCorrect($numberB)) {
        throw new InvalidArgumentException("Passed number {$numberB} is incorrect value");
    }

    $absNumberA = $abs($numberA);
    $absNumberB = $abs($numberB);
    $isNumberAPositive = $absNumberA === $numberA;
    $isNumberBPositive = $absNumberB === $numberB;

    if ($isNumberAPositive xor $isNumberBPositive) {
        $compare = $compareNumbers($numberA, $numberB);
        if ($compare === 0) {
            return '0';
        }

        if ($compare === -1) {
            $result = $operatorMinus($absNumberB, $absNumberA);
            $isNegativeResult = $isNumberAPositive;
        } else {
            $result = $operatorMinus($absNumberA, $absNumberB);
            $isNegativeResult = $isNumberBPositive;
        }
    } else {
        $result = $operatorPlus($absNumberA, $absNumberB);
        $isNegativeResult = !$isNumberAPositive && !$isNumberBPositive;
    }

    return $isNegativeResult ? '-' . $result : $result;
}

$numberA = '-20';
$numberB = '900';

try {
    $sum = sumBigNumbers($numberA, $numberB);
    echo 'Sum is: ' . $sum;
} catch (InvalidArgumentException $exception) {
    echo $exception->getMessage();
}