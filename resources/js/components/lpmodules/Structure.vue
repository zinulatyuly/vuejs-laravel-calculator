<template>
    <div class="form-group">
        <div class="card">
            <div class="card-header">
                Kinds of constructions, protection zone types and categories of lightning protection
            </div>
            <div class="card-body">
                <div>
                    <div style="margin-bottom: 10px">
                <textarea
                        class="form-control"
                        style="resize: vertical" rows="4"
                        :value="selected.kind.label"
                        readonly>
                </textarea>
                    </div>
                    <p>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse"
                                data-target="#LPKindsContent">Show/hide
                            list of buildings <br v-if="$mq==='mobile'">and constructions
                        </button>
                    </p>
                    <div class="collapse" id="LPKindsContent">
                        <div class="card card-body">
                            <div class="radio" v-for="(kind, index) in structure.kinds" :key="index">
                                <label :for="prefix + 'kind_' + kind.key">
                                    <input
                                            :id="prefix + 'kind_' + kind.key"
                                            type="radio"
                                            :value="kind"
                                            :name="prefix + 'kind'"
                                            v-model="selected.kind"> {{ kind.label }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div>
                    <div style="margin-bottom: 10px">
                <textarea
                        class="form-control"
                        style="resize: vertical"
                        rows="3"
                        :value="selected.location.label"
                        readonly>
                </textarea>
                    </div>
                    <p>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse"
                                data-target="#LPLocationsContent">Show/hide list of
                            placements
                        </button>
                    </p>
                    <div class="collapse" id="LPLocationsContent">
                        <div class="card card-body">
                            <div class="radio" v-for="(location, index) in structure.locations" :key="index">
                                <label :for="prefix + 'location_' + location.key"
                                       :disabled="ifLocationExists(location)">
                                    <input
                                            :disabled="ifLocationExists(location)"
                                            :id="prefix + 'location_' + location.key"
                                            type="radio"
                                            :value="location"
                                            :name="prefix + 'location'"
                                            v-model="selected.location"> {{ location.label }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row"
                     :class="{'text-center' : $mq === 'mobile'}">
                    <label class="col-12 col-md-4"
                           :class="{'control-label text-right': $mq === 'desktop'}"
                           :for="prefix + 'zones_' + structure.zones.key"
                           v-html="structure.zones.label"></label>
                    <div class="col-12 col-md-4 bs-toggle-buttons"
                         :class="{'btn-group': $mq === 'desktop'}">
                        <label
                                v-for="(value, index) in structure.zones.values"
                                :key="index"
                                :class="{ active: selected.zone === value.key, disabled: ifZoneExists(value.key) }"
                                class="btn btn-light"
                                :aria-disabled="ifZoneExists(value.key)">
                            <input type="radio"
                                   :name="structure.zones.key"
                                   :value="value.key"
                                   :disabled="ifZoneExists(value.key)"
                                   v-model="selected.zone"> {{ value.label }}
                        </label>
                    </div>
                </div>

                <div class="form-group row"
                     :class="{'text-center' : $mq === 'mobile'}">
                    <label class="col-12 col-md-4"
                           :class="{'control-label text-right': $mq === 'desktop'}"
                           :for="prefix + 'categories_' + structure.categories.key"
                           v-html="structure.categories.label"></label>
                    <div class="col-12 col-md-4 bs-toggle-buttons"
                         :class="{'btn-group': $mq === 'desktop'}">
                        <label
                                v-for="(value, index) in structure.categories.values"
                                :key="index"
                                :class="{ active: selected.category === value.key, disabled: ifCategoryExists(value.key) }"
                                :aria-disabled="ifCategoryExists(value.key)"
                                class="btn btn-light">
                            <input type="radio"
                                   :name="structure.categories.key"
                                   :value="value.key"
                                   :disabled="ifCategoryExists(value.key)"
                                   v-model="selected.category"> {{ value.label }}
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
  import ResultItem from '../ResultItem.vue';
  import structures from './store/structures.js';

  export default {
    name: 'structure',
    components: {
      ResultItem
    },
    props: {
      prefix: {
        type: String,
        default: 'mt_lp_s_',
        required: false
      }
    },
    data() {
      return {
        structure: structures,
        selected: {
          kind: structures.kinds[0],
          location: null,
          zone: null,
          category: null
        }
      };
    },
    watch: {
      'selected.kind': 'matchData',
      'selected.zone': 'updateRequest'
    },
    beforeMount() {
      this.setData()
    },
    methods: {
      updateRequest() {
        this.$emit('input', this.selected);
      },
      matchData() {
        if (!this.selected.kind.locations.includes(this.selected.location.key))
          this.selected.location = this.structure.locations.find(item => item.key === this.selected.kind.locations[0]);
        if (!this.selected.kind.zones.includes(this.selected.zone))
          this.selected.zone = this.selected.kind.zones[0];
        if (!this.selected.kind.categories.includes(this.selected.category))
          this.selected.category = this.selected.kind.categories[0];
      },
      setData() {
        this.selected['location'] = this.structure.locations.find(item => item.key === this.selected.kind.locations[0]);
        this.selected['zone'] = this.selected.kind.zones[0];
        this.selected['category'] = this.selected.kind.categories[0];
      },
      ifLocationExists(location) {
        if (!this.selected.kind.locations.includes(location.key)) return true;
        return false;
      },
      ifZoneExists(zone) {
        if (!this.selected.kind.zones.includes(zone)) return true;
        return false;
      },
      ifCategoryExists(cat) {
        if (!this.selected.kind.categories.includes(cat)) return true;
        return false;
      }
    }
  };
</script>