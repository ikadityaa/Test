<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance System - Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div id="app" class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">QR Attendance System</h1>
                <p class="text-gray-600">Installation Wizard</p>
            </div>

            <!-- Progress Steps -->
            <div class="flex justify-center mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                             :class="currentStep >= 1 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                            Requirements
                        </span>
                    </div>
                    <div class="w-8 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                             :class="currentStep >= 2 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                            Permissions
                        </span>
                    </div>
                    <div class="w-8 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                             :class="currentStep >= 3 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                            Configuration
                        </span>
                    </div>
                    <div class="w-8 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                             :class="currentStep >= 4 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium" :class="currentStep >= 4 ? 'text-blue-600' : 'text-gray-500'">
                            Install
                        </span>
                    </div>
                </div>
            </div>

            <!-- Step 1: Requirements Check -->
            <div v-if="currentStep === 1" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">System Requirements</h2>
                <div v-if="loading" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Checking requirements...</p>
                </div>
                <div v-else>
                    <div class="space-y-3">
                        <div v-for="(met, requirement) in requirements" :key="requirement" 
                             class="flex items-center justify-between p-3 rounded-lg border"
                             :class="met ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
                            <span class="font-medium" :class="met ? 'text-green-800' : 'text-red-800'">
                                @{{ requirement }}
                            </span>
                            <div class="flex items-center">
                                <svg v-if="met" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg v-else class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <button @click="checkRequirements" 
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Recheck
                        </button>
                        <button @click="nextStep" 
                                :disabled="!allRequirementsMet"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Permissions Check -->
            <div v-if="currentStep === 2" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Directory Permissions</h2>
                <div v-if="loading" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Checking permissions...</p>
                </div>
                <div v-else>
                    <div class="space-y-3">
                        <div v-for="(writable, path) in permissions" :key="path" 
                             class="flex items-center justify-between p-3 rounded-lg border"
                             :class="writable ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
                            <span class="font-medium" :class="writable ? 'text-green-800' : 'text-red-800'">
                                @{{ path }}
                            </span>
                            <div class="flex items-center">
                                <svg v-if="writable" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg v-else class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <button @click="prevStep" 
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Previous
                        </button>
                        <button @click="nextStep" 
                                :disabled="!allPermissionsWritable"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Configuration -->
            <div v-if="currentStep === 3" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Application Configuration</h2>
                <form @submit.prevent="configure">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                            <input v-model="config.app_name" type="text" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                            <input v-model="config.app_url" type="url" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Connection</label>
                            <select v-model="config.db_connection" @change="onDbConnectionChange"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="sqlite">SQLite</option>
                                <option value="mysql">MySQL</option>
                            </select>
                        </div>
                    </div>

                    <!-- MySQL Configuration -->
                    <div v-if="config.db_connection === 'mysql'" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
                            <input v-model="config.db_host" type="text" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
                            <input v-model="config.db_port" type="text" required value="3306"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Name</label>
                            <input v-model="config.db_database" type="text" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
                            <input v-model="config.db_username" type="text" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
                            <input v-model="config.db_password" type="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="prevStep" 
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Previous
                        </button>
                        <button type="submit" :disabled="configuring"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            @{{ configuring ? 'Configuring...' : 'Configure' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Installation -->
            <div v-if="currentStep === 4" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Installation</h2>
                <div v-if="installing" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Installing QR Attendance System...</p>
                    <p class="text-sm text-gray-500 mt-1">This may take a few moments</p>
                </div>
                <div v-else>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Configuration Complete</h3>
                                <p class="text-sm text-green-700 mt-1">Your application is ready to be installed.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button @click="prevStep" 
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Previous
                        </button>
                        <button @click="install" 
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            Install Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="installationComplete" class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-green-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Installation Complete!</h2>
                <p class="text-gray-600 mb-6">Your QR Attendance System has been successfully installed.</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-800 mb-2">Default Login Credentials:</h3>
                    <p class="text-sm text-blue-700"><strong>Email:</strong> admin@qrattendance.com</p>
                    <p class="text-sm text-blue-700"><strong>Password:</strong> password</p>
                </div>
                <a href="/" class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Go to Application
                </a>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    currentStep: 1,
                    loading: false,
                    configuring: false,
                    installing: false,
                    installationComplete: false,
                    requirements: {},
                    permissions: {},
                    config: {
                        app_name: 'QR Attendance System',
                        app_url: window.location.origin,
                        db_connection: 'sqlite',
                        db_host: 'localhost',
                        db_port: '3306',
                        db_database: '',
                        db_username: '',
                        db_password: ''
                    }
                }
            },
            computed: {
                allRequirementsMet() {
                    return Object.values(this.requirements).every(met => met);
                },
                allPermissionsWritable() {
                    return Object.values(this.permissions).every(writable => writable);
                }
            },
            mounted() {
                this.checkRequirements();
            },
            methods: {
                async checkRequirements() {
                    this.loading = true;
                    try {
                        const response = await axios.get('/install/requirements');
                        this.requirements = response.data.requirements;
                    } catch (error) {
                        console.error('Error checking requirements:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                async checkPermissions() {
                    this.loading = true;
                    try {
                        const response = await axios.get('/install/permissions');
                        this.permissions = response.data.permissions;
                    } catch (error) {
                        console.error('Error checking permissions:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                async configure() {
                    this.configuring = true;
                    try {
                        await axios.post('/install/configure', this.config);
                        this.nextStep();
                    } catch (error) {
                        alert('Configuration failed: ' + (error.response?.data?.message || error.message));
                    } finally {
                        this.configuring = false;
                    }
                },
                async install() {
                    this.installing = true;
                    try {
                        await axios.post('/install/install');
                        this.installationComplete = true;
                    } catch (error) {
                        alert('Installation failed: ' + (error.response?.data?.message || error.message));
                    } finally {
                        this.installing = false;
                    }
                },
                nextStep() {
                    if (this.currentStep === 1) {
                        this.checkPermissions();
                    }
                    this.currentStep++;
                },
                prevStep() {
                    this.currentStep--;
                },
                onDbConnectionChange() {
                    if (this.config.db_connection === 'sqlite') {
                        this.config.db_host = '';
                        this.config.db_port = '';
                        this.config.db_database = '';
                        this.config.db_username = '';
                        this.config.db_password = '';
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>