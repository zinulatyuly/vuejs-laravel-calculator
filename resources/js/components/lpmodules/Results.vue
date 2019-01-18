<template>
    <div class="form-group">
        <div class="card">
            <div class="card-header">Results</div>
            <div class="card-body">
                <result-item
                        v-for="item in fields"
                        v-if="results[item.key]"
                        :item="item"
                        :key="item.key"
                        :label="item.label"
                        :result="results[item.key]"/>

                <div class="form-group" v-if="results.errors">

                    <div class="form-group row">
                        <div class="col-12 col-md-4"
                             :class="[$mq === 'mobile' ? 'text-center' : 'text-right']">
                            <h4 class="font-weight-bold text-danger">Error</h4>
                        </div>
                    </div>
                    <result-item
                            v-for="(value, key) in results.errors"
                            :item="key"
                            :key="key"
                            :label="key"
                            :result="value"/>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
  import ResultItem from '../ResultItem.vue';


  export default {
    name: 'results',
    components: {
      ResultItem
    },
    props: {
      prefix: {
        type: String,
        default: 'mt_lp_res_',
        required: false
      },
      results: Object,
      fields: Array
    }
  };
</script>