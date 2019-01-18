<template>
    <form class="form-horizontal">
        <div class="form-group">
            <div class="card">
                <div class="card-header">Parameters of the building and its territory</div>
                <div class="card-body">
                    <div class="row"
                         :class="{'text-center' : $mq === 'mobile'}">
                        <label class="col-12 col-md-4"
                               :class="{'control-label text-right': $mq === 'desktop'}">
                            Building type:
                        </label>
                        <div class="col-12 col-md-8 bs-toggle-buttons"
                             :class="{'btn-group': $mq === 'desktop'}">
                            <label
                                    v-for="building in buildings"
                                    :key="building.key"
                                    :class="{ active: activeBuilding.key === building.key }"
                                    class="btn btn-light">
                                <input type="radio"
                                       :name="building.key"
                                       :value="building"
                                       v-model="activeBuilding"> {{ building.name }}
                            </label>
                        </div>
                    </div>
                    <hr>
                    <building
                            :params="activeBuilding.params"
                            :results="building_results"
                            @input="updateBuilding"
                    />
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="card">
                <div class="card-header">Parameters of the lightning conductor</div>
                <div class="card-body">
                    <div class="row form-group"
                         :class="{'text-center' : $mq === 'mobile'}">
                        <label class="col-12 col-md-4"
                               :class="{'control-label text-right': $mq === 'desktop'}">
                            Lightning conductor:
                        </label>
                        <div class="col-12 col-md-6">
                            <select class="form-control" v-model="activeConductor">
                                <option v-for="conductor in conductors"
                                        :key="conductor.key"
                                        :value="conductor"
                                >{{ conductor.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <multiple-conductor v-if="activeConductor.key === 'multiple_rod'"
                                        key="multiple-rod-conductor"
                                        :params="activeConductor.data.params"
                                        :img="activeConductor.img"
                                        @input="updateConductor"
                                        @inputFields="addFieldsData"/>
                    <conductor v-else
                               key="non-multiple-rod-conductor"
                               :params="activeConductor.data.params"
                               :img="activeConductor.img"
                               @input="updateConductor"
                    />
                </div>
            </div>
        </div>


        <structure
                @input="updateZone"
        />

        <results :results="results" :fields="activeConductor.data.resultFields"/>

        <div class="form-group" v-show="!results.errors">
            <div class="row justify-content-center">
                <button
                        class="btn btn-success"
                        type="button"
                        @click="getPDF">
                    Download PDF
                </button>
            </div>
        </div>
    </form>
</template>

<script>
  import buildings from './lpmodules/store/buildings.js';
  import conductors from './lpmodules/store/conductors.js';
  import Building from './lpmodules/Building';
  import Conductor from './lpmodules/Conductor';
  import MultipleConductor from './lpmodules/MultipleConductor';
  import Structure from './lpmodules/Structure';
  import Results from './lpmodules/Results';

  export default {
    components: {
      Building,
      Conductor,
      MultipleConductor,
      Structure,
      Results
    },
    props: {
      internalApi: {
        type: String,
        default: '/lightning-protection',
        required: false
      },
      prefix: {
        type: String,
        default: 'ekf_mt_lp_',
        required: false
      }
    },
    data() {
      return {
        buildings: buildings,
        activeBuilding: {},
        building_request: {},
        building_results: {},

        conductors: conductors,
        activeConductor: {},
        conductor_request: {},

        activeStructure: '',

        results: {},
      };
    },
    watch: {
      'activeStructure.zone': 'getCalc',
      building_request: {
        handler() {
          this.getCalc();
        },
        deep: true
      },
      conductor_request: {
        handler() {
          this.getCalc();
        },
        deep: true
      }
    },
    created() {
      this.activeBuilding = this.buildings[0];
      this.activeConductor = this.conductors[0];
    },
    methods: {
      getCalc() {
        this.results = {};
        axios.post(this.internalApi + '/calc', {
          building: {
            type: this.activeBuilding.key,
            request: this.building_request
          },
          conductor: {
            type: this.activeConductor.key,
            request: this.conductor_request
          },
          zone: this.activeStructure.zone
        }).then(response => {
          this.building_results = response.data.building;
          this.results = response.data.conductor;
        }).catch((error) => {
          this.errors = error.response.data.errors;
        });
      },
      getPDF() {
        axios
          .post(this.internalApi + '/export', {
            building: {
              type: this.activeBuilding.key,
              request: this.building_request
            },
            conductor: {
              type: this.activeConductor.key,
              request: this.conductor_request
            },
            zone: this.activeStructure.zone,
            structure: this.activeStructure
          })
          .then(response => {
            const link = document.createElement('a');
            link.href = response.data.link;
            link.setAttribute('download', response.data.name);
            document.body.appendChild(link);
            link.click();
          });
      },
      updateBuilding(v) {
        this.building_request = v;
      },
      updateConductor(v) {
        this.conductor_request = v;
      },
      updateZone(v) {
        this.activeStructure = v;
      },
      addFieldsData(v) {
        this.activeConductor.data['resultFields'] = v;
      }
    }
  };
</script>

<style scoped>
    .control-label {
        padding-top: 7px;
        margin-bottom: 0;
    }

    .tab-button {
        padding: 6px 10px;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border: 1px solid #ccc;
        cursor: pointer;
        background: #f0f0f0;
        margin-bottom: -1px;
        margin-right: -1px;
    }

    .tab-button:hover {
        background: #e0e0e0;
    }

    .tab-button.active {
        background: #e0e0e0;
    }

    .tab {
        border: 1px solid #ccc;
        padding: 10px;
    }
</style>
