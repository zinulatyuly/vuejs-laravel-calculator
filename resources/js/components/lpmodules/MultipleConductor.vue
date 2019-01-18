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

        <param-item
                v-for="item in msmData"
                :item="item"
                :key="item.key"
                :first="msmRequest[item.key]"
                :prefix="prefix"
                v-model="msmRequest[item.key]"
        />

    </div>
</template>

<script>
  import ParamItem from '../ParamItem.vue';

  export default {
    name: 'MultipleConductor',
    components: {
      ParamItem
    },
    props: {
      prefix: {
        type: String,
        default: 'mh_lp_mc_',
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
        selected: {},
        msmData: {},
        msmRequest: {},
      };
    },
    watch: {
      params: 'setDefault',
      msmRequest:
        {
          handler() {
            this.updateRequest();
          }
          ,
          deep: true
        }
      ,
      selected: {
        handler() {
          this.createMsmData(this.selected.quantity);
          this.createMsmResultFields();
        }
        ,
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
        this.$emit('input', { ...this.selected, ...this.msmRequest });
      },
      createMsmResultFields() {
        let arr = [],
          { quantity } = this.selected;
        for (let i = 1; i <= quantity; i++) {
          arr.push(
            {
              key: `hc${i}`,
              label: `h<sub>c${i}</sub>`
            },
            {
              key: `rc${i}`,
              label: `r<sub>c${i}</sub>`
            },
            {
              key: `rcx${i}`,
              label: `r<sub>cx${i}</sub>`
            }
          )
        }
        arr.push(
          {
            key: 'conclusion',
            label: 'Conclusion'
          }
        );
        this.$emit('inputFields', arr);
      },
      createMsmData(quantity) {

        let data = [],
          request = {},
          step = null;

        if (quantity > 2 && quantity < 100) {
          for (let i = 1; i < quantity; i++) {
            step = i + 1;
            data.push(
              {
                key: `height${i}`,
                label: `Height of conductor ${i} h<sub>${i}</sub>, m:`,
                type: 'number'
              },
              {
                key: `distance${i}`,
                label: `Distance between conductors ${i} and ${step} L<sub>${i}${step}</sub>, m:`,
                type: 'number'
              }
            );

            request[`height${i}`] = 20;
            request[`distance${i}`] = 5;
          }

          data.push(
            {
              key: `height${quantity}`,
              label: `Height of conductor ${quantity} h<sub>${quantity}</sub>, m:`,
              type: 'number'
            },
            {
              key: `distance${quantity}`,
              label: `Distance between conductors ${quantity} and 1 L<sub>${quantity}1</sub>, m:`,
              type: 'number'
            }
          );
          request[`height${quantity}`] = 20;
          request[`distance${quantity}`] = 5;

          this.msmData = data;
          this.msmRequest = request;
        } else {
          this.msmData = {};
          this.msmRequest = {};
        }
      }
    },
  };
</script>