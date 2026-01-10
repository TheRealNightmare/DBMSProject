<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div class="relative">
      <input 
        :type="inputType"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        class="w-full rounded-xl border border-gray-300 shadow-sm p-3 focus:ring-2 focus:ring-verso-blue focus:border-transparent outline-none transition"
      />
      <button 
        v-if="type === 'password'" 
        type="button"
        @click="showPassword = !showPassword"
        class="absolute right-3 top-3.5 text-gray-400 hover:text-verso-blue"
      >
        <component :is="showPassword ? EyeOff : Eye" class="w-5 h-5" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Eye, EyeOff } from 'lucide-vue-next';

const props = defineProps(['modelValue', 'label', 'type', 'placeholder']);
const emit = defineEmits(['update:modelValue']);

const showPassword = ref(false);
const inputType = computed(() => {
  if (props.type === 'password') return showPassword.value ? 'text' : 'password';
  return props.type || 'text';
});
</script>