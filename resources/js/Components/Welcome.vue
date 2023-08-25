<script setup lang="ts">
import {Database} from "@/types";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import {Link,useForm} from "@inertiajs/vue3";


let clickedButton = null;

const form = useForm({
    host: null,
    port: '3306',
    name: null,
    username: null,
    password: null,
});

const submit = () => {
    console.log(form, clickedButton);
    if (clickedButton === 'add') {
        addDatabase();
    } else if (clickedButton === 'test') {
        testConnection();
    }
};

const addDatabase = () => {
    form.transform(data => ({
        ...data,
    })).post(route('database.store'), {
        onFinish: () => {
            form.reset();
            clickedButton = null;
        },
    });
}

const testConnection = () => {
    form.transform(data => ({
        ...data,
    })).post(route('database.test-connection'), {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            clickedButton = null;
        },
    });
}

defineProps<{
    databases: Database[];
}>();
</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                Welcome to your SQL-GPT application!
            </h1>

            <p class="mt-6 text-gray-500 leading-relaxed">
                Here you can add your databases and ask for reports in natural language. SQL-GPT will generate the
                needed SQL queries and fetch reports for you.<br/>
                You can also save your reports and share them with your colleagues. <br/>
                Usage: <br/>
                1. Add your database <br/>
                2. Ask for a report in natural language ex. <cite class="font-mono">Best performing product of last 5
                weeks</cite> <br/>
                3. Save your report <br/>
                4. Share your report with your colleagues <br/>
            </p>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">
            <div v-for="database in databases" :key="database.id">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500"><path stroke-linecap="round" stroke-linejoin="round" d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64"></path></svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        <span >{{database.host}}:{{database.port}}</span>
                    </h2>
                    <h3 class="ml-3 text-2xl  text-gray-600">{{database.name}}</h3>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Short description of the database. And some more optional text.
                </p>

                <p class="mt-4 text-sm">
                    <Link :href="route('database.show',{database:database.id})" class="inline-flex items-center font-semibold text-indigo-700">
                        Go to Reports

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="ml-1 w-5 h-5 fill-indigo-500">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </Link>
                </p>
            </div>

            <div v-show="databases.length===0">
                <div class="grid items-center">
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">
                        <a href="https://laracasts.com">Start by adding Database Connection</a>
                    </h2>
                </div>

                <!--                <p class="mt-4 text-gray-500 text-sm leading-relaxed"></p>-->
                <div class="grid">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="host" value="Host"/>
                                <TextInput
                                    id="host"
                                    v-model="form.host"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete="host"
                                />
                                <InputError class="mt-2" :message="form.errors.host"/>
                            </div>
                            <div>
                                <InputLabel for="port" value="Port"/>
                                <TextInput
                                    id="port"
                                    v-model="form.port"
                                    type="number"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="port"
                                />
                                <InputError class="mt-2" :message="form.errors.port"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="username" value="Username"/>
                                <TextInput
                                    id="username"
                                    v-model="form.username"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.username"/>
                            </div>
                            <div>
                                <InputLabel for="password" value="Password"/>
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="password"
                                />
                                <InputError class="mt-2" :message="form.errors.password"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="name" value="Database"/>
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="database"
                                />
                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>
                        </div>
                        <div v-show="$page.props.flash.status!=undefined" class="grid grid-cols-2 gap-6 mt-3">
                            <p v-show="!$page.props.flash.status" class="text-red-600">{{ $page.props.flash.message }}</p>
                            <p v-show="$page.props.flash.status" class="text-green-600">{{ $page.props.flash.message }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-6 mt-3">
                            <InputError class="mt-2" :message="$page.props.error"/>
                            <div class="grid grid-cols-2 gap-6">
                                <button type="submit" @click="clickedButton = 'test'"
                                        class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Test Connection
                                </button>
                                <button type="submit" @click="clickedButton = 'add'"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Add Database
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</template>
