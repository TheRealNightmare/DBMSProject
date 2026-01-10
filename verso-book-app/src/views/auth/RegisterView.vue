<template>
  <div
    class="min-h-screen bg-verso-cream flex flex-col items-center justify-center p-6"
  >
    <div
      class="w-full max-w-sm bg-white p-8 rounded-2xl shadow-lg md:shadow-none md:bg-transparent"
    >
      <h1 class="text-3xl font-bold text-verso-dark mb-1 text-center">
        Registration
      </h1>
      <p class="text-center text-verso-blue font-medium mb-8">Be a worm</p>

      <form class="space-y-4">
        <BaseInput label="Username" />
        <BaseInput label="Email" />
        <BaseInput label="Password" type="password" />
        <BaseInput label="Confirm Password" type="password" />

        <div class="pt-4">
          <BaseButton class="w-full py-3">Sign up</BaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import BaseInput from "@/components/ui/BaseInput.vue";
import BaseButton from "@/components/ui/BaseButton.vue";
</script>
