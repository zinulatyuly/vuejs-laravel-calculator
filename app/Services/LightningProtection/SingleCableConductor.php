<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class SingleCableConductor extends SimplePartCalculator
{
    const NAME = 'Single cable conductor';

    const VALIDATOR_RULE = [
        'height' => 'required|numeric|min:1|max:150',
        'span' => 'required|numeric|min:1|max:150',
    ];

    public function makeCalc()
    {
        $this->params['span'] < 120
            ? $ht = $this->params['height'] - 2
            : $ht = $this->params['height'] - 3;
        
        if ($this->params['zone'] == 'a') {
            $this->results = [
                'h0' => 0.85 * $ht,
                'r0' => (1.35 - 0.0025 * $ht) * $ht,
                'rx' => (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85)
            ];
            
        } else {
            $this->results = [
                'h0' => 0.92 * $ht,
                'r0' => 1.7 * $ht,
                'rx' => 1.7 * ($ht - $this->params['building']['height'] / 0.92)
            ];
        }

        $this->results['conclusion'] = $this->getConclusion($this->results, $this->params['span'], $this->params['building']);

    }

    private function getConclusion($conductor, $span, $building)
    {
        $conclusion = null;

        if ($building['type'] == 'rectangle') {
            if ($building['length'] < $building['width']) {
                $buff = $building['length'];
                $building['length'] = $building['width'];
                $building['width'] = $buff;
            }
            
            $x1 = sqrt(pow(($building['length'] - $span),2) + pow($building['width'], 2))/2;
            $x2 = $building['width'] / 2;
            $x = max($x1, $x2);

            $conductor['rx'] > $x
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';

        } else {
            $x = $building['diameter'] / 2;

            $conductor['rx'] > $x
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';
        }

        return $conclusion;
    }

    public function getResultFields()
    {
        return [
            'h0' => 'h0:',
            'r0' => 'r0:',
            'rx' => 'rx:',
            'conclusion' => 'Conclusion:'
        ];
    }

    public function getFields()
    {
        return [
            'height' => 'Height hx, m:',
            'span' => 'Span (cable) length a, m:'
        ];
    }
}