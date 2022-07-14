<template>
  <div>
    <Head title="Learn" />
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center justify-between py-6">
        <select v-model="form.topic" class="form-select mr-4 py-3 w-full">
          <option :value="null">all cards ({{ cards.total }})</option>
          <option v-for="topic in topics" :key="topic" :value="topic.subject">{{ topic.subject }} ({{ topic.total }})</option>
        </select>
        <p class="mr-4">or</p>
        <search v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      </div>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-pre-wrap leading-normal">
        <tbody>
          <tr v-for="card in cards.data" :key="card.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td v-if="showFront" class="border-t flex items-center p-6 lg:p-24 h-64 cardbg" @click="showFront=false">
              {{ card.front }}
              <img
                v-if="card.front_photo"
                class="inline w-64 h-64 p-6" 
                :src="card.front_photo"
                alt="front-photo"
                @error="$event.target.src = '../../images/notfound.png'"
              />
            </td>
            <td v-else class="border-t flex items-center p-6 lg:p-24 h-64 invcardbg" @click="showFront=true">
              {{ card.back }}
              <img
                v-if="card.back_photo"
                class="inline w-64 h-64 p-6" 
                :src="card.back_photo"
                alt="back-photo"
                @error="$event.target.src = '../../images/notfound.png'"
              />
            </td>
          </tr>
          <tr v-for="card in cards.data" :key="card.id">
            <td class="lg:pl-24 p-4 border-t">
              {{ card.main_subject }} / {{ card.subject }}
            </td>
          </tr>
          <tr v-if="cards.data.length === 0">
            <td class="px-6 py-4 border-t">No card found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="cards.links" />
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import pickBy from 'lodash/pickBy'
import LayoutGuest from '@/Shared/LayoutGuest'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import Search from '@/Shared/Search'

export default {
  components: {
    Head,
    Pagination,
    Search,
  },
  layout: LayoutGuest,
  props: {
    filters: Object,
    cards: Object,
    topics: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        topic: this.filters.topic,
      },
      showFront: true,
    }
  },
  watch: {
    form: {
      deep: true,
      handler: debounce(function () {
        this.$inertia.get('/', pickBy(this.form), { preserveState: true })
      }, 750),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
