<?php

function stringToArray(string $string): array
{
  return array_map(
    fn ($n) => (int) $n,
    str_split(strrev($string))
  );
}

function reverseStringLikeNumber(string $string): string
{
  return ltrim(strrev($string), '0');
}

function isMoreThanOneDigit(int $number): bool
{
  return strlen($number) > 1;
}

function sum_strings(string $a, string $b): string
{
  $reminder = 0;
  $result = '';
  $main = stringToArray(strlen($a) >= strlen($b) ? $a : $b);
  $secondary = stringToArray(strlen($a) < strlen($b) ? $a : $b);

  foreach ($main as $index => $number) {
    $sum = $reminder + $number + (@$secondary[$index] ?: 0);
    $reminder = isMoreThanOneDigit($sum) ? substr($sum, 0, 1) : 0;
    $result .= isMoreThanOneDigit($sum) ? substr($sum, 1) : $sum;
  }

  return reverseStringLikeNumber(
    (int) $reminder > 0 ?
      $result . $reminder :
      $result
  );
}

function test() {
    $start = strtotime('now');
    $format = 'Y-m-d H:i:s.U';
    $sum = 1;
    $i = 0;

    while ($i != '1000000') {
        $sum = sum_strings($sum, $i);
        $i = sum_strings($i, 1);
    }

    $end = strtotime('now');
    return [
        'start' => date($format, $start),
        'end' => date($format, $end),
        'diff time' => date('H:i:s.U', $end - $start),
        'sum' => $sum
    ];
}

echo print_r(test()), PHP_EOL;

?>
