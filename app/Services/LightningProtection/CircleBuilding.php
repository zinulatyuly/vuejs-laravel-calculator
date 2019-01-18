<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class CircleBuilding extends SimplePartCalculator
{
    const NAME = 'Circle (tube, tower, belfry, etc.)';

    const VALIDATOR_RULE = [
        'diameter' => 'required|numeric|min:1',
        'height' => 'required|numeric|min:1',
        'density' => 'required|numeric|min:1',
    ];

    public function makeCalc()
    {
        $this->results['levin'] = 9 * M_PI * pow($this->params['height'],2) * $this->params['density'] * pow(10, -6);
    }

    public function getResultFields()
    {
        return [
            'levin' => 'Expected quantity of lightning strikes per year N, pcs:'
        ];
    }

    public function getFields()
    {
        return [
            'diameter' => 'Diameter D, m:',
            'height' => 'Height hx, m:',
            'density' => 'Specific density of lightning strikes in the ground N, 1/(km2×year):'
        ];
    }
}