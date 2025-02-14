<template>
  <div class="bg-gray-100 select-none text-neutral-700 h-screen">
    <WindowControls @show-terminal="toggleLogs" />
    <div class="container mx-auto px-4 pb-4 pt-32">
      <div class="grid grid-cols-1 gap-6">
        <LaravelCard :progress="laravelProgress" @terminal="toggleLogs" />
      </div>

      <pre
        v-bind:class="{ 'h-[0px]': !showLogs, 'h-[250px]': showLogs }"
        id="logs"
        class="fixed bottom-0 left-0 w-full bg-neutral-800 text-sm text-neutral-50 break-all whitespace-pre-wrap max-h-[250px] transition-all duration-300 h-[0px] overflow-y-scroll"
        :class="{ 'h-[250px] p-4': showLogs }"
        >{{ logs }}</pre
      >
    </div>
  </div>
</template>

<script>
import WindowControls from "./components/WindowControls.vue";
import LaravelCard from "./components/LaravelCard.vue";

export default {
  components: {
    WindowControls,
    LaravelCard,
  },
  data() {
    return {
      showLaravelForm: false,
      showLogs: false,
      logs: "",
      laravelProgress: 0,
    };
  },
  methods: {
    sendNotification(title, body) {
      const options = {
        body: body,
        icon: "icon.png",
      };

      new Notification(title, options);
    },

    toggleLogs() {
      this.showLogs = !this.showLogs;
    },
  },
  mounted() {
    window.electron.on("laravel-creation-log", (data) => {
      this.logs += data;
    });
    window.electron.on("laravel-creation-error", (data) => {
      this.logs += `${data}\n`;

      // if (data.includes("project at")) {
      //   this.laravelProgress = 10;
      // } else if (data.includes("Installing dependencies")) {
      //   this.laravelProgress = 40;
      // } else if (data.includes("Locking")) {
      //   this.laravelProgress = 60;
      // } else if (data.includes("Generating optimized autoload files")) {
      //   this.laravelProgress = 90;
      // } else if (data.includes("php artisan key:generate")) {
      //   this.laravelProgress = 100;
      // }
    });

    window.electron.on("laravel-creation-success", (data) => {
      document.getElementById(
        "logs"
      ).innerHTML += `<span class="w-full py-2 bg-green-500 text-white text-center">Your project has been created!</span>`;

      this.sendNotification(
        "Laravel Project Created",
        "Your project has been created!"
      );
    });
  },
};
</script>
