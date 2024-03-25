<script setup>
import { onMounted } from 'vue';

let urlParams = new URLSearchParams(window.location.search);
let url = urlParams.get('redirectUrl');
if (url) {
  window.location.href = url;
} else {
  console.error('No URL provided for redirection.');
}
</script>

<template>
  <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
      <div class="text-center">
        <p class="mb-4">Redirecting...</p>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
      </div>
  </div>

</template>