<?php

namespace App\Services;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Storage;

class LightningProtectionCalculator extends SimpleCalculator
{
    protected $report_filename = 'lightning-protection';

    protected $building_name = [];
    protected $conductor_name = [];

    protected $building_params = [];
    protected $building_results = [];

    protected $conductor_params = [];
    protected $conductor_results = [];

    protected $structure_params = [];

    const NAME = 'Lightning Protection';

    const VALIDATOR_RULE = [
        'building' => 'required|array',
        'conductor' => 'required|array',
    ];

    protected $buildings = [
        'rectangle' => 'App\Services\LightningProtection\RectangleBuilding',
        'circle' => 'App\Services\LightningProtection\CircleBuilding',
    ];

    protected $conductors = [
        'single_rod' => 'App\Services\LightningProtection\SingleRodConductor',
        'double_rod' => 'App\Services\LightningProtection\DoubleRodConductor',
        'two_diff_rod' => 'App\Services\LightningProtection\TwoDiffRodConductor',
        'multiple_rod' => 'App\Services\LightningProtection\MultipleRodConductor',
        'single_cable' => 'App\Services\LightningProtection\SingleCableConductor',
        'double_cable' => 'App\Services\LightningProtection\DoubleCableConductor',
        'two_diff_cable' => 'App\Services\LightningProtection\TwoDiffCableConductor',
    ];

    public function getBuildingCalculator($type)
    {
        return $this->buildings[$type];
    }

    public function getConductorCalculator($type)
    {
        return $this->conductors[$type];
    }

    public function makeCalc()
    {

        // Калькулятор здания
        $building_class = $this->getBuildingCalculator($this->params['building']['type']);

        $building_calc = new $building_class;
        $building_results = $building_calc->calc($this->params['building']['request']);

        if (isset($building_results['errors'])) {
            $this->results['conductor']['errors'] = $building_results['errors'];
            return false;
        }

        // Перестановка значений (length > width)
        $this->params['building']['request'] = $this->mixLengthWidth($this->params['building']);

        // Калькулятор молниеотвода
        $conductor_class = $this->getConductorCalculator($this->params['conductor']['type']);

        $conductor_calc = new $conductor_class;

        $conductor_request = $this->params['conductor']['request'];
        $conductor_request['building'] = $this->params['building']['request'];
        $conductor_request['building']['type'] = $this->params['building']['type'];
        $conductor_request['zone'] = $this->params['zone'];

        $conductor_results = $conductor_calc->calc($conductor_request);

        $this->results = [
            'building' => $building_results,
            'conductor' => $conductor_results
        ];

        if (array_key_exists('structure', $this->params)) $this->makePDF($building_calc, $conductor_calc);
    }

    private function makePDF($building_calc, $conductor_calc)
    {
        $this->building_name = $building_calc::NAME;
        $this->building_params = $building_calc->getParamList();
        $this->building_results = $building_calc->getResultList();

        $this->setResultGroup(
            'building',
            'Parameters of the building and its territory',
            $building_calc->getResultList()
        );

        $this->conductor_name = $conductor_calc::NAME;
        $this->conductor_params = $conductor_calc->getParamList();
        $this->conductor_results = $conductor_calc->getResultList();

        $this->setResultGroup(
            'conductor',
            'Parameters of the lightning conductor',
            $conductor_calc->getResultFields()
        );

        $this->structure_params = $this->params['structure'];
    }

    private function mixLengthWidth($building)
    {
        if ($building['type'] === 'rectangle') {
            if ($building['request']['length'] < $building['request']['width']) {
                $buff = $building['request']['length'];
                $building['request']['length'] = $building['request']['width'];
                $building['request']['width'] = $buff;
            }
        }

        return $building['request'];
    }

    public function setResultGroup($key, $name, $group)
    {
        $this->result_groups[$key] = [
            'name' => $name,
            'group' => $group
        ];
    }

    public function getResultFields()
    {
        return $this->result_groups;
    }

    public function getParamList()
    {
        return [];
    }

    public function getFields()
    {
        return [];
    }

    public function makeOrderReport()
    {
        $logo = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ekf-logo-m.svg';

        $title = $this->getName();

        $building_name = $this->building_name;
        $conductor_name = $this->conductor_name;
        $building_params = $this->building_params;
        $conductor_params = $this->conductor_params;
        $building_results = $this->building_results;
        $conductor_results = $this->conductor_results;
        $structure_params = $this->structure_params;

        $content =
            view('reports.lightning',
            compact(
                'title',
                'building_name',
                'conductor_name',
                'building_params',
                'conductor_params',
                'building_results',
                'conductor_results',
                'structure_params'
                ))
            ->render();

        try {
            $mpdf = new Mpdf([
                'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf',

                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 30,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

            $mpdf->WriteHTML($content);

            $filename = 'lightning-protection-' . date("Ymd_His") . ".pdf";
            $folder = Storage::disk('public')->path('');

            $mpdf->Output($folder . DIRECTORY_SEPARATOR . $filename);

            return [
                'name' => $filename,
                'link' => Storage::disk('public')->url($filename)
            ];

        } catch (MpdfException $e) {
            return [
                'errors' => 'Something wrong with PDF'
            ];
        }
    }
}