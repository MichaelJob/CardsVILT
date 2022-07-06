<template>
  <div>
    <Head title="Cards" />
    <h1 class="mb-8 text-3xl font-bold">Cards</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/cards/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Card</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Mainsubject</th>
            <th class="pb-4 pt-6 px-6">Subject</th>
            <th class="pb-4 pt-6 px-6">Front</th>
            <th class="pb-4 pt-6 px-6">Back</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="card in cards.data" :key="card.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/cards/${card.id}/edit`">
                {{ card.main_subject }}
                <icon v-if="card.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/cards/${card.id}/edit`" tabindex="-1">
                {{ card.subject }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/cards/${card.id}/edit`" tabindex="-1">
                {{ card.front }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/cards/${card.id}/edit`" tabindex="-1">
                {{ card.back }}
              </Link>
            </td>
          </tr>
          <tr v-if="cards.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No cards found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="cards.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
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
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/cards', pickBy(this.form), { preserveState: true })
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
