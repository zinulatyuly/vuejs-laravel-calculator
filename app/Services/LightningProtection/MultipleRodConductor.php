<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class MultipleRodConductor extends SimplePartCalculator
{
    const NAME = 'Multiple rod conductor';

    const VALIDATOR_RULE = [
        'quantity' => 'required|numeric|min:3|max:100'
    ];

    public function makeCalc()
    {
        foreach ($this->params as $key => $value) {
            ${"$key"} = $value;
        }

        $this->results['conclusion'] = 'The protection criterion is met.';
        $step = 0;

        // 1 ... n-1
        for ($i = 1; $i < intdiv(count($this->params), 2) - 1; $i++) {
            $step = $i + 1;
            if ($zone == 'a') {
                if (${"distance$i"} <= 4 * min(${"height$i"}, ${"height$step"})) {
                    $this->results["hc$i"] = (0.85 * ${"height$i"} + 0.85 * ${"height$step"}) / 2;
                    $this->results["rc$i"] = ((1.1 - 0.002 * ${"height$i"}) * ${"height$i"} + (1.1 - 0.002 * ${"height$step"}) * ${"height$step"}) / 2;
                    $this->results["rcx$i"] = $this->results["rc$i"] * ($this->results["hc$i"] + $building['height']) / $this->results["hc$i"];
                } else {
                    $this->errors['distance'] = "Distance between lightning conductors $i and $step greatly exceeds their heights.";
                    return false;
                }
            }
            if ($zone == 'b') {
                if (${"distance$i"} <= 6 * min(${"height$i"}, ${"height$step"})) {
                    $this->results["hc$i"] = (0.92 * ${"height$i"} + 0.92 * ${"height$i"}) / 2;
                    $this->results["rc$i"] = (1.5 * ${"height$i"} + 1.5 * ${"height$step"}) / 2;
                    $this->results["rcx$i"] = $this->results["rc$i"] * ($this->results["hc$i"] + $building['height']) / $this->results["hc$i"];
                } else {
                    $this->errors['distance'] = "Distance between lightning conductors $i and $step greatly exceeds their heights.";
                    return false;
                }
            }

            if ($this->results["rcx$i"] < 0) $this->results['conclusion'] = 'The protection criterion isn\'t met.';
        }

        // n -> 1
        if ($zone == 'a') {
            if (${"distance$step"} <= 4 * min(${"height$step"}, ${"height1"})) {
                $this->results["hc$step"] = (0.85 * ${"height$step"} + 0.85 * ${"height1"}) / 2;
                $this->results["rc$step"] = ((1.1 - 0.002 * ${"height$step"}) * ${"height$step"} + (1.1 - 0.002 * ${"height1"}) * ${"height1"}) / 2;
                $this->results["rcx$step"] = $this->results["rc$step"] * ($this->results["hc$step"] + $building['height']) / $this->results["hc$step"];
            } else {
                $this->errors['distance'] = "Distance between lightning conductors $step and 1 greatly exceeds their heights.";
                return false;
            }
        }
        if ($zone == 'b') {
            if (${"distance$step"} <= 6 * min(${"height$step"}, ${"height1"})) {
                $this->results["hc$step"] = (0.92 * ${"height$step"} + 0.92 * ${"height$step"}) / 2;
                $this->results["rc$step"] = (1.5 * ${"height$step"} + 1.5 * ${"height1"}) / 2;
                $this->results["rcx$step"] = $this->results["rc$step"] * ($this->results["hc$step"] + $building['height']) / $this->results["hc$step"];
            } else {
                $this->errors['distance'] = "Distance between lightning conductors $step and 1 greatly exceeds their heights.";
                return false;
            }
        }

        if ($this->results["rcx$step"] < 0) $this->results['conclusion'] = 'The protection criterion isn\'t met.';

    }

    public
    function getResultFields()
    {
        $arr = [];

        for ($i = 1; $i < 100; $i++) {
            $arr["hc$i"] = "hc$i:";
            $arr["rc$i"] = "rc$i:";
            $arr["rcx$i"] = "rcx$i:";
        }

        return array_merge($arr, ['conclusion' => 'Conclusion:']);
    }

    public
    function getFields()
    {
        $arr = [];

        for ($i = 1; $i < 100; $i++) {
            $arr["height$i"] = "Height of lightning conductor $i h$i, m:";
            $arr["distance$i"] = "Distance between lightning conductors L, m:";
        }

        return array_merge(['quantity' => 'Quantity of lightning conductors k, pcs:'], $arr);
    }
}