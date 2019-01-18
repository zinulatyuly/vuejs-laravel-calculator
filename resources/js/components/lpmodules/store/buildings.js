const rectangle = [
  {
    key: 'length',
    label: 'Length А, m:',
    type: 'number',
    min: 1,
    def: 20
  },
  {
    key: 'width',
    label: 'Width B, m:',
    type: 'number',
    min: 1,
    def: 40
  },
  {
    key: 'height',
    label: 'Height h<sub>x</sub>, m:',
    type: 'number',
    min: 1,
    def: 10
  }
];

const circle = [
    {
        key: 'diameter',
        label: 'Diameter D, m:',
        type: 'number',
        min: 1,
        def: 10
    },
    {
        key: 'height',
        label: 'Height h<sub>x</sub>, m:',
        type: 'number',
        min: 1,
        def: 10
    }
];


export default [
  { key: 'rectangle', name: 'Rectangle', params: rectangle },
    { key: 'circle', name: 'Circle (tube, tower, belfry, etc.)', params: circle },
];