<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class DoubleRodConductor extends SimplePartCalculator
{
    const NAME = 'Double rod conductor with equal lengths';

    const VALIDATOR_RULE = [
        'height' => 'required|numeric|min:1|max:150',
        'distance' => 'required|numeric|min:1'
    ];

    public function makeCalc()
    {
        if ($this->params['zone'] == 'a') {

            $this->results['rx'] = (1.1 - 0.002 * $this->params['height']) * (($this->params['height'] - $this->params['building']['height']) / 0.85);

            if ($this->params['distance'] <= $this->params['height']) {
                $this->results['hc'] = 0.85 * $this->params['height'];
                $this->results['rc'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'];
                $this->results['rcx'] = (1.1 - 0.002 * $this->params['height']) * (($this->params['height'] - $this->params['building']['height']) / 0.85);

            } elseif ($this->params['distance'] <= 2 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $this->params['height'] - (0.17 + 3 * pow(10, -4) * $this->params['height']) * ($this->params['distance'] - $this->params['height']);
                $this->results['rc'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'];
                $this->results['rcx'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'] * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];

            } elseif ($this->params['distance'] <= 4 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $this->params['height'] - (0.17 + 3 * pow(10, -4) * $this->params['height']) * ($this->params['distance'] - $this->params['height']);
                $this->results['rc'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'] * (1 - 0.2 * ($this->params['distance'] - 2 * $this->params['height']) / $this->params['height']);
                $this->results['rcx'] = $this->results['rc'] * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];

            } else {
                $this->errors['distance'] = 'The distance between the cables greatly exceeds their heights.';
                return false;
            }

        } else {

            $this->results['rx'] = 1.5 * ($this->params['height'] - $this->params['building']['height'] / 0.92);

            if ($this->params['distance'] <= $this->params['height']) {
                $this->results['hc'] = 0.85 * $this->params['height'];
                $this->results['rc'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'];
                $this->results['rcx'] = (1.1 - 0.002 * $this->params['height']) * (($this->params['height'] - $this->params['building']['height']) / 0.85);

            } elseif ($this->params['distance'] < 6 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $this->params['height'] - 0.14 * ($this->params['distance'] - $this->params['height']);
                $this->results['rc'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'];
                $this->results['rcx'] = (1.1 - 0.002 * $this->params['height']) * $this->params['height'] * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];
            } else {
                $this->errors['distance'] = 'The distance between the cables greatly exceeds their heights.';
                return false;
            }
        }

        $this->results['conclusion'] = $this->getConclusion($this->results, $this->params['distance'], $this->params['building']);

    }

    private function getConclusion($conductor, $distance, $building)
    {
        $conclusion = null;

        if ($building['type'] == 'rectangle') {
            $x1 = sqrt(pow(($building['length'] - $distance), 2) + pow($building['width'], 2)) / 2;
            $x2 = $building['width'] / 2;

            $conductor['rx'] > $x1
            && $conductor['rcx'] > $x2
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';

        } else {
            $x = $building['diameter'] / 2;

            $conductor['rcx'] > $x
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
            'height' => 'Height of lightning conductors hоп, m:',
            'distance' => 'Distance between cables L, m:'
        ];
    }
}