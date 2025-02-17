<template>
  <div class="w-full transition-all duration-300 ease-in-out">
    <div class="grid grid-cols-4 gap-6 py-10">
      <div
        @click="selectStack(0)"
        :class="{
          'border-2 border-red-400 scale-105 bg-red-400/20':
            selectedStack === 0,
          'border-2 border-white': selectedStack !== 0,
        }"
        class="card h-[170px] trasition-all cursor-pointer hover:scale-105 duration-200 ease-in-out p-6 rounded-lg flex flex-col items-center justify-center gap-2"
      >
        <div class="flex justify-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-16"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"
            />
          </svg>
        </div>
        <h2 class="mt-4 text-xl font-semibold">None</h2>
      </div>

      <div
        v-for="(stack, index) in stacks"
        @click="selectStack(index + 1)"
        :class="{
          'border-2 border-red-400 scale-105 bg-red-400/20':
            selectedStack === index + 1,
          'border-2 border-white': selectedStack !== index + 1,
        }"
        class="card h-[170px] trasition-all cursor-pointer hover:scale-105 duration-200 ease-in-out p-6 rounded-lg flex flex-col items-center justify-center gap-2"
      >
        <div class="flex justify-center items-center">
          <img
            draggable="false"
            :src="stack.image"
            alt="Laravel Logo"
            :class="stack.imageClass"
          />
        </div>
        <h2 class="mt-4 text-xl font-semibold">{{ stack.name }}</h2>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="text-start">
      <div class="space-y-4">
        <div v-if="selectedStack === 1">
          <label class="inline-flex items-center cursor-pointer">
            <input
              type="checkbox"
              name="auth"
              value="true"
              v-model="auth"
              class="sr-only peer"
            />
            <div
              class="relative w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
            ></div>
            <span class="ms-3 text-sm font-medium text-gray-900"
              >Auth Template</span
            >
          </label>
        </div>

        <div>
          <label
            for="projectPath"
            class="block text-sm font-medium text-gray-700"
            >Project Path:</label
          >
          <div class="flex gap-2">
            <input
              type="text"
              v-model="projectPath"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
            />
            <button
              type="button"
              @click="selectFolder"
              class="border-2 border-neutral-800 cursor-pointer hover:bg-neutral-800 hover:text-white flex items-center justify-center px-4 mt-1 rounded-md transition-all duration-300 ease-in-out"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="#fff"
                class="size-6 fill-neutral-800"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                />
              </svg>
            </button>
          </div>
        </div>
        <div>
          <label
            for="projectName"
            class="block text-sm font-medium text-gray-700"
            >Project Name:</label
          >
          <input
            type="text"
            v-model="projectName"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
          />
        </div>
        <div>
          <button
            type="submit"
            class="w-full border-2 border-neutral-800 cursor-pointer hover:bg-neutral-800 hover:text-white flex items-center justify-center px-4 py-2 rounded-md transition-all duration-300 ease-in-out"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-6 fill-black"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"
              />
            </svg>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      projectPath: "",
      projectName: "",
      selectedStack: 0,
      stacks: [
        {
          name: "TALL",
          description: "Tailwind, Alpine.js, Laravel, Livewire",
          imageClass: "w-16",
          image:
            "https://i.postimg.cc/cLDzs6fZ/8582affb-e65b-43ee-8fc4-0f956ff592da.png",
        },
        {
          name: "VILT",
          description: "Vue, Inertia, Laravel, Tailwind",
          imageClass: "w-16",
          image:
            "https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/2367px-Vue.js_Logo_2.svg.png",
        },
        {
          name: "LIR",
          description: "Vue, Inertia, Laravel, Tailwind",
          imageClass: "w-16",
          image: "https://laravext.dev/images/logo/laravext.png",
        },
      ],
      auth: false,
    };
  },
  methods: {
    async selectFolder() {
      const folderPath = await window.electron.invoke("select-folder");
      console.log(folderPath);
      if (folderPath) {
        this.projectPath = folderPath;
      }
    },
    handleSubmit() {
      console.log(this.projectPath, this.projectName);

      this.$emit("terminal");

      window.electron.send("create-laravel-project", {
        projectPath: this.projectPath,
        projectName: this.projectName,
        stack: this.selectedStack,
        auth: this.auth,
      });
    },

    selectStack(index) {
      this.selectedStack = index;
    },
  },
};
</script>
