const single_rod = {
    params: [
        {
            key: 'height',
            label: 'Height of lightning conductor h, m:',
            type: 'number',
            min: 1,
            def: 35
        }
    ],
    resultFields: [
        {key: 'h0', label: 'h<sub>0</sub>'},
        {key: 'r0', label: 'r<sub>0</sub>'},
        {key: 'rx', label: 'r<sub>x</sub>'},
        {key: 'conclusion', label: 'Conclusion:'}
    ]
};

const double_rod = {
    params: [
        {
            key: 'height',
            label: 'Height of lightning conductors h, m:',
            type: 'number',
            min: 1,
            def: 15
        },
        {
            key: 'distance',
            label: 'Distance between them L, m:',
            type: 'number',
            min: 1,
            def: 20
        }
    ],
    resultFields: [
        {key: 'hc', label: 'h<sub>c</sub>'},
        {key: 'rc', label: 'r<sub>c</sub>'},
        {key: 'rcx', label: 'r<sub>cx</sub>'},
        {key: 'conclusion', label: 'Conclusion:'},
    ]
};

const two_diff_rod = {
    params: [
        {
            key: 'height1',
            label: 'Height of lightning conductor h<sub>1</sub>, м:',
            type: 'number',
            min: 1,
            def: 15
        },
        {
            key: 'height2',
            label: 'Height of lightning conductor h<sub>2</sub>, м:',
            type: 'number',
            min: 1,
            def: 20
        },
        {
            key: 'distance',
            label: 'Distance between them L, m:',
            type: 'number',
            min: 1,
            def: 15
        }
    ],
    resultFields: [
        {key: 'hc', label: 'h<sub>c</sub>'},
        {key: 'rc', label: 'r<sub>c</sub>'},
        {key: 'rcx', label: 'r<sub>cx</sub>'},
        {key: 'conclusion', label: 'Conclusion:'},
    ]
};

const multiple_rod = {
    params: [
        {
            key: 'quantity',
            label: 'Quantity of lightning conductors k<sub>m</sub>, pcs:',
            type: 'number',
            min: 3,
            max: 100,
            def: 3
        }
    ]
};

const single_cable = {
    params: [
        {
            key: 'height',
            label: 'Height of lightning conductor h<sub>оп</sub>, m:',
            type: 'number',
            min: 1,
            def: 15
        },
        {
            key: 'span',
            label: 'Span (cable) length a, m:',
            type: 'number',
            min: 1,
            def: 20
        }
    ],
    resultFields: [
        {key: 'h0', label: 'h<sub>0</sub>'},
        {key: 'r0', label: 'r<sub>0</sub>'},
        {key: 'rx', label: 'r<sub>x</sub>'},
        {key: 'conclusion', label: 'Conclusion:'},
    ]
};

const double_cable = {
    params: [
        {
            key: 'height',
            label: 'Height of conductors h<sub>оп</sub>, m:',
            type: 'number',
            min: 1,
            def: 15
        },
        {
            key: 'span',
            label: 'Span (cable) length a, m:',
            type: 'number',
            min: 1,
            def: 20
        },
        {
            key: 'distance',
            label: 'Distance between cables L, m:',
            type: 'number',
            min: 1,
            def: 5
        }
    ],
    resultFields: [
        {key: 'hc', label: 'h<sub>c</sub>'},
        {key: 'r1x', label: 'r\'<sub>x</sub>'},
        {key: 'rc', label: 'r<sub>c</sub>'},
        {key: 'rcx', label: 'r<sub>cx</sub>'},
        {key: 'conclusion', label: 'Conclusion:'},
    ]
};

const two_diff_cable = {
    params: [
        {
            key: 'height1',
            label: 'Height of lightning conductor h<sub>оп1</sub>, м:',
            type: 'number',
            min: 1,
            def: 15
        },
        {
            key: 'height2',
            label: 'Height of lightning conductor h<sub>оп2</sub>, м:',
            type: 'number',
            min: 1,
            def: 20
        },
        {
            key: 'span',
            label: 'Span (cable) length a, m:',
            type: 'number',
            min: 1,
            def: 20
        },
        {
            key: 'distance',
            label: 'Distance between cables L, m:',
            type: 'number',
            min: 1,
            def: 5
        }
    ],
    resultFields: [
        {key: 'hc', label: 'h<sub>c</sub>'},
        {key: 'rc', label: 'r<sub>c</sub>'},
        {key: 'rcx', label: 'r<sub>cx</sub>'},
        {key: 'conclusion', label: 'Conclusion:'},
    ]
};

export default [
    {key: 'single_rod', name: 'Single rod lightning conductor', img: '\\uploads\\tm1.png', data: single_rod},
    {key: 'double_rod', name: 'Double rod conductor with equal lengths', img: '\\uploads\\tm2.png', data: double_rod},
    {key: 'two_diff_rod', name: 'Two rod conductors with different lengths', img: '\\uploads\\tm3.png', data: two_diff_rod},
    {key: 'multiple_rod', name: 'Multiple rod conductor', img: '\\uploads\\tm4.png', data: multiple_rod},
    {key: 'single_cable', name: 'Single cable conductor', img: '\\uploads\\tm5.png', data: single_cable},
    {key: 'double_cable', name: 'Double cable conductor with equal lengths', img: '\\uploads\\tm6.png', data: double_cable},
    {key: 'two_diff_cable', name: 'Two cable conductors with different lengths', img: '\\uploads\\tm7.png', data: two_diff_cable},
];