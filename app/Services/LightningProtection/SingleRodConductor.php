<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class SingleRodConductor extends SimplePartCalculator
{
    const NAME = 'Single rod lightning conductor';

    const VALIDATOR_RULE = [
        'height' => 'required|numeric|min:1|max:150',
    ];

    public function makeCalc()
    {
        if ($this->params['zone'] == 'a') {
            $this->results = [
                'h0' => 0.85 * $this->params['height'],
                'r0' => (1.1 - 0.002 * $this->params['height']) * $this->params['height'],
                'rx' => (1.1 - 0.002 * $this->params['height']) * (($this->params['height'] - $this->params['building']['height']) / 0.85)
            ];
        } elseif ($this->params['zone'] == 'b') {
            $this->results = [
                'h0' => 0.92 * $this->params['height'],
                'r0' => 1.5 * $this->params['height'],
                'rx' => 1.5 * ($this->params['height'] - $this->params['building']['height'] / 0.92)
            ];
        }

        $this->results['conclusion'] = $this->getConclusion($this->results, $this->params['building']);

    }

    private function getConclusion($conductor, $building)
    {
        $conclusion = null;

        $building['type'] === 'rectangle'
            ? $x = sqrt(pow($building['length'], 2) / 4 + pow($building['width'], 2))
            : $x = $building['diameter'] / 2;

        $conductor['rx'] > $x
            ? $conclusion = 'The protection criterion is met.'
            : $conclusion = 'The protection criterion isn\'t met.';

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
            'height' => 'Height hx, m:'
        ];
    }
}