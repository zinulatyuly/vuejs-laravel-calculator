<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class TwoDiffCableConductor extends SimplePartCalculator
{
    const NAME = 'Two cable conductors with different lengths';

    const VALIDATOR_RULE = [
        'height1' => 'required|numeric|min:1|max:150',
        'height2' => 'required|numeric|min:1|max:150',
        'span' => 'required|numeric|min:1|max:150',
        'distance' => 'required|numeric|min:1'
    ];

    public function makeCalc()
    {
        foreach ($this->params as $key => $value) {
            ${"$key"} = $value;
        }

        if ($zone == 'a') {
            for ($i = 1; $i <= 2; $i++) {
                $span < 120
                    ? ${"ht$i"} = ${"height$i"} - 2
                    : ${"ht$i"} = ${"height$i"} - 3;

                ${"h0$i"} = 0.85 * ${"ht$i"};
                ${"r0$i"} = (1.35 - 0.0025 * ${"ht$i"}) * ${"ht$i"};
                ${"rx$i"} = (1.35 - 0.0025 * ${"ht$i"}) * (${"ht$i"} - $building['height'] / 0.85);

                if ($distance <= ${"height$i"}) {
                    ${"hc$i"} = 0.85 * ${"ht$i"};
                } elseif ($distance <= 2 * ${"height$i"}) {
                    ${"hc$i"} = 0.85 * ${"ht$i"} - (0.14 + 5 * pow(10, -4) * ${"height$i"}) * ($distance - ${"height$i"});
                } elseif ($distance <= 4 * ${"height$i"}) {
                    ${"hc$i"} = 0.85 * ${"ht$i"} - (0.14 + 5 * pow(10, -4) * ${"height$i"}) * ($distance - ${"height$i"});
                } else {
                    $this->errors['distance'] = 'Distance between cables are greatly exceeds their heights.';
                    return false;
                }
            }

            $this->results['rc'] = ($r01 + $r02) / 2;
            $this->results['hc'] = ($hc1 + $hc2) / 2;
            $this->results['rcx'] = ($rx1 + $rx2) / 2;

        } else {
            for ($i = 1; $i <= 2; $i++) {
                $span < 120
                    ? ${"ht$i"} = ${"height$i"} - 2
                    : ${"ht$i"} = ${"height$i"} - 3;

                ${"h0$i"} = 0.92 * ${"ht$i"};
                ${"r0$i"} = 1.7 * ${"ht$i"};
                ${"rx$i"} = 1.7 * (${"ht$i"} - $building['height'] / 0.92);

                if ($distance <= ${"height$i"}) {
                    ${"hc$i"} = 0.85 * ${"ht$i"};
                } elseif ($distance <= 6 * ${"height$i"}) {
                    ${"hc$i"} = 0.85 * ${"ht$i"} - 0.12 * ($distance - ${"height$i"});
                } else {
                    $this->errors['distance'] = 'Distance between cables are greatly exceeds their heights.';
                    return false;
                }
            }

            $this->results['rc'] = ($r01 + $r02) / 2;
            $this->results['hc'] = ($hc1 + $hc2) / 2;
            $this->results['rcx'] = ($rx1 + $rx2) / 2;
        }

        $this->results['conclusion'] = $this->getConclusion($this->results, $this->params['distance'], $this->params['span'], $this->params['building']);

    }

    private function getConclusion($conductor, $distance, $span, $building)
    {
        $conclusion = null;

        if ($building['length'] < $building['width']) {
            $buff = $building['length'];
            $building['length'] = $building['width'];
            $building['width'] = $buff;
        }

        if ($building['type'] == 'rectangle') {
            $conductor['rcx'] > ($distance / 2)
            && $conductor['rcx'] > sqrt(pow(($building['width'] - $span), 2) + pow(($building['length'] - $distance), 2)) / 2
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';
        } else {
            $conductor['rcx'] > ($distance / 2)
            && $conductor['rcx'] > sqrt(pow(($building['diameter'] - $span), 2) + pow(($distance), 2)) / 2
            && $conductor['rcx'] > ($building['diameter'] - $distance) / 2
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';
        }

        return $conclusion;

    }

    public function getResultFields()
    {
        return [
            'hc' => 'hc:',
            'rc' => 'rc:',
            'rcx' => 'rcx:',
            'conclusion' => 'Conclusion:'
        ];
    }

    public function getFields()
    {
        return [
            'height1' => 'Height of lightning conductor hоп1, m:',
            'height2' => 'Height of lightning conductor hоп2, m:',
            'span' => 'Span (cable) length a, m:',
            'distance' => 'Distance between cables L, m:'
        ];
    }
}