<template>
  <div v-if="snackbar.isVisible" 
    class="snackbar text-gray-50 rounded py-3 px-2 w-64 min-w-max text-sm"
    :class="{
      'bg-green-500': snackbar.status === 'success',
      'bg-red-500': snackbar.status !== 'success'
    }"
  >
    {{ snackbar.text }}
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'SnackbarComponent',

  computed: {
    ...mapState(['snackbar'])
  }
}
</script>

<style>

</style>