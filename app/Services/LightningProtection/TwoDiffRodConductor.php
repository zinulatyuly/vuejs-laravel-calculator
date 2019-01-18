<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class TwoDiffRodConductor extends SimplePartCalculator
{
    const NAME = 'Two rod conductors with different lengths';

    const VALIDATOR_RULE = [
        'height1' => 'required|numeric|min:1|max:150',
        'height2' => 'required|numeric|min:1|max:150',
        'distance' => 'required|numeric|min:1'
    ];

    public function makeCalc()
    {
        if ($this->params['zone'] == 'a') {

            $hmin = min($this->params['height1'], $this->params['height2']);

            $this->results['rx'] = (1.1 - 0.002 * $hmin) * (($hmin - $this->params['building']['height']) / 0.85);

            if ($this->params['distance'] <= 4 * $hmin) {
                $this->results['hc'] = (0.85 * $this->params['height1'] + 0.85 * $this->params['height2']) / 2;
                $this->results['rc'] = ((1.1 - 0.002 * $this->params['height1']) * $this->params['height1'] + (1.1 - 0.002 * $this->params['height2']) * $this->params['height2']) / 2;
                $this->results['rcx'] = $this->results['rc'] * ($this->results['hc'] + $this->params['building']['height']) / $this->results['hc'];
            } else {
                $this->errors['distance'] = 'The distance between the cables greatly exceeds their heights.';
                return false;
            }
            
        } else {

            $hmin = min($this->params['height1'], $this->params['height2']);

            $this->results['rx'] = 1.5 * ($hmin - $this->params['building']['height'] / 0.92);

            if ($this->params['distance'] <= 6 * $hmin) {
                $this->results['hc'] = (0.92 * $this->params['height1'] + 0.92 * $this->params['height1']) / 2;
                $this->results['rc'] = (1.5 * $this->params['height1'] + 1.5 * $this->params['height2']) / 2;
                $this->results['rcx'] = $this->results['rc'] * ($this->results['hc'] + $this->params['building']['height']) / $this->results['hc'];
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
            if ($building['length'] < $building['width']) {
                $buff = $building['length'];
                $building['length'] = $building['width'];
                $building['width'] = $buff;
            }
            
            $x1 = sqrt(pow(($building['length'] - $distance),2) + pow($building['width'], 2))/2;
            $x2 = $building['width'] / 2;

            $conductor['rx'] > $x1 && $conductor['rcx'] > $x2 
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';
            
        } elseif ($building['type'] == 'circle') {
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
            'height1' => 'Height of lightning conductor hоп1, m:',
            'height2' => 'Height of lightning conductor hоп2, m:',
            'distance' => 'Distance between cables L, m:'
        ];
    }
}