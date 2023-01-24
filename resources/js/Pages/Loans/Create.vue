<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";

// Define the properties for vue js to use in the template
defineProps({
    message: String,
    book: {
        type: Object,
        default: () => ({
            id: Number,
            title: String,
            author: String,
            publication_year: Number,
            number_of_pages: Number,
        }),
    },
});

// Create a form object specifying the data that will be used and stored
const form = useForm({
    book_id: usePage().props.book.id,
    borrower_name: null,
    loan_start_date: null,
    loan_end_date: null,
});

// Create a on submit handler
const submit = () => {
    // Post the form data to the specified resource
    form.post(route('books.loans.store', { 'book': usePage().props.book }));
};
</script>

<template>
    <Head title="Loans - Create"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Loans - Add
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
                                    <Label for="book" value="Book"/>

                                    <Input
                                        id="book"
                                        type="text"
                                        class="mt-1 block w-full"
                                        disabled
                                        v-bind:value="book.title"
                                        autofocus/>
                                    <span class="text-red-600" v-if="form.errors.book">
                                        {{ form.errors.book }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="borrower_name" value="Borrower Name"/>

                                    <TextInput
                                        id="borrower_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.borrower_name"
                                        autofocus/>
                                    <span class="text-red-600" v-if="form.errors.borrower_name">
                                        {{ form.errors.borrower_name }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="loan_start_date" value="Loan Start Date"/>

                                    <TextInput
                                        id="loan_start_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.loan_start_date"
                                        autofocus/>
                                    <span class="text-red-600" v-if="form.errors.loan_start_date">
                                        {{ form.errors.loan_start_date }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <Label for="loan_end_date" value="Loan End Date"/>

                                    <TextInput
                                        id="loan_end_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.loan_end_date"
                                        autofocus/>
                                    <span class="text-red-600" v-if="form.errors.loan_end_date">
                                        {{ form.errors.loan_end_date }}
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
