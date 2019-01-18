<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class RectangleBuilding extends SimplePartCalculator
{
    const NAME = 'Rectangle';

    const VALIDATOR_RULE = [
        'length' => 'required|numeric|min:1',
        'width' => 'required|numeric|min:1',
        'height' => 'required|numeric|min:1',
        'density' => 'required|numeric|min:1',
    ];

    public function makeCalc()
    {
        $this->results['levin'] = (
                ($this->params['length'] + 6 * $this->params['height']) *
                ($this->params['width'] + 6 * $this->params['height']) -
                7.7 * pow($this->params['height'],2))
            * $this->params['density'] * pow(10,-6);
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
            'length' => 'Length A, m:',
            'width' => 'Width B, m:',
            'height' => 'Height hx, m:',
            'density' => 'Specific density of lightning strikes in the ground N, 1/(km2×year):'
        ];
    }
}