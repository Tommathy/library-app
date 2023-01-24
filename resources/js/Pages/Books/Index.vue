<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link,} from '@inertiajs/vue3';
import Button from '@/Components/PrimaryButton.vue';
import Book from "@/Components/Book.vue";

// Define the properties for vue js to use in the template
defineProps({
    books: Array
});
</script>

<template>
    <Head title="Books"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Books</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="flex justify-between">
                        <div class="p-6 text-gray-900 dark:text-gray-100">Most recently published books</div>

                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <Link :href="route('books.create')" type="button">
                                <Button>Add Book</Button>
                            </Link>
                        </div>
                    </div>

                    <ul v-if="books" class="flex flex-col bg-gray-100 dark:bg-gray-800 p-4">
                        <li v-for="book in books" class="border-gray-100 flex flex-row mb-2">
                            <Book :book="book"/>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
