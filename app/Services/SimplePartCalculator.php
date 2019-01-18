<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;

abstract class SimplePartCalculator
{
    const VALIDATOR_RULE = [];

    protected $params = [];

    protected $results = [];

    protected $errors = [];

    public function calc($input)
    {
        if (static::VALIDATOR_RULE) {
            $validator = Validator::make($input, static::VALIDATOR_RULE);
            if ($validator->fails()) {
                return ['errors' => $validator->messages()];
            }
        }

        $this->params = $input;

        $this->makeCalc();

        if ($this->errors) {
            return ['errors' => $this->errors];
        }

        return $this->results;
    }

    abstract public function makeCalc();

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

}