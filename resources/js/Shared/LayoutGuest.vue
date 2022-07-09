<template>
  <div>
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:flex-shrink-0 bg-indigo-900">
          <div class="flex items-center justify-between px-6 py-4">
            <Link class="mt-1" href="/login">
              <logo class="fill-white" width="120" height="120" />
            </Link>
            <p class="m-4 p-4 text-xl text-white">Cards by <a class="text-indigo-300 hover:text-indigo-100" href="https://michaeljob.ch">michaeljob.ch</a></p>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Logo,
    Link,
  },
  props: {
    auth: Object,
  },
}
</script>
