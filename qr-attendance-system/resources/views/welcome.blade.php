<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">QR Attendance System</h1>
                <p class="text-xl text-gray-600">Modern attendance tracking for educational institutions</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome to QR Attendance System</h2>
                    <p class="text-gray-600 mb-6">
                        A comprehensive web application for tracking student attendance using QR codes. 
                        Designed specifically for tutoring services and educational institutions.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">For Administrators</h3>
                        <p class="text-blue-700">Manage courses, create QR sessions, and view attendance reports.</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">For Students</h3>
                        <p class="text-green-700">Check in using QR codes and view your attendance history.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if(file_exists(storage_path('installed')))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">System is installed and ready to use!</span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('login') }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                Register
                            </a>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-yellow-800 font-medium">System needs to be installed</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('install.index') }}" 
                           class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Start Installation
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} QR Attendance System. Built with Laravel.</p>
            </div>
        </div>
    </div>
</body>
</html>
