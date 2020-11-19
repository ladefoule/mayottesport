<template>
  <div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    TEST {{ current }}
    <h1 class="h4 text-center p-2 col-12">
      {{ competition }} - Calendrier et résultats
    </h1>
    <div
      class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3"
    >
        <!-- <router-link to="/day">Go to Day</router-link> -->
      <router-link
        v-if="previous"
        :to="previous"
        class="float-right pr-2"
        >précédente</router-link>
      <select
        class="form-control col-6 col-sm-4 col-md-3 px-2"
        name="journee"
        id="journee"
        v-on:change="dayChange($event)"
      >
        <option
          v-for="day in days"
          :value="day.id"
          :key="day.id"
          :data-href="day.url"
        >
          {{ niemeJournee(day.numero) }}
        </option>
      </select>
      <router-link
        v-if="previous"
        :to="previous"
        class="float-left pl-2"
        >suivante</router-link>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
      <div class="col-12 pb-3 mb-3 px-0">
        <div class="px-3">
            <a v-for="match in matches" :href="match.url" :key="match.id" class="text-decoration-none text-body match-calendrier">
                <div class="row d-flex flex-nowrap py-2 border-bottom-dashed @if($i==0) border-top-dashed @endif">
                    <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match['score_eq_dom'] > $match['score_eq_ext']) font-weight-bold @endif">
                        <div>
                            <img :src="match.fanion_eq_dom" :alt="match.nom_eq_dom" class="fanion-calendrier pr-2">
                        </div>
                        <div class="text-right">
                            {{ match.nom_eq_dom }}
                        </div>
                    </div>
                    <div class="col-2 d-flex justify-content-center align-items-center p-0">
                        {{ match.score }}
                    </div>
                    <div class="col-5 p-0 d-flex justify-content-between align-items-center @if(match.score_eq_dom < match.score_eq_ext) font-weight-bold @endif">
                        <div class="text-left">
                            {{ match.nom_eq_ext }}
                        </div>
                        <div>
                            <img :src="match.fanion_eq_ext" :alt="match.nom_eq_ext" class="fanion-calendrier pl-2">
                        </div>
                    </div>
                </div>
            </a>
        </div>
      </div>
    </div>
    <div class="col-lg-4 pl-5 pr-0 text-center">PUB</div>

    <!-- <router-view></router-view> -->
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: [
    "competition",
    "previous",
    "next",
    "days",
    "current",
    "matches",
  ],
  data() {
    return {
        // previous: this
    }
  },
  mounted() {
       console.log('Component mounted. OK !')
    // cl('monté')
  },
  methods: {
    niemeJournee(numero) {
      if (numero <= 0) return false;
      return (numero == 1 ? "1ère" : numero + "ème") + " journée";
    },

    qs: (query, elem = document) => elem.querySelector(query),
    qsa: (query, elem = document) => elem.querySelectorAll(query),
    cl: (elem) => console.log(elem),
    dce: (elem) => document.createElement(elem),

    dayChange(event) {
        let  option = event.target.options[event.target.selectedIndex]
        axios.get('/api/journee/calendrier', {params: {saison:1, journee:5}})
        .then(res => {
            this.$router.push(option.dataset.href)
        })
        .catch(err => {
            cl(err)
        })
    },
  },
};
</script>
