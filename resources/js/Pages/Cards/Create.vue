<template>
  <div>
    <Head title="Create Card" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/cards">Cards</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-8xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.main_subject" :error="form.errors.main_subject" class="pb-8 pr-6 w-full lg:w-1/2" label="mainsubject" />
          <text-input v-model="form.subject" :error="form.errors.subject" class="pb-8 pr-6 w-full lg:w-1/2" label="subject" />
          <text-input ref="front" v-model="form.front" :error="form.errors.front" class="pb-8 pr-6 w-full" label="front" />
          <text-input v-model="form.back" :error="form.errors.back" class="pb-8 pr-6 w-full" label="back" />
          <file-input v-model="form.front_photo" :error="form.errors.front_photo" class="pr-6 pb-8" type="file" accept="image/*" :label="'Front photo'" />
          <file-input v-model="form.back_photo" :error="form.errors.back_photo" class="pr-6 pb-8" type="file" accept="image/*" :label="'Back photo'" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Store and create next card</loading-button>
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
import FileInput from '@/Shared/FileInput'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    FileInput,
  },
  layout: Layout,
  data() {
    return {
      form: this.$inertia.form({
        main_subject: null,
        subject: null,
        front: null,
        back: null,
        front_photo: null,
        back_photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/cards', {
        onSuccess: () => {
          this.form.front = null
          this.form.back = null
          this.front_photo = null
          this.back_photo = null
          this.$refs.front.focus()
        },
      })
    },
  },
}
</script>
