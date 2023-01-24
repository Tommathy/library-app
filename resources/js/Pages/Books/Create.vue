<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";

// Define the properties for vue js to use in the template
defineProps({
    message: String,
});

// Create a form object specifying the data that will be used and stored
const form = useForm({
    title: '',
    author: '',
    publication_year: '',
    number_of_pages: '',
});

// Create a on submit handler
const submit = () => {
    // Post the form data to the specified resource
    form.post(route('books.store'));
};
</script>

<template>
    <Head title="Books - Create"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Books - Add
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                        <div v-if="message"
                             class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                             role="alert">
                            <div class="flex">
                                <div>
                                    <p class="text-sm">{{ message }}</p>
                                </div>
                            </div>
                        </div>
                        <form name="createForm" @submit.prevent="submit">
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <Label for="title" value="Title"/>

                                    <Input
                                        id="title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.title"
                                        autofocus/>
                                    <span class="text-red-600" v-if="form.errors.title">
                                        {{ form.errors.title }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="author" value="Author"/>

                                    <TextInput
                                        id="author"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.author"/>
                                    <span class="text-red-600" v-if="form.errors.author">
                                        {{ form.errors.author }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="publication_year" value="Publication Year"/>

                                    <TextInput
                                        id="publication_year"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.publication_year"/>
                                    <span class="text-red-600" v-if="form.errors.publication_year">
                                        {{ form.errors.publication_year }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="publication_year" value="Number Of Pages"/>

                                    <TextInput
                                        id="number_of_pages"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.number_of_pages"/>
                                    <span class="text-red-600" v-if="form.errors.number_of_pages">
                                        {{ form.errors.number_of_pages }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="px-6 py-2 font-bold text-white bg-green-500 rounded">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
