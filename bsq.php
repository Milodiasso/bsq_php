<?php
class Algo_bsq
{
    private $max_suite;
    private $map = [];
    private $max_countX;
    private $max_countY;
    private $nbr_line;
    private $nbr;
    private $sus_map;
    private array $coordonnees;

    function __construct($file)
    {
        $fichier = file($file);
        $nbr_line = intval($fichier[0]);
        $nbr = intval(strlen($fichier[1]));
        $max_countX = [];
        $max_countY = [];
        $tmp_countY = [];

        for ($a = 0; $a < $nbr; $a++) {
            $max_countY[$a] = 0;
            $tmp_countY[$a] = 1;
        }

        for ($i = 1; $i <= $nbr_line; $i++) {
            $this->map[$i - 1] = [];
            $max_countX[$i - 1] = 0;
            $tmp_countX[$i] = 0;
            for ($j = 0; $j <= ($nbr - 2); $j++) {
                $this->map[$i - 1][] = $fichier[$i][$j];
                if ($fichier[$i][$j] == '.') {
                    $tmp_countX[$i]++;
                    if ($tmp_countX[$i] > $max_countX[$i - 1]) {
                        $max_countX[$i - 1] = $tmp_countX[$i];
                    }

                    if ($i + 1 <= $nbr_line) {
                        if ($fichier[$i + 1][$j] == '.') {
                            $tmp_countY[$j]++;
                            if ($tmp_countY[$j] > $max_countY[$j]) {
                                $max_countY[$j] = $tmp_countY[$j];
                            }
                        } else {
                            $tmp_countY[$j] = 0;
                        }
                    }
                }
                if ($fichier[$i][$j] == 'o') {

                    $tmp_countX[$i] = 0;
                }
            }
        }
        unset($max_countY[count($max_countY) - 1]);
        $this->max_suite = min([max($max_countX), max($max_countY)]);
        $this->max_countX = $max_countX;
        $this->max_countY = $max_countY;
        $this->nbr_line = $nbr_line;
        $this->nbr = $nbr;

        print_r($max_countX);
        print_r($max_countY);
    }


    function max_suite(array $map)
    {
        $suite = $this->max_suite;
        // echo $suite . PHP_EOL;
        if ($suite == 0) return [0, 0];
        for ($i = $suite; $i > 0; $i--) {
            $flag = 0;
            foreach ($map as $key => $value) {
                if ($value >= $i) $flag++;
                if ($value < $i) $flag = 0;
                if ($flag == $i) {

                    // print_r([$flag, ($key + 1 ) -$flag]);
                    return [$flag, ($key + 1) - $flag];
                }
            }
        }
    }


    function init_max_suite()
    {
        $sus_x = $this->max_suite($this->max_countX);
        $sus_y = $this->max_suite($this->max_countY);
        $max = min([$sus_x[0], $sus_y[0]]);
        // print_r($sus_x);
        $this->sus_map['x'] = $sus_x[1];
        $this->sus_map['y'] = $sus_y[1];
        $this->max_suite = $max;
        // print_r($sus_x);
        // print_r($sus_y);
    }

    function checker($j, $i, $carre)
    {
        $coordonnees = [];
        $my_map = $this->map;

        for ($a = 0; $a < $carre; $a++) {
            for ($b = 0; $b < $carre; $b++) {
                if ($my_map[$j + $a][$i - $b] == '.') {
                    $coordonnees[] = [$j + $a, $i - $b];
                    // print_r( [$j + $a, $i - $b]);

                }
                if ($my_map[$j + $a][$i - $b] == 'o') {
                    $coordonnees = [];
                    return false;
                }
            }
        }
        $this->coordonnees = $coordonnees;
        return true;
    }

    function check_map()
    {
        $carre = $this->max_suite;
        $my_map = $this->map;
        echo $carre . PHP_EOL;

        if ($carre == 0) return false;

        while ($carre > 0) {
            // print_r($my_map);
            for ($j = 0; $j <= $this->nbr_line - 1; $j++) {
                if ($this->nbr_line - $j  >= $carre) {
                    // echo $this->sus_map['x'] . '___' .  $this->nbr . PHP_EOL;
                    for ($i = 0; $i <= $this->nbr - 2; $i++) {
                        // echo $j . ' ---  ' . $i . PHP_EOL;
                        // echo $tmp_count . PHP_EOL;
                        if ($my_map[$j][$i] == '.') {
                            // echo $j . ' ' .  $i . PHP_EOL;
                            // echo "Ligne n° " . $j . " Col n° " . $i ." --- Taille : " . $carre . PHP_EOL;
                            if ($this->checker($j, $i, $carre)) {
                                return true;
                            }
                        }
                    }
                }
            }
            $carre--;
        }
    }

    function placement()
    {
        $new_map = "";
        if ($this->max_suite == 0) {
            foreach ($this->map as $value) {
                $new_map .= implode('', $value) . "\n";
            }
        } else {
            // $fp = fopen('data.txt', "w+");
            // print_r($this->coordonnees);
            foreach ($this->coordonnees as $value) {
                $this->map[$value[0]][$value[1]] = 'x';
            }
            foreach ($this->map as $value) {
                $new_map .= implode('', $value) . "\n";
            }
        }
        echo $new_map;
        // echo memory_get_peak_usage(true);
        // fwrite($fp, $new_map . "\n");
        // fclose($fp);
    }
}

$test = new Algo_bsq($argv[1]);
$test->init_max_suite();
$test->check_map();
$test->placement();
