<template>
  <div
    class="border-2 border-red-400 bg-white transition-all duration-300 ease-in-out rounded-xl flex flex-col items-center justify-center relative text-center overflow-hidden"
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

    <LaravelForm
      @terminal="this.$emit('terminal')"
      class="px-6 transition-all duration-300 ease-in-out"
      v-bind:class="{
        'h-[0px] overflow-y-hidden': !showLaravelForm,
        'h-[100%] overflow-y-hidden': showLaravelForm,
      }"
    />

    <button
      @click="showLaravelForm = !showLaravelForm"
      class="w-full flex cursor-pointer transition-all duration-200 ease-in-out items-center justify-center py-2 bg-red-500 mt-4 group rounded-b-lg"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-8 text-white group-hover:text-white transition-all duration-200 ease-in-out"
        v-bind:class="{
          'rotate-180': !showLaravelForm,
        }"
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
