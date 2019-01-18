<template>
    <div>
        <div style="min-height: 150px">
            <param-item
                    v-for="item in params"
                    :item="item"
                    :key="item.key"
                    :first="selected[item.key]"
                    :prefix="prefix"
                    v-model="selected[item.key]"/>
        </div>
        <param-item
                v-for="item in localParams"
                :item="item"
                :key="item.key"
                :first="item.def"
                :prefix="prefix"
                v-model="selected[item.key]"/>

        <hr>

        <result-item
                v-for="item in resultFields"
                :key="item.key"
                :label="item.label"
                :result="results[item.key]"/>
    </div>
</template>

<script>
    import ParamItem from '../ParamItem.vue';
    import ResultItem from '../ResultItem.vue';

    export default {
        name: 'building',
        components: {
            ParamItem,
            ResultItem
        },
        props: {
            params: {
                type: Array,
                required: true
            },
            results: {},
            prefix: {
                type: String,
                default: 'mh_lp_b_',
                required: false
            }
        },
        data() {
            return {
                localParams: [
                    {
                        key: 'density',
                        label: 'Specific density of lightning strikes in the ground N, 1/(km<sup>2</sup>×year):',
                        type: 'select',
                        values: this.getDensities(),
                        min: 1,
                        def: 5.5
                    }
                ],
                selected: {},
                resultFields: [
                    {key: 'levin', label: 'Expected quantity of lightning strikes per year N, pcs:'}
                ]
            };
        },
        methods: {
            setDefault() {
                let selected = {};

                this.params.forEach(({key, def}) => {
                    selected[key] = def;
                });

                this.localParams.forEach(({key, def}) => {
                    selected[key] = def;
                });

                for (let item in this.selected) {
                    if (selected[item]) selected[item] = this.selected[item];
                }

                this.selected = selected;
            },
            updateRequest() {
                this.$emit('input', this.selected);
            },
            getDensities() {
                let buff = [1, 2, 4, 5.5, 7, 8.5],
                    arr = [];

                buff.forEach(function (i) {
                    arr.push({key: i, label: i});
                });

                return arr;
            }
        },
        watch: {
            params: 'setDefault',
            selected: {
                handler() {
                    this.updateRequest()
                },
                deep: true
            }
        },
        beforeMount() {
            this.setDefault();
        }
    };
</script>

<style scoped>
    .bs-toggle-buttons > .btn input[type='radio'],
    .bs-toggle-buttons > .btn-group > .btn input[type='radio'],
    .bs-toggle-buttons > .btn input[type='checkbox'],
    .bs-toggle-buttons > .btn-group > .btn input[type='checkbox'] {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }
</style>