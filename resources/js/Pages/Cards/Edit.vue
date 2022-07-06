<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/cards">Cards</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.front }}
    </h1>
    <trashed-message v-if="card.deleted_at" class="mb-6" @restore="restore"> This card has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.main_subject" :error="form.errors.main_subject" class="pb-8 pr-6 w-full lg:w-1/2" label="mainsubject" />
          <text-input v-model="form.subject" :error="form.errors.subject" class="pb-8 pr-6 w-full lg:w-1/2" label="subject" />
          <text-input v-model="form.front" :error="form.errors.front" class="pb-8 pr-6 w-full lg:w-1/2" label="front" />
          <text-input v-model="form.back" :error="form.errors.back" class="pb-8 pr-6 w-full lg:w-1/2" label="back" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!card.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Card</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Card</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    card: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        main_subject: this.card.main_subject,
        subject: this.card.subject,
        front: this.card.front,
        back: this.card.back,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/cards/${this.card.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this card?')) {
        this.$inertia.delete(`/cards/${this.card.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this card?')) {
        this.$inertia.put(`/cards/${this.card.id}/restore`)
      }
    },
  },
}
</script>
