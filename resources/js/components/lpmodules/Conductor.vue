<template>
  <div>
    <div class="form-group text-center">
        <img class="img-fluid" :src="img" height="350px">
    </div>

    <hr>

    <param-item
            v-for="item in params"
            :item="item"
            :key="item.key"
            :first="selected[item.key]"
            :prefix="prefix"
            v-model="selected[item.key]"/>

  </div>
</template>

<script>
import ParamItem from '../ParamItem.vue';

export default {
  name: 'conductor',
  components: {
    ParamItem
  },
  props: {
    prefix: {
      type: String,
      default: 'mh_lp_c_',
      required: false
    },
    params: {
      type: Array,
      required: true
    },
    img: String

  },
  data() {
    return {
      selected: {}
    };
  },
  watch: {
    params: 'setDefault',
    selected: {
      handler() {
          this.updateRequest();
      },
      deep: true
    }
  },
  beforeMount() {
    this.setDefault();
  },
  methods: {
    setDefault() {
      let selected = {};

      this.params.forEach(({ key, def }) => {
        selected[key] = def;
      });

      for (let item in this.selected) {
        if (selected[item]) selected[item] = this.selected[item];
      }

      this.selected = selected;
    },
    updateRequest() {
      this.$emit('input', this.selected);
    }
  }
};
</script>