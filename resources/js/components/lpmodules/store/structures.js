const kinds = [
    {
        key: '1',
        label: '(1) Buildings and structures or their parts, the premises of which, according to the OES, belong to zones of classes B-I and B-II.',
        type: 'radio',
        locations: ['sng', '10'],
        zones: ['a', 'b'],
        categories: ['1']
    },
    {
        key: '2',
        label: '(2) Outdoor installations that in accordance with the EIR, create a zone of class B-Ig.',
        type: 'radio',
        locations: ['sng'],
        zones: ['b'],
        categories: ['2']
    },
    {
        key: '3',
        label: '(3) Buildings and structures or their parts, the premises of which, in accordance with the EMP, belong to zones of classes PI, P-II, P-IIa. For buildings and structures I and II degrees of fire resistance.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '4',
        label: '(4) Buildings and structures or their parts, the premises of which, in accordance with the EMP, belong to zones of classes PI, P-II, P-IIa. For buildings and structures III - V degrees of fire resistance.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']

    },
    {
        key: '5',
        label: '(5) Buildings and structures or their parts, the premises of which, according to the OES, belong to the zones of classes PI, P-II, P-IIa. For buildings and structures I - V degrees of fire resistance.',
        type: 'radio',
        locations: ['20'],
        zones: ['a'],
        categories: ['3']
    },
    {
        key: '6',
        label: '(6) Located in rural areas, small buildings of III - V degrees of fire resistance, the premises of which, according to PUE, belong to areas of classes PI, P-II, P-IIa.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '7',
        label: '(7) Outdoor installations and open warehouses, which, in accordance with PUE, create a zone of classes P-III.',
        type: 'radio',
        locations: ['20'],
        zones: ['a', 'b'],
        categories: ['3']
    },
    {
        key: '8',
        label: '(8) Buildings and structures of III, IIIa, IIIb, IV, V degrees of fire resistance, in which there are no premises attributed to the EMP to the zones of explosion and fire hazard classes.',
        type: 'radio',
        locations: ['10'],
        zones: ['a', 'b'],
        categories: ['3']
    },
    {
        key: '9',
        label: '(9) Buildings and constructions made of light metal structures with a combustible insulation (IVa degree of fire resistance), in which there are no premises attributed to the EMI to the zones of explosion and fire hazardous classes.',
        type: 'radio',
        locations: ['10'],
        zones: ['a', 'b'],
        categories: ['3']
    },
    {
        key: '10',
        label: '(10) Small buildings of III-V degrees of fire resistance, located in rural areas, in which there are no premises attributed to the PUE to zones of explosion and fire hazardous classes.',
        type: 'radio',
        locations: ['20-345', '20-4a'],
        zones: ['a'],
        categories: ['3']
    },
    {
        key: '11',
        label: '(11) Buildings of computer centers, including those located in urban areas.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['2']
    },
    {
        key: '12',
        label: '(12) Livestock and poultry buildings and structures of III-V degrees of fire resistance: for cattle and pigs per 100 heads or more, for sheep for 500 heads and more, for poultry per 1000 heads or more, for horses for 40 heads and more.',
        type: 'radio',
        locations: ['40'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '13',
        label: '(13) Smoke and other pipes of enterprises and boiler houses, towers and towers of all purposes with a height of 15 m and more.',
        type: 'radio',
        locations: ['10'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '14',
        label: '(14) Residential and public buildings, the height of which is more than 25 m higher than the average height of surrounding buildings within a radius of 400 m, as well as detached buildings with a height of more than 30 m, remote from other buildings more than 400 m.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '15',
        label: '(15) Detached residential and public buildings in rural areas with a height of more than 30 m.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '16',
        label: '(16) Public buildings of III-V degrees of fire resistance for the following purposes: kindergartens, schools and boarding schools, hospitals of medical institutions, dormitories and canteens of health care and recreation facilities, educational and entertainment institutions, administrative buildings, stations, hotels, motels and campgrounds.',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '17',
        label: '(17) Open entertainment establishments (auditoriums of open cinemas, stands of open stadiums, etc.).',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },
    {
        key: '18',
        label: '(18) Buildings and structures that are monuments of history, architecture and culture (sculptures, obelisks, etc.).',
        type: 'radio',
        locations: ['20'],
        zones: ['b'],
        categories: ['3']
    },

];

const locations = [
    {
        key: 'sng',
        type: 'radio',
        label: 'Throughout the CIS',
    },
    {
        key: '10',
        type: 'radio',
        label: 'In areas with an average duration of thunderstorms 10 hours per year or more',
    },
    {
        key: '20',
        type: 'radio',
        label: 'In areas with an average duration of thunderstorms 20 hours per year or more'
    },
    {
        key: '20-345',
        type: 'radio',
        label: 'In areas with an average duration of thunderstorms of 20 hours per year or more for III, IIIa, IIIb, IV, V degrees of fire resistance'
    },
    {
        key: '20-4a',
        type: 'radio',
        label: 'In areas with an average duration of thunderstorms 20 hours per year or more for IVa degree of fire resistance'
    },
    {
        key: '40',
        type: 'radio',
        label: 'In areas with an average duration of thunderstorms 40 hours per year or more'
    }
];
const zones = {
    key: 'zone',
    label: 'Type of protection zone:',
    type: 'btn_group',
    values: [
        {
            key: 'a',
            label: 'A'
        },
        {
            key: 'b',
            label: 'B'
        }
    ]
};
const categories = {
    key: 'category',
    label: 'Category of lightning protection:',
    type: 'btn_group',
    values: [
        {
            key: '1',
            label: 'I'
        },
        {
            key: '2',
            label: 'II'
        },
        {
            key: '3',
            label: 'III'
        }
    ]
};

export default {
    kinds: kinds,
    locations: locations,
    categories: categories,
    zones: zones
};