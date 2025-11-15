<div id="pwaInstallContainer" class="hidden">
    <button id="pwaInstallBtn"
            onclick="installPWA()"
            class="hidden md:flex fixed bottom-6 right-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 items-center space-x-2 z-50 animate-pulse-slow">
        <i class="ri-download-cloud-line text-xl"></i>
        <span class="font-semibold">Install App</span>
    </button>

    <div id="pwaInstallBanner" class="md:hidden fixed bottom-0 left-0 right-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 transform translate-y-full transition-transform duration-500 z-50 shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="/WayUp.png" alt="WayUp" class="w-12 h-12 rounded-lg shadow-lg">
                <div>
                    <p class="font-bold">Install Scholder App</p>
                    <p class="text-sm opacity-90">Quick access from home screen</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="installPWA()" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-bold hover:bg-gray-100 shadow-lg">
                    Install
                </button>
                <button onclick="dismissInstallBanner()" class="text-white/80 hover:text-white p-2">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="iosInstallModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full animate-slideUp">
            <div class="text-center mb-4">
                <i class="ri-apple-line text-5xl text-gray-800"></i>
                <h3 class="text-xl font-bold mt-2">Install on iPhone/iPad</h3>
            </div>
            <ol class="space-y-3 text-left text-gray-700">
                <li class="flex items-start">
                    <span class="font-bold mr-2">1.</span>
                    <span>Tap the share button <i class="ri-share-box-line"></i> at the bottom of Safari</span>
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">2.</span>
                    <span>Scroll down and tap "Add to Home Screen"</span>
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">3.</span>
                    <span>Tap "Add" to install</span>
                </li>
            </ol>
            <button onclick="closeiOSModal()" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg font-semibold">
                Got it!
            </button>
        </div>
    </div>
</div>
