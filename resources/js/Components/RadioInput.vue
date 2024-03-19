<template>
  <div class="flex">
    <label class="flex items-center">
      <input
        type="radio"
        :name="name"
        :value="value"
        v-model="selectedValue"
        class="w-5 h-5 text-blue-600 form-radio"
      />
      <span class="ml-2 text-base">{{ label }}</span>
    </label>
  </div>
</template>

<script>
export default {
  props: {
    name: String,
    value: String,
    label: String,
    checked: Boolean,
  },
  data() {
    return {
      selectedValue: null,
    };
  },
  watch: {
    selectedValue() {
      this.$emit('update:modelValue', this.selectedValue);
    },
  },
  created() {
    this.selectedValue = this.checked ? this.value : null;
  },
};
</script>