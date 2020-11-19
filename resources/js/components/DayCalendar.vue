<template>
  <div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    TEST
    <h1 class="h4 text-center p-2 col-12">
      {{ competition }} - Calendrier et résultats
    </h1>
    <div
      class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3"
    >
        <!-- <router-link to="/day">Go to Day</router-link> -->
      <router-link
        v-if="hrefjourneeprecedente"
        :to="hrefjourneeprecedente"
        class="float-right pr-2"
        >précédente</router-link>
      <select
        class="form-control col-6 col-sm-4 col-md-3 px-2"
        name="journee"
        id="journee"
        v-on:change="dayChange($event)"
      >
        <!-- @foreach ($journees as $journee_)
            <option data-href="{{ route('competition.day', ['sport' => strToUrl(request()->sport->nom),'competition' => strToUrl(request()->competition->nom),'journee' => $journee_->numero]) }}"
                value="{{ $journee_->numero }}" @if($journee->numero == $journee_->numero) selected @endif>{{ niemeJournee($journee_->numero) }}</option>
        @endforeach -->
        <option
          v-for="journee in journees"
          :value="journee.id"
          :key="journee.id"
          :data-href="journee.url"
          :selected="currentday == journee.numero"
        >
          {{ niemeJournee(journee.numero) }}
        </option>
      </select>
      <a
        v-if="hrefjourneesuivante"
        :href="hrefJourneeSuivante"
        class="float-left pl-2"
        >suivante</a
      >
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
      <div class="col-12 pb-3 mb-3 px-0">
        <div class="px-3">
          <!-- {!! $calendrierJourneeHtml !!} -->
        </div>
      </div>
    </div>
    <div class="col-lg-4 pl-5 pr-0 text-center">PUB</div>

    <!-- <router-view></router-view> -->
  </div>
</template>

<script>
export default {
  props: [
    "competition",
    "hrefjourneesuivante",
    "hrefjourneeprecedente",
    "journees",
    "currentday",
  ],
  data() {
    return {
      test: "",
    };
  },
  mounted() {
    //    console.log('Component mounted. OK !')
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
    //   let href = event.target.options.dataset.href;
    //   cl(option.dataset.href)
    //   return
      //   option = event.target.value;
      document.location = option.dataset.href;
    },
  },
};
</script>
