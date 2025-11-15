{{-- resources/views/install-instructions.blade.php --}}
@extends('layouts.school')

@section('title', 'Install App - WayUp')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-8 text-white text-center">
                <img src="/WayUp.png" alt="WayUp" class="h-20 w-20 mx-auto mb-4 rounded-xl shadow-lg">
                <h1 class="text-3xl font-bold mb-2">Install {{ $school->name }} App</h1>
                <p class="text-white/90">Quick access from your home screen</p>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- iOS Instructions -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="ri-apple-line mr-2"></i> iPhone/iPad
                        </h2>
                        <ol class="space-y-3 text-gray-700">
                            <li class="flex">
                                <span class="font-bold mr-2">1.</span>
                                <span>Open this page in Safari</span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">2.</span>
                                <span>Tap the share button <i class="ri-share-box-line"></i></span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">3.</span>
                                <span>Scroll down and tap "Add to Home Screen"</span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">4.</span>
                                <span>Tap "Add" to install</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Android Instructions -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="ri-android-line mr-2"></i> Android
                        </h2>
                        <ol class="space-y-3 text-gray-700">
                            <li class="flex">
                                <span class="font-bold mr-2">1.</span>
                                <span>Open this page in Chrome</span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">2.</span>
                                <span>Tap the menu button <i class="ri-more-2-line"></i></span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">3.</span>
                                <span>Tap "Add to Home screen"</span>
                            </li>
                            <li class="flex">
                                <span class="font-bold mr-2">4.</span>
                                <span>Tap "Add" to install</span>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                    <p class="text-center text-gray-700">
                        <i class="ri-information-line text-blue-600 mr-2"></i>
                        The app will appear on your home screen just like a native app!
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <button onclick="window.close()" class="btn-secondary">
                        <i class="ri-close-line mr-2"></i>
                        Close Instructions
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
