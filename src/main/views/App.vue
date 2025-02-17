<template>
  <div class="bg-gray-100 select-none text-neutral-700 min-h-screen h-full">
    <WindowControls @show-terminal="toggleLogs" />
    <div class="mx-auto px-4 pb-4 pt-32">
      <div class="grid grid-cols-1 gap-6">
        <LaravelCard @terminal="toggleLogs" />
      </div>

      <pre
        v-bind:class="{ 'h-[0px]': !showLogs, 'h-[250px]': showLogs }"
        id="logs"
        class="fixed bottom-0 select-text left-0 w-full bg-neutral-800 text-sm text-neutral-50 break-all whitespace-pre-wrap max-h-[250px] transition-all duration-300 h-[0px] overflow-y-scroll"
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
      this.logs += `${data}\n`;
      document.getElementById("logs").scrollTop =
        document.getElementById("logs").scrollHeight;
    });
    window.electron.on("laravel-creation-error", (data) => {
      this.logs += `${data}\n`;
      document.getElementById("logs").scrollTop =
        document.getElementById("logs").scrollHeight;
    });

    window.electron.on("laravel-creation-success", (data) => {
      document.getElementById(
        "logs"
      ).innerHTML += `<span class="w-full py-2 bg-green-500 text-white text-center">Your project has been created!</span>`;

      document.getElementById("logs").scrollTop =
        document.getElementById("logs").scrollHeight;

      /*FIXME:
        - Some times the notification is called more then once
        */
      this.sendNotification(
        "Laravel Project Created",
        "Your project has been created!"
      );
    });
  },
};
</script>
