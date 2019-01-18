<?php

namespace App\Services\LightningProtection;

use App\Services\SimplePartCalculator;

class DoubleCableConductor extends SimplePartCalculator
{
    const NAME = 'Double cable conductor with equal lengths';

    const VALIDATOR_RULE = [
        'height' => 'required|numeric|min:1|max:150',
        'span' => 'required|numeric|min:1|max:150',
        'distance' => 'required|numeric|min:1'
    ];

    public function makeCalc()
    {
        $this->params['span'] < 120
            ? $ht = $this->params['height'] - 2
            : $ht = $this->params['height'] - 3;
        
        if ($this->params['zone'] == 'a') {
            if ($this->params['distance'] <= $this->params['height']) {
                $this->results['hc'] = 0.85 * $ht;
                $this->results['rc'] = (1.35 - 0.0025 * $ht) * $ht;
                $this->results['rcx'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85);
            } elseif ($this->params['distance'] <= 2 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $ht - (0.14 + 5 * pow(10,-4) * $this->params['height']) * ($this->params['distance'] - $this->params['height']);
                $this->results['r1x'] = $this->params['distance'] * (0.85 * $ht - $this->params['building']['height']) / (2 * (0.85 * $ht - $this->results['hc']));
                $this->results['rc'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85);
                $this->results['rcx'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85) * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];
            } elseif ($this->params['distance'] <= 4 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $ht - (0.14 + 5 * pow(10,-4) * $this->params['height']) * ($this->params['distance'] - $this->params['height']);
                $this->results['r1x'] = $this->params['distance'] * (0.85 * $ht - $this->params['building']['height']) / (2 * (0.85 * $ht - $this->results['hc']));
                $this->results['rc'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85) * (1 - 0.2 * ($this->params['distance'] - 2 * $this->params['height']) / $this->params['height']);
                $this->results['rcx'] = $this->results['rc'] * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];
            } else {
                $this->errors['distance'] = 'The distance between the cables greatly exceeds their heights.';
                return false;
            }
            
        } else {
            if ($this->params['distance'] <= $this->params['height']) {
                $this->results['hc'] = 0.85 * $ht;
                $this->results['rc'] = (1.35 - 0.0025 * $ht) * $ht;
                $this->results['rcx'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85);
            } elseif ($this->params['distance'] <= 6 * $this->params['height']) {
                $this->results['hc'] = 0.85 * $ht - 0.12 * ($this->params['distance'] - $this->params['height']);
                $this->results['r1x'] = $this->params['distance'] * (0.85 * $ht - $this->params['building']['height']) / (2 * (0.85 * $ht - $this->results['hc']));
                $this->results['rc'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85);
                $this->results['rcx'] = (1.35 - 0.0025 * $ht) * ($ht - $this->params['building']['height'] / 0.85) * ($this->results['hc'] - $this->params['building']['height']) / $this->results['hc'];
            } else {
                $this->errors['distance'] = 'The distance between the cables greatly exceeds their heights.';
                return false;
            }
        }

        $this->results['conclusion'] = $this->getConclusion($this->results, $this->params['distance'], $this->params['span'], $this->params['building']);

    }

    private function getConclusion($conductor, $distance, $span, $building)
    {
        $conclusion = null;

        if ($building['type'] == 'rectangle') {
            $conductor['rcx'] > ($distance / 2)
            && $conductor['rcx'] > sqrt( pow(($building['width'] - $span), 2) + pow(($building['length'] - $distance), 2) ) / 2
                ? $conclusion = 'The protection criterion is met.'
                : $conclusion = 'The protection criterion isn\'t met.';
        } else {
            $conductor['rcx'] > ($distance / 2)
            && $conductor['rcx'] > sqrt( pow(($building['diameter'] - $span), 2) + pow(($distance), 2) ) / 2
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
            'r1x' => 'r\'x:',
            'rcx' => 'rcx:',
            'conclusion' => 'Conclusion:'
        ];
    }

    public function getFields()
    {
        return [
            'height' => 'Height of lightning conductors hоп, m:',
            'span' => 'Span (cable) length a, m:',
            'distance' => 'Distance between cables L, m:'
        ];
    }
}