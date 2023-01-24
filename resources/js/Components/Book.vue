<script setup>
import {Link} from '@inertiajs/vue3';
import Button from '@/Components/PrimaryButton.vue';

const props = defineProps({
    book: {
        type: Object,
        default: () => ({
            id: Number,
            title: String,
            author: String,
            publication_year: Number,
            number_of_pages: Number,
        }),
    }
})

</script>

<template>

    <div
        class="select-none cursor-pointer bg-white dark:bg-gray-700 rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out hover:shadow-lg">
        <img class="flex flex-col rounded-md h-10 bg-gray-300 aspect-[7/10] justify-center items-center mr-4" alt="Book cover"/>
        <div class="flex-1 pl-1 mr-16">
            <div class="font-medium text-gray-900 dark:text-gray-100">{{ props.book.title }}</div>
            <div class="text-gray-600 dark:text-gray-300 text-sm">{{ book.author }}</div>
            <div class="text-gray-600 dark:text-gray-300 text-xs">Published:
                {{ book.publication_year }}
            </div>
        </div>
        <Link :href="route('books.show', book)" class="pr-2">
            <Button>View</Button>
        </Link>
        <Link :href="route('books.edit', book)" class="pr-2">
            <Button>Edit</Button>
        </Link>
    </div>

</template>

<style scoped>

</style>
