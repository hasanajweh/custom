<div id="pwaInstallContainer" class="hidden">
    <button id="pwaInstallBtn"
            onclick="installPWA()"
            class="hidden md:flex fixed bottom-6 right-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 items-center space-x-2 z-50 animate-pulse-slow">
        <i class="ri-download-cloud-line text-xl"></i>
        <span class="font-semibold">{{ __('messages.pwa.install_app') }}</span>
    </button>

    <div id="pwaInstallBanner" class="md:hidden fixed bottom-0 left-0 right-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 transform translate-y-full transition-transform duration-500 z-50 shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="/WayUp.png" alt="WayUp" class="w-12 h-12 rounded-lg shadow-lg">
                <div>
                    <p class="font-bold">{{ __('messages.pwa.install_title', ['name' => __('messages.app.name')]) }}</p>
                    <p class="text-sm opacity-90">{{ __('messages.pwa.install_description') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="installPWA()" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-bold hover:bg-gray-100 shadow-lg">
                    {{ __('messages.pwa.install') }}
                </button>
                <button onclick="dismissInstallBanner()" class="text-white/80 hover:text-white p-2">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="iosInstallModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mt-2">{{ __('messages.pwa.install_on_iphone') }}</h3>
            <ol class="list-decimal list-inside space-y-2 mt-4 text-gray-700">
                <li>{{ __('messages.pwa.tap_share_button') }} <i class="ri-share-box-line"></i> {{ __('messages.pwa.at_bottom_safari') }}</li>
                <li>{{ __('messages.pwa.scroll_add_home') }}</li>
                <li>{{ __('messages.pwa.tap_add_install') }}</li>
            </ol>
            <button onclick="document.getElementById('iosInstallModal').classList.add('hidden')" class="mt-6 w-full bg-purple-600 text-white py-3 rounded-lg font-bold">
                {{ __('messages.pwa.got_it') }}
            </button>
        </div>
    </div>
</div>

<script>
function installPWA() {
    if (window.deferredPrompt) {
        window.deferredPrompt.prompt();
        window.deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            }
            window.deferredPrompt = null;
        });
    } else {
        // Show iOS instructions
        document.getElementById('iosInstallModal').classList.remove('hidden');
    }
}

function dismissInstallBanner() {
    document.getElementById('pwaInstallBanner').classList.add('translate-y-full');
    localStorage.setItem('pwaBannerDismissed', 'true');
}
</script>

