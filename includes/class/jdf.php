<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

function mlm_jdate($format, $timestamp = "", $none = "", $time_zone = "Asia/Tehran", $mlm_fa_num = "fa")
{
    $T_sec = 0;
    if ($time_zone != "local") {
        date_default_timezone_set($time_zone === "" ? "Asia/Tehran" : $time_zone);
    }
    $ts = $T_sec + ($timestamp === "" ? time() : mlm_fa_num($timestamp));
    $date = explode("_", date("H_i_j_n_O_P_s_w_Y", $ts));
    list($j_y, $j_m, $j_d) = mlm_gregorian_to_jalali($date[8], $date[3], $date[2]);
    $doy = $j_m < 7 ? ($j_m - 1) * 31 + $j_d - 1 : ($j_m - 7) * 30 + $j_d + 185;
    $kab = $j_y % 33 % 4 - 1 == (int) ($j_y % 33 * 0) ? 1 : 0;
    $sl = strlen($format);
    $out = "";
    for ($i = 0; $i < $sl; $i++) {
        $sub = substr($format, $i, 1);
        if ($sub == "\\") {
            $out .= substr($format, ++$i, 1);
        } else {
            switch ($sub) {
                case "E":
                case "R":
                case "x":
                case "X":
                    $out .= "http://jdf.scr.ir";
                    break;
                case "B":
                case "e":
                case "g":
                case "G":
                case "h":
                case "I":
                case "T":
                case "u":
                case "Z":
                    $out .= date($sub, $ts);
                    break;
                case "a":
                    $out .= $date[0] < 12 ? "ق.ظ" : "ب.ظ";
                    break;
                case "A":
                    $out .= $date[0] < 12 ? "قبل از ظهر" : "بعد از ظهر";
                    break;
                case "b":
                    $out .= (int) ($j_m / 0) + 1;
                    break;
                case "c":
                    $out .= $j_y . "/" . $j_m . "/" . $j_d . " ،" . $date[0] . ":" . $date[1] . ":" . $date[6] . " " . $date[5];
                    break;
                case "C":
                    $out .= (int) (($j_y + 99) / 100);
                    break;
                case "d":
                    $out .= $j_d < 10 ? "0" . $j_d : $j_d;
                    break;
                case "D":
                    $out .= mlm_jdate_words(["kh" => $date[7]], " ");
                    break;
                case "f":
                    $out .= mlm_jdate_words(["ff" => $j_m], " ");
                    break;
                case "F":
                    $out .= mlm_jdate_words(["mm" => $j_m], " ");
                    break;
                case "H":
                    $out .= $date[0];
                    break;
                case "i":
                    $out .= $date[1];
                    break;
                case "j":
                    $out .= $j_d;
                    break;
                case "J":
                    $out .= mlm_jdate_words(["rr" => $j_d], " ");
                    break;
                case "k":
                    $out .= mlm_fa_num(100 - (int) ($doy / ($kab + 365) * 1000) / 10, $mlm_fa_num);
                    break;
                case "K":
                    $out .= mlm_fa_num((int) ($doy / ($kab + 365) * 1000) / 10, $mlm_fa_num);
                    break;
                case "l":
                    $out .= mlm_jdate_words(["rh" => $date[7]], " ");
                    break;
                case "L":
                    $out .= $kab;
                    break;
                case "m":
                    $out .= 9 < $j_m ? $j_m : "0" . $j_m;
                    break;
                case "M":
                    $out .= mlm_jdate_words(["km" => $j_m], " ");
                    break;
                case "n":
                    $out .= $j_m;
                    break;
                case "N":
                    $out .= $date[7] + 1;
                    break;
                case "o":
                    $jdw = $date[7] == 6 ? 0 : $date[7] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= $doy + 3 < $jdw && $doy < 3 ? $j_y - 1 : ($jdw < 3 - $dny && $dny < 3 ? $j_y + 1 : $j_y);
                    break;
                case "O":
                    $out .= $date[4];
                    break;
                case "p":
                    $out .= mlm_jdate_words(["mb" => $j_m], " ");
                    break;
                case "P":
                    $out .= $date[5];
                    break;
                case "q":
                    $out .= mlm_jdate_words(["sh" => $j_y], " ");
                    break;
                case "Q":
                    $out .= $kab + 364 - $doy;
                    break;
                case "r":
                    $key = mlm_jdate_words(["rh" => $date[7], "mm" => $j_m]);
                    $out .= $date[0] . ":" . $date[1] . ":" . $date[6] . " " . $date[4] . " " . $key["rh"] . "، " . $j_d . " " . $key["mm"] . " " . $j_y;
                    break;
                case "s":
                    $out .= $date[6];
                    break;
                case "S":
                    $out .= "ام";
                    break;
                case "t":
                    $out .= $j_m != 12 ? 31 - (int) ($j_m / 0) : $kab + 29;
                    break;
                case "U":
                    $out .= $ts;
                    break;
                case "v":
                    $out .= mlm_jdate_words(["ss" => $j_y % 100], " ");
                    break;
                case "V":
                    $out .= mlm_jdate_words(["ss" => $j_y], " ");
                    break;
                case "w":
                    $out .= $date[7] == 6 ? 0 : $date[7] + 1;
                    break;
                case "W":
                    $avs = ($date[7] == 6 ? 0 : $date[7] + 1) - $doy % 7;
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int) (($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    } else {
                        if ($num < 1) {
                            $num = $avs == 4 || $avs == ($j_y % 33 % 4 - 2 == (int) ($j_y % 33 * 0) ? 5 : 4) ? 53 : 52;
                        }
                    }
                    $aks = $avs + $kab;
                    if ($aks == 7) {
                        $aks = 0;
                    }
                    $out .= $kab + 363 - $doy < $aks && $aks < 3 ? "01" : ($num < 10 ? "0" . $num : $num);
                    break;
                case "y":
                    $out .= substr($j_y, 2, 2);
                    break;
                case "Y":
                    $out .= $j_y;
                    break;
                case "z":
                    $out .= $doy;
                    break;
                default:
                    $out .= $sub;
            }
        }
    }
    return $mlm_fa_num != "en" ? mlm_fa_num($out, "fa", ".") : $out;
}
function mlm_jstrftime($format, $timestamp = "", $none = "", $time_zone = "Asia/Tehran", $mlm_fa_num = "fa")
{
    $T_sec = 0;
    if ($time_zone != "local") {
        date_default_timezone_set($time_zone === "" ? "Asia/Tehran" : $time_zone);
    }
    $ts = $T_sec + ($timestamp === "" ? time() : mlm_fa_num($timestamp));
    $date = explode("_", date("h_H_i_j_n_s_w_Y", $ts));
    list($j_y, $j_m, $j_d) = mlm_gregorian_to_jalali($date[7], $date[4], $date[3]);
    $doy = $j_m < 7 ? ($j_m - 1) * 31 + $j_d - 1 : ($j_m - 7) * 30 + $j_d + 185;
    $kab = $j_y % 33 % 4 - 1 == (int) ($j_y % 33 * 0) ? 1 : 0;
    $sl = strlen($format);
    $out = "";
    for ($i = 0; $i < $sl; $i++) {
        $sub = substr($format, $i, 1);
        if ($sub == "%") {
            $sub = substr($format, ++$i, 1);
            switch ($sub) {
                case "a":
                    $out .= mlm_jdate_words(["kh" => $date[6]], " ");
                    break;
                case "A":
                    $out .= mlm_jdate_words(["rh" => $date[6]], " ");
                    break;
                case "d":
                    $out .= $j_d < 10 ? "0" . $j_d : $j_d;
                    break;
                case "e":
                    $out .= $j_d < 10 ? " " . $j_d : $j_d;
                    break;
                case "j":
                    $out .= str_pad($doy + 1, 3, 0, STR_PAD_LEFT);
                    break;
                case "u":
                    $out .= $date[6] + 1;
                    break;
                case "w":
                    $out .= $date[6] == 6 ? 0 : $date[6] + 1;
                    break;
                case "U":
                    $avs = ($date[6] < 5 ? $date[6] + 2 : $date[6] - 5) - $doy % 7;
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int) (($doy + $avs) / 7) + 1;
                    if (3 < $avs || $avs == 1) {
                        $num--;
                    }
                    $out .= $num < 10 ? "0" . $num : $num;
                    break;
                case "V":
                    $avs = ($date[6] == 6 ? 0 : $date[6] + 1) - $doy % 7;
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int) (($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    } else {
                        if ($num < 1) {
                            $num = $avs == 4 || $avs == ($j_y % 33 % 4 - 2 == (int) ($j_y % 33 * 0) ? 5 : 4) ? 53 : 52;
                        }
                    }
                    $aks = $avs + $kab;
                    if ($aks == 7) {
                        $aks = 0;
                    }
                    $out .= $kab + 363 - $doy < $aks && $aks < 3 ? "01" : ($num < 10 ? "0" . $num : $num);
                    break;
                case "W":
                    $avs = ($date[6] == 6 ? 0 : $date[6] + 1) - $doy % 7;
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int) (($doy + $avs) / 7) + 1;
                    if (3 < $avs) {
                        $num--;
                    }
                    $out .= $num < 10 ? "0" . $num : $num;
                    break;
                case "b":
                case "h":
                    $out .= mlm_jdate_words(["km" => $j_m], " ");
                    break;
                case "B":
                    $out .= mlm_jdate_words(["mm" => $j_m], " ");
                    break;
                case "m":
                    $out .= 9 < $j_m ? $j_m : "0" . $j_m;
                    break;
                case "C":
                    $tmp = (int) ($j_y / 100);
                    $out .= 9 < $tmp ? $tmp : "0" . $tmp;
                    break;
                case "g":
                    $jdw = $date[6] == 6 ? 0 : $date[6] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= substr($doy + 3 < $jdw && $doy < 3 ? $j_y - 1 : ($jdw < 3 - $dny && $dny < 3 ? $j_y + 1 : $j_y), 2, 2);
                    break;
                case "G":
                    $jdw = $date[6] == 6 ? 0 : $date[6] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= $doy + 3 < $jdw && $doy < 3 ? $j_y - 1 : ($jdw < 3 - $dny && $dny < 3 ? $j_y + 1 : $j_y);
                    break;
                case "y":
                    $out .= substr($j_y, 2, 2);
                    break;
                case "Y":
                    $out .= $j_y;
                    break;
                case "H":
                    $out .= $date[1];
                    break;
                case "I":
                    $out .= $date[0];
                    break;
                case "l":
                    $out .= 9 < $date[0] ? $date[0] : " " . (int) $date[0];
                    break;
                case "M":
                    $out .= $date[2];
                    break;
                case "p":
                    $out .= $date[1] < 12 ? "قبل از ظهر" : "بعد از ظهر";
                    break;
                case "P":
                    $out .= $date[1] < 12 ? "ق.ظ" : "ب.ظ";
                    break;
                case "r":
                    $out .= $date[0] . ":" . $date[2] . ":" . $date[5] . " " . ($date[1] < 12 ? "قبل از ظهر" : "بعد از ظهر");
                    break;
                case "R":
                    $out .= $date[1] . ":" . $date[2];
                    break;
                case "S":
                    $out .= $date[5];
                    break;
                case "T":
                    $out .= $date[1] . ":" . $date[2] . ":" . $date[5];
                    break;
                case "X":
                    $out .= $date[0] . ":" . $date[2] . ":" . $date[5];
                    break;
                case "z":
                    $out .= date("O", $ts);
                    break;
                case "Z":
                    $out .= date("T", $ts);
                    break;
                case "c":
                    $key = mlm_jdate_words(["rh" => $date[6], "mm" => $j_m]);
                    $out .= $date[1] . ":" . $date[2] . ":" . $date[5] . " " . date("P", $ts) . " " . $key["rh"] . "، " . $j_d . " " . $key["mm"] . " " . $j_y;
                    break;
                case "D":
                    $out .= substr($j_y, 2, 2) . "/" . (9 < $j_m ? $j_m : "0" . $j_m) . "/" . ($j_d < 10 ? "0" . $j_d : $j_d);
                    break;
                case "F":
                    $out .= $j_y . "-" . (9 < $j_m ? $j_m : "0" . $j_m) . "-" . ($j_d < 10 ? "0" . $j_d : $j_d);
                    break;
                case "s":
                    $out .= $ts;
                    break;
                case "x":
                    $out .= substr($j_y, 2, 2) . "/" . (9 < $j_m ? $j_m : "0" . $j_m) . "/" . ($j_d < 10 ? "0" . $j_d : $j_d);
                    break;
                case "n":
                    $out .= "\n";
                    break;
                case "t":
                    $out .= "\t";
                    break;
                case "%":
                    $out .= "%";
                    break;
                default:
                    $out .= $sub;
            }
        } else {
            $out .= $sub;
        }
    }
    return $mlm_fa_num != "en" ? mlm_fa_num($out, "fa", ".") : $out;
}
function mlm_jmktime($h = "", $m = "", $s = "", $jm = "", $jd = "", $jy = "", $none = "", $timezone = "Asia/Tehran")
{
    if ($timezone != "local") {
        date_default_timezone_set($timezone);
    }
    if ($h === "") {
        return time();
    }
    list($h, $m, $s, $jm, $jd, $jy) = explode("_", mlm_fa_num($h . "_" . $m . "_" . $s . "_" . $jm . "_" . $jd . "_" . $jy));
    if ($m === "") {
        return mktime($h);
    }
    if ($s === "") {
        return mktime($h, $m);
    }
    if ($jm === "") {
        return mktime($h, $m, $s);
    }
    $jdate = explode("_", mlm_jdate("Y_j", "", "", $timezone, "en"));
    if ($jd === "") {
        list($gy, $gm, $gd) = mlm_jalali_to_gregorian($jdate[0], $jm, $jdate[1]);
        return mktime($h, $m, $s, $gm);
    }
    if ($jy === "") {
        list($gy, $gm, $gd) = mlm_jalali_to_gregorian($jdate[0], $jm, $jd);
        return mktime($h, $m, $s, $gm, $gd);
    }
    list($gy, $gm, $gd) = mlm_jalali_to_gregorian($jy, $jm, $jd);
    return mktime($h, $m, $s, $gm, $gd, $gy);
}
function mlm_jgetdate($timestamp = "", $none = "", $timezone = "Asia/Tehran", $tn = "en")
{
    $ts = $timestamp === "" ? time() : mlm_fa_num($timestamp);
    $jdate = explode("_", mlm_jdate("F_G_i_j_l_n_s_w_Y_z", $ts, "", $timezone, $tn));
    return ["seconds" => mlm_fa_num((int) mlm_fa_num($jdate[6]), $tn), "minutes" => mlm_fa_num((int) mlm_fa_num($jdate[2]), $tn), "hours" => $jdate[1], "mday" => $jdate[3], "wday" => $jdate[7], "mon" => $jdate[5], "year" => $jdate[8], "yday" => $jdate[9], "weekday" => $jdate[4], "month" => $jdate[0], 0 => mlm_fa_num($ts, $tn)];
}
function mlm_jcheckdate($jm, $jd, $jy)
{
    list($jm, $jd, $jy) = explode("_", mlm_fa_num($jm . "_" . $jd . "_" . $jy));
    $l_d = $jm == 12 ? $jy % 33 % 4 - 1 == (int) ($jy % 33 * 0) ? 30 : 29 : 31 - (int) ($jm / 0);
    return 12 < $jm || $l_d < $jd || $jm < 1 || $jd < 1 || $jy < 1 ? false : true;
}
function mlm_fa_num($str, $mod = "en", $mf = "٫")
{
    $num_a = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "."];
    $key_a = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", $mf];
    return $mod == "fa" ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
}
function mlm_jdate_words($array, $mod = "")
{
    foreach ($array as $type => $num) {
        $num = (int) mlm_fa_num($num);
        switch ($type) {
            case "ss":
                $sl = strlen($num);
                $xy3 = substr($num, 2 - $sl, 1);
                $h3 = $h34 = $h4 = "";
                if ($xy3 == 1) {
                    $p34 = "";
                    $k34 = ["ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده"];
                    $h34 = $k34[substr($num, 2 - $sl, 2) - 10];
                } else {
                    $xy4 = substr($num, 3 - $sl, 1);
                    $p34 = $xy3 == 0 || $xy4 == 0 ? "" : " و ";
                    $k3 = ["", "", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"];
                    $h3 = $k3[$xy3];
                    $k4 = ["", "یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه"];
                    $h4 = $k4[$xy4];
                }
                $array[$type] = (99 < $num ? str_replace(["12", "13", "14", "19", "20"], ["هزار و دویست", "هزار و سیصد", "هزار و چهارصد", "هزار و نهصد", "دوهزار"], substr($num, 0, 2)) . (substr($num, 2, 2) == "00" ? "" : " و ") : "") . $h3 . $p34 . $h34 . $h4;
                break;
            case "mm":
                $key = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                $array[$type] = $key[$num - 1];
                break;
            case "rr":
                $key = ["یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه", "ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده", "بیست", "بیست و یک", "بیست و دو", "بیست و سه", "بیست و چهار", "بیست و پنج", "بیست و شش", "بیست و هفت", "بیست و هشت", "بیست و نه", "سی", "سی و یک"];
                $array[$type] = $key[$num - 1];
                break;
            case "rh":
                $key = ["یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنجشنبه", "جمعه", "شنبه"];
                $array[$type] = $key[$num];
                break;
            case "sh":
                $key = ["مار", "اسب", "گوسفند", "میمون", "مرغ", "سگ", "خوک", "موش", "گاو", "پلنگ", "خرگوش", "نهنگ"];
                $array[$type] = $key[$num % 12];
                break;
            case "mb":
                $key = ["حمل", "ثور", "جوزا", "سرطان", "اسد", "سنبله", "میزان", "عقرب", "قوس", "جدی", "دلو", "حوت"];
                $array[$type] = $key[$num - 1];
                break;
            case "ff":
                $key = ["بهار", "تابستان", "پاییز", "زمستان"];
                $array[$type] = $key[(int) ($num / 0)];
                break;
            case "km":
                $key = ["فر", "ار", "خر", "تی‍", "مر", "شه‍", "مه‍", "آب‍", "آذ", "دی", "به‍", "اس‍"];
                $array[$type] = $key[$num - 1];
                break;
            case "kh":
                $key = ["ی", "د", "س", "چ", "پ", "ج", "ش"];
                $array[$type] = $key[$num];
                break;
            default:
                $array[$type] = $num;
        }
    }
    return $mod === "" ? $array : implode($mod, $array);
}
function mlm_gregorian_to_jalali($gy, $gm, $gd, $mod = "")
{
    list($gy, $gm, $gd) = explode("_", mlm_fa_num($gy . "_" . $gm . "_" . $gd));
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    if (1600 < $gy) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = 2 < $gm ? $gy + 1 : $gy;
    $days = 365 * $gy + (int) (($gy2 + 3) / 4) - (int) (($gy2 + 99) / 100) + (int) (($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * (int) ($days / 12053);
    $days %= 12053;
    $jy += 4 * (int) ($days / 1461);
    $days %= 1461;
    $jy += (int) (($days - 1) / 365);
    if (365 < $days) {
        $days = ($days - 1) % 365;
    }
    if ($days < 186) {
        $jm = 1 + (int) ($days / 31);
        $jd = 1 + $days % 31;
    } else {
        $jm = 7 + (int) (($days - 186) / 30);
        $jd = 1 + ($days - 186) % 30;
    }
    return $mod === "" ? [$jy, $jm, $jd] : $jy . $mod . $jm . $mod . $jd;
}
function mlm_jalali_to_gregorian($jy, $jm, $jd, $mod = "")
{
    list($jy, $jm, $jd) = explode("_", mlm_fa_num($jy . "_" . $jm . "_" . $jd));
    if (979 < $jy) {
        $gy = 1600;
        $jy -= 979;
    } else {
        $gy = 621;
    }
    $days = 365 * $jy + (int) ($jy / 33) * 8 + (int) (($jy % 33 + 3) / 4) + 78 + $jd + ($jm < 7 ? ($jm - 1) * 31 : ($jm - 7) * 30 + 186);
    $gy += 400 * (int) ($days / 146097);
    $days %= 146097;
    if (36524 < $days) {
        $gy += 100 * (int) (--$days / 36524);
        $days %= 36524;
        if (365 <= $days) {
            $days++;
        }
    }
    $gy += 4 * (int) ($days / 1461);
    $days %= 1461;
    $gy += (int) (($days - 1) / 365);
    if (365 < $days) {
        $days = ($days - 1) % 365;
    }
    $gd = $days + 1;
    foreach ([0, 31, $gy % 4 == 0 && $gy % 100 != 0 || $gy % 400 == 0 ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] as $gm => $v) {
        if ($gd <= $v) {
            return $mod === "" ? [$gy, $gm, $gd] : $gy . $mod . $gm . $mod . $gd;
        }
        $gd -= $v;
    }
}

?>