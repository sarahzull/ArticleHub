<template>
  <button
    :class="[
      'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150',
      {
        'bg-gray-800 text-white': !disabled,
        'bg-gray-300 text-gray-500 cursor-not-allowed': disabled,
      },
    ]"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
