<template>
  <div>
    <Head title="Learn" />
    <h1 class="mb-8 text-3xl font-bold">Learn with Cards</h1>
    <div class="flex items-center justify-between mb-6">
      <search v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-pre-wrap leading-normal">
        <tbody>
          <tr v-for="card in cards.data" :key="card.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td v-if="showFront" class="border-t flex items-center p-6 lg:p-24 h-64" @click="showFront=false">
              {{ card.front }}
            </td>
            <td v-else class="border-t flex items-center p-6 lg:p-24 h-64" @click="showFront=true">
              {{ card.back }}
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
import throttle from 'lodash/throttle'
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
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
      showFront: true,
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/learn', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
