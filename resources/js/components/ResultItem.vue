<template>
    <div class="form-group row">
        <div class="col-6 col-md-4 text-right">
            <h4 class="zresult" v-html="label"></h4>
        </div>
        <div class="col-6 col-md-8">
            <h4 class="form-control-static" :class="result_class">{{ result | roundalize }}</h4>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'zResultItem',
    filters: {
      roundalize: function (value) {
        if ($.isNumeric(value)) value = Number(value.toFixed(2));
        return value;
      }
    },

    props: {
      label: {
        type: String,
        required: true
      },
      result: {
        required: true
      },
      result_class: {
        type: String,
        required: false
      }
    }
  };
</script>

<style scoped>
    .zresult {
        font-weight: bold
    }
</style>