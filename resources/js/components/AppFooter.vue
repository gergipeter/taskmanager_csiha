<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
  name: 'AppFooter',
  setup() {
    const currentYear = ref(new Date().getFullYear());

    return {
      currentYear,
    };
  },
});
</script>

<template>
  <v-footer app color="primary" dark class="footer-style">
    <span id="footer-text">&copy; {{ currentYear }} Task Manager App - CSIHA Test Project</span>
  </v-footer>
</template>
