<template>
  <div class="p-4">
    <button @click="$router.back()" class="mb-4">← Back</button>
    <h1 class="text-2xl font-bold">Book Details</h1>
    <p class="text-gray-500">Book ID: {{ $route.params.id }}</p>
    </div>
</template>