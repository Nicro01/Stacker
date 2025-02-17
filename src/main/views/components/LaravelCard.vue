<template>
  <div
    class="card bg-white transition-all duration-300 ease-in-out rounded-xl flex flex-col items-center justify-center relative text-center"
    v-bind:class="{
      'pb-6': !showLaravelForm,
      'pb-0': showLaravelForm,
    }"
  >
    <div class="flex justify-center pt-6">
      <img
        draggable="false"
        src="https://laravel.com/img/logomark.min.svg"
        alt="Laravel Logo"
        class="w-20 h-20"
      />
    </div>
    <h2 class="mt-4 text-xl font-semibold">Laravel</h2>
    <p class="mt-2 text-gray-600">Create a new Laravel project</p>
    <button
      v-if="!showLaravelForm"
      @click="showLaravelForm = !showLaravelForm"
      class="absolute rounded-full right-5 bottom-5 cursor-pointer bg-neutral-800 size-12 flex items-center justify-center transition-all duration-300 ease-in-out"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-6 fill-neutral-800"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"
        />
      </svg>
    </button>

    <LaravelForm
      @terminal="this.$emit('terminal')"
      class="px-6"
      v-bind:class="{
        'h-[0px] overflow-y-hidden': !showLaravelForm,
        'min-h-[200px] pb-12 mt-6 overflow-y-hidden': showLaravelForm,
      }"
    />

    <button
      v-if="showLaravelForm"
      @click="showLaravelForm = !showLaravelForm"
      v-bind:class="{
        'opacity-0': !showLaravelForm,
        'opacity-100': showLaravelForm,
      }"
      class="w-full flex cursor-pointer transition-all duration-200 ease-in-out items-center justify-center py-2 border-t border-neutral-800 hover:bg-neutral-800 group rounded-b-xl"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-8 text-neutral-800 group-hover:text-white"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="m4.5 15.75 7.5-7.5 7.5 7.5"
        />
      </svg>
    </button>
  </div>
</template>
<script>
import LaravelForm from "./LaravelForm.vue";

export default {
  components: {
    LaravelForm,
  },

  data() {
    return {
      showLaravelForm: false,
    };
  },
  methods: {
    openLaravelForm() {
      this.showLaravelForm = !this.showLaravelForm;
    },
    async selectFolder() {
      const folderPath = await window.electron.invoke("select-folder");
      return folderPath;
    },
  },
};
</script>
