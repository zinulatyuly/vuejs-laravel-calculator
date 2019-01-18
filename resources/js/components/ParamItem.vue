<template>
    <div v-if="item.values"
         class="form-group row">

        <label :for="prefix + item.key"
               class="col-6 col-md-4 control-label text-right" v-html="item.label"></label>

        <div class="col-md-2 col-6">
            <select v-if="item.type === 'select'"
                    class="form-control"
                    :id="prefix + item.key"
                    :name="item.key"
                    v-model="selected">
                <option v-for="value in item.values"
                        :value="value.key">{{ value.label }}
                </option>
            </select>

            <div v-else class="btn-group bs-toggle-buttons">
                <label
                        v-for="value in item.values"
                        :key="value.key"
                        :class="{ active: selected == value.key }"
                        class="btn btn-default">
                    <input type="radio"
                           :name="item.key"
                           :value="value.key"
                           v-model="selected"> {{ value.label }}
                </label>
            </div>
        </div>
    </div>
    <div v-else-if="item.type === 'radio'" class="radio">
        <label :for="prefix + item.key">
            <input
                :id="prefix + item.key"
                :name="radioName"
                :type="item.type"
                :value="item"
                v-model="selected"> {{ item.label }} </label>
    </div>
    <div v-else
         class=" form-group row"
         :class="[item.type === 'number' && selected < item.min ? 'has-error' : '']">
        <label
                :for="prefix + item.key"
                :class="[item.type !== 'radio' ? 'col-6 col-md-4 control-label text-right' : '']"
                v-html="item.label"></label>
        <div class="col-md-2 col-6">
            <input
                    v-if="item.type === 'number'"
                    :id="prefix + item.key"
                    :name="item.key"
                    :type="item.type"
                    :min="item.min"
                    :max="item.max"
                    :step="item.step"
                    v-model.number="selected"
                    class="form-control">
            <input
                    v-else
                    :id="prefix + item.key"
                    :name="item.key"
                    :type="item.type"
                    :step="item.step"
                    v-model="selected"
                    class="form-control">
            <small class="text-danger" v-if="item.type === 'number' && selected < item.min">Value of this field must be higher than {{ item.min }}!</small>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'zParamItem',
        props: {
            item: {
                type: Object,
                required: true
            },
            first: {
                required: true
            },
            prefix: {
                type: String,
                required: true
            },
            radioName: {
                type: String,
                default: null
            }
        },
        watch: {
          selected(val) {
            this.$emit('input', val);
          }
        },
        data() {
            return {
                selected: this.first || 0
            };
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
