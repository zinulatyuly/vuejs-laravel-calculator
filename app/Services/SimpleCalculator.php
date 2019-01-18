<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

abstract class SimpleCalculator
{
    const NAME = '';

    const VALIDATOR_RULE = [];

    protected $params = [];

    protected $results = [];

    protected $template = 'reports.pdf.programs.simple';

    protected $report_filename = 'area-losses';

    public function calc(Request $request)
    {
        if (static::VALIDATOR_RULE) {
            $validator = Validator::make($request->all(), static::VALIDATOR_RULE);
            if ($validator->fails()) {
                return ['errors' => $validator->messages()];
            }
        }

        $this->params = $request->all();

        $this->makeCalc();

        return $this->results;
    }

    abstract public function makeCalc();

    public function getResults()
    {
        return $this->results;
    }

    public function getName()
    {
        return static::NAME;
    }

    /**
     * Params List for PDF
     *
     * @return \Illuminate\Support\Collection
     */
    public function getParamList()
    {
        $data = [];

        foreach($this->getFields() as $k => $field){
            if (isset($this->params[$k])) {
                if (is_array($field) && isset($field['values'][$this->params[$k]])) {
                    $data[$k] = [
                        'name' => $field['name'],
                        'value' => $field['values'][$this->params[$k]]
                    ];
                } else {
                    $data[$k] = [
                        'name' => $field,
                        'value' => $this->params[$k]
                    ];
                }
            }
        }

        return collect($data);
    }

    /**
     * Result List for PDF
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResultList()
    {
        $data = [];

        foreach($this->getResultFields() as $g => $field){
            if (isset($this->results[$g])) {
                if (isset($field['group'])) {
                    $group = [];

                    foreach($field['group'] as $v => $name) {
                        if (isset($this->results[$g][$v])) {
                            $group[$v] = [
                                'name' => $name,
                                'value' => $this->results[$g][$v]
                            ];
                        }
                    }

                    $data[$g] = [
                        'name' => $field['name'],
                        'group' => $group
                    ];
                } else {
                    $data[$g] = [
                        'name' => $field,
                        'value' => $this->results[$g]
                    ];
                }
            }
        }

        return collect($data);
    }

    /**
     * Result list for Form
     *
     * @return array
     */
    public function getResultFields()
    {
        return [
            'result'     => 'Результат:',
        ];
    }

    /**
     * Fields list for generate Form
     *
     * @return array
     */
    abstract public function getFields();

    public function export(){
        $logo = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ekf-logo-m.svg';

        $title = $this->getName();
        $params = $this->getParamList();
        $results = $this->getResultList();

        $content = view($this->template, compact('params', 'results', 'title'))->render();
        $header = view('reports.pdf.header', compact('logo'))->render();
        $footer = view('reports.pdf.footer')->render();

        try {
            $mpdf = new Mpdf([
                'tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf',

                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 30,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

            // LOAD a stylesheet
            // $stylesheet = file_get_contents('assets/mpdfstyleA4.css');
            // $mpdf->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no body/html/text

            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            $mpdf->WriteHTML($content);

            $report_filename = 'ekf-' . $this->report_filename . '-' . date("d.m.Y_H-i-s") . ".pdf";

            $filePathWithName = storage_path() . DIRECTORY_SEPARATOR . $report_filename;

            $mpdf->Output($filePathWithName);

            return [
                'download' => $filePathWithName,
            ];

            /* $report_filename = 'ekf-ground-' . date("d.m.Y H-i-s") . ".pdf";

            $path = base_path('public\uploads\reports') . DIRECTORY_SEPARATOR . $report_filename;

            $mpdf->Output($path);

            $download_link = asset('public/uploads/reports/'.$report_filename);

            return [
                'download' => $path,
                'link' => $download_link
            ];*/

        } catch (MpdfException $e) {
            return ['errors' => 'Something wrong with PDF'];
        }
    }
}