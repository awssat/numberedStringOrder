<?php

namespace Awssat\numberedStringOrder;

class numberedStringOrder
{
    /**
     * Get numbers from strings.
     *
     * @param $array
     *
     * @return array
     */
    public function getNumbers($array)
    {
        return $this->compile($array);
    }

    /**
     * Sort by numbers.
     *
     * @param $array
     *
     * @return array
     */
    public function sort($array)
    {
        $result = $this->compile($array);

        asort($result);

        return array_keys($result);
    }

    /**
     * Words to int.
     *
     * @param $str
     *
     * @return float|int|mixed
     */
    public function str2int($str)
    {
        return $this->arabicW2N($str) + $this->englishW2N($str);
    }

    /**
     * @param $str
     *
     * @return float|int
     */
    private function englishW2N($str)
    {
        $numbers = [
            'zero'      => 0,
            'one'       => 1,
            'two'       => 2,
            'three'     => 3,
            'four'      => 4,
            'five'      => 5,
            'six'       => 6,
            'seven'     => 7,
            'eight'     => 8,
            'nine'      => 9,
            'ten'       => 10,
            'eleven'    => 11,
            'twelve'    => 12,
            'thirteen'  => 13,
            'fourteen'  => 14,
            'fifteen'   => 15,
            'sixteen'   => 16,
            'seventeen' => 17,
            'eighteen'  => 18,
            'nineteen'  => 19,
            'twenty'    => 20,
            'thirty'    => 30,
            'forty'     => 40,
            'fourty'    => 40,
            'fifty'     => 50,
            'sixty'     => 60,
            'seventy'   => 70,
            'eighty'    => 80,
            'ninety'    => 90,
            'hundred'   => 100,
            'thousand'  => 1000,
            'million'   => 1000000,
            'billion'   => 1000000000, ];

        $str = preg_replace('/[^a-zA-Z]+/', ' ', $str);
        $words = explode(' ', $str);
        $total = 0;
        $force_addition = false;
        $last_digit = null;
        $final_sum = [];

        foreach ($words as $word) {
            if (!isset($numbers[$word]) && $word != 'and') {
                continue;
            }

            $word = strtolower($word);

            if ($word == 'and') {
                if ($last_digit === null) {
                    $total = 0;
                }
                $force_addition = true;
            } else {
                if ($force_addition) {
                    $total += $numbers[$word];
                    $force_addition = false;
                } else {
                    if ($last_digit !== null && $last_digit > $numbers[$word]) {
                        $total += $numbers[$word];
                    } else {
                        if ($total == 0) {
                            $total = $numbers[$word];
                        } else {
                            $total *= $numbers[$word];
                        }
                    }
                }
                $last_digit = $numbers[$word];

                if ($numbers[$word] >= 1000) {
                    $final_sum[] = $total;
                    $last_digit = null;
                    $force_addition = false;
                    $total = 1;
                }
            }
        }

        $final_sum[] = $total;

        return array_sum($final_sum);
    }

    /**
     * @param $str
     *
     * @return int|mixed
     */
    private function arabicW2N($str)
    {
        // Normalization phase
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ه', 'ة', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = str_replace(['ـ', 'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ', 'ّ', 'ال'], '', $str);
        $str = str_replace('مائة', 'مئة', $str);
        $str = str_replace(['احدى', 'احد'], 'واحد', $str);
        $str = str_replace(['اثنا ', 'اثني ', ' اثنتا ', 'اثنتي '], 'اثنان', $str);
        $str = trim($str);

        $spell = [
            'واحد'    => 1,
            'واحدة'   => 1,
            'اثنان'   => 2,
            'اولى'    => 1,
            'ثانية'   => 2,
            'ثاني'    => 2,
            'ثالثة'   => 3,
            'ثالث'    => 3,
            'خامسة'   => 5,
            'خامس'    => 5,
            'سادسة'   => 6,
            'سادس'    => 6,
            'سابعة'   => 7,
            'سابع'    => 7,
            'ثامنة'   => 8,
            'ثامن'    => 8,
            'تاسعة'   => 9,
            'تاسع'    => 9,
            'عاشر'    => 10,
            'اثنين'   => 2,
            'اثنتان'  => 2,
            'اثنتين'  => 2,
            'ثلاث'    => 3,
            'اربع'    => 4,
            'خمس'     => 5,
            'ست'      => 6,
            'سبع'     => 7,
            'ثماني'   => 8,
            'تسع'     => 9,
            'عشر'     => 10,
            'ثلاثة'   => 3,
            'اربعة'   => 4,
            'خمسة'    => 5,
            'ستة'     => 6,
            'سبعة'    => 7,
            'ثمانية'  => 8,
            'تسعة'    => 9,
            'عشرة'    => 10,
            'عشرون'   => 20,
            'ثلاثون'  => 30,
            'اربعون'  => 40,
            'خمسون'   => 50,
            'ستون'    => 60,
            'سبعون'   => 70,
            'ثمانون'  => 80,
            'تسعون'   => 90,
            'عشرين'   => 20,
            'ثلاثين'  => 30,
            'اربعين'  => 40,
            'خمسين'   => 50,
            'ستين'    => 60,
            'سبعين'   => 70,
            'ثمانين'  => 80,
            'تسعين'   => 90,
            'مئتان'   => 200,
            'مئتين'   => 200,
            'ثلاثمئة' => 300,
            'اربعمئة' => 400,
            'خمسمئة'  => 500,
            'ستمئة'   => 600,
            'سبعمئة'  => 700,
            'ثمانمئة' => 800,
            'تسعمئة'  => 900,

            'ثلاث مئة' => 300,
            'اربع مئة' => 400,
            'خمس مئة'  => 500,
            'ست مئة'   => 600,
            'سبع مئة'  => 700,
            'ثمان مئة' => 800,
            'تسع مئة'  => 900,

            'مئة'     => 100,
            'الف'     => 1000,
            'الفان'   => 2000,
            'مليون'   => 1000000,
            'مليونان' => 2000000,
            'مليونين' => 2000000,
            'مليار'   => 1000000000,
            'ملياران' => 2000000000,
            'مليارين' => 2000000000,
        ];

        $complications = [
            'مليارات' => 1000000000,
            'مليار'   => 1000000000,
            'ملايين'  => 1000000,
            'مليون'   => 1000000,
            'الاف'    => 1000,
            'الف'     => 1000,
        ];

        $total = 0;

        foreach ($complications as $complication => $by) {
            if (preg_match("/(.*)\s+{$complication}/", $str, $result) && isset($result[1])) {
                $result = " {$result[1]} ";
                foreach ($spell as $word => $value) {
                    if (strpos($result, "$word ") !== false) {
                        $str = str_replace("$word ", ' ', $str);
                        $result = str_replace("$word ", ' ', $result);
                        $total += $value;
                    }
                }

                $str = str_replace(" $complication", ' ', $str);
                $total *= $by;
            }
        }

        $str = " {$str} ";

        foreach ($spell as $word => $value) {
            if (strpos($str, "$word ") !== false) {
                $str = str_replace("$word ", ' ', $str);
                $total += $value;
            }
        }

        return $total;
    }

    /**
     * @param $term
     *
     * @return string
     */
    private function split_numbers($term)
    {
        $text = '';
        $splits = preg_split("~(\d+)~", $term, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($splits as $split) {
            $text .= ' '.$split;
        }

        return $text;
    }

    /**
     * @param $text
     *
     * @return mixed|null|string|string[]
     */
    private function normalize_text($text)
    {
        $text = str_replace(['%', '؛', '،', '<', '>', '«', '»', '|', '[', ']', '؟', '?', '(', ')', '/', '=', '+', '-', '@', '!', '؟', '#', '$', '^', '&', '_', '*', '"', "'", '{', '}', '~', '`', '`', 'ـ', '.'], '', $text);
        $text = str_replace(['١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '٠'], ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'], $text);
        $text = preg_replace('!&#x66([0-9]{1});!', '$1', $text);
        $text = $this->split_numbers($text);

        return $text;
    }

    /**
     * @param $array
     *
     * @return array
     */
    private function compile($array)
    {
        $result = [];

        foreach ($array as $string) {
            $needle = $this->normalize_text($string);

            //lets get number
            //if not then arabic word
            //if not then get it in last
            if (preg_match('!\d+!', $needle, $num)) {
                $sort = $num[0];
                $result[$string] = $sort;
            } else {
                $ar_int = $this->str2int($needle);

                if ($ar_int != 0) {
                    $result[$string] = $ar_int;
                } else {
                    $result[$string] = $string;
                }
            }
        }

        return $result;
    }
}
