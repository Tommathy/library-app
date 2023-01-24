<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link,} from '@inertiajs/vue3';
import Button from '@/Components/PrimaryButton.vue';

// Define the properties for vue js to use in the template
defineProps({
    books: Array,
    totalBooks: Number,
    totalBooksLoaned: Number,
})
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col gap-4">
                    <h3 class="text-gray-900 dark:text-gray-100 text-2xl font-black">The library has {{totalBooks}} books</h3>
                    <p class="text-gray-900 dark:text-gray-100">There are {{totalBooksLoaned}} books on loan</p>

                    <div class="flex gap-3">
                        <Link :href="route('books.index')" type="button">
                            <Button>Show All Books</Button>
                        </Link>
                        <Link :href="route('books.create')" type="button">
                            <Button>Add Book</Button>
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
