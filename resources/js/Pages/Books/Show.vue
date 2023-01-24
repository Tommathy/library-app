<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import Button from '@/Components/PrimaryButton.vue';

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
    canBeLoaned: Boolean,
});
</script>

<template>
    <Head title="Books - Show"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Books - Show</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="message"
                     class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                     role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ message }}</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow dark:shadow-none sm:rounded-lg">
                    <div class="flex justify-between content-center gap-3 px-4 py-3 sm:px-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Book Information</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-200">Property of +24 Marketing Ltd.</p>
                        </div>
                        <Link v-if="canBeLoaned" :href="route('books.loans.create', book)">
                            <Button type="button">New Loan</Button>
                        </Link>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <dl>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    {{ book.title }}
                                </dd>
                            </div>
                            <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Author</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    {{ book.author }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Published</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    {{ book.publication_year }}
                                </dd>
                            </div>
                            <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Number of pages</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    {{ book.number_of_pages }}
                                </dd>
                            </div>
                            <div class="bg-white dark:bg-gray-700 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Loan History</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    <ul v-if="book.loans.length" role="list"
                                        class="divide-y divide-gray-200 rounded-md border border-gray-200 dark:border-gray-800">
                                        <li v-for="loan in book.loans"
                                            class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                                            <div class="flex w-0 flex-1 items-center">
                                                <span class="ml-2 w-0 flex-1 truncate">Loaned to {{
                                                        loan.borrower_name
                                                    }} on {{ loan.loan_start_date }}</span>
                                                <p v-if="loan.loan_end_date">Returned on: {{loan.loan_end_date}}</p>
                                                <Link v-if="loan.loan_end_date === null" :href="route('books.loans.update', [book, loan] )" method="patch" type="button" :data="{returnBook: true}">
                                                    Return
                                                </Link>
                                            </div>
                                        </li>
                                    </ul>
                                    <p v-else>No loans</p>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
