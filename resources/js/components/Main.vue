<script>
import { defineComponent } from 'vue';
import AppNavbar from '@/components/AppNavbar.vue';
import AppFooter from '@/components/AppFooter.vue';
import AppList from '@/components/AppList.vue';

export default defineComponent({
  name: 'Main',
  components: {
    AppNavbar,
    AppFooter,
    AppList
  },
  setup() {

    return {};
  }
});
</script>

<template>
  <v-app>
    <app-navbar></app-navbar>
    <v-main>
      <v-container>
        <app-list></app-list>
      </v-container>
    </v-main>
    <app-footer></app-footer>
  </v-app>
</template>
