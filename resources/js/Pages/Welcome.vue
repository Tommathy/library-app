<script setup>
import {Head, Link} from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome"/>

    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">

        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="text-sm text-gray-700 dark:text-gray-500 underline">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1">

                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                        <div class="text-lg leading-7 font-semibold text-gray-900 dark:text-white">
                            Library Technical Test
                        </div>

                        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm flex flex-col gap-3">
                            <p>For this technical test, it has been assumed that there is only one copy of each
                                book. This means that a book can only be loaned out once and then must be
                                returned.</p>
                            <p>There is also a Book.php class in the project root as requested with the appropriate
                                properties and getter methods. There is also a library.sql in the project root to create
                                the schema specified.</p>
                            <p>This project was also designed with dark mode first so the light mode may have some
                                visual issues. So it is recommended that you view this project in dark mode.</p>
                            <p>To proceed, please register an account using the link in the top right. After which
                                will be redirected to the main library dashboard. From there you will be able to add
                                new books, edit books, check them out, and return them.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <a href="https://24marketing.co.uk/" class="ml-1 underline"> +24 Marketing </a>
                </div>

                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </div>
            </div>
        </div>
    </div>
</template>
