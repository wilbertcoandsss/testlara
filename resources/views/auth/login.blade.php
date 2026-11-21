<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="my-4 flex items-center justify-between">
        <hr class="w-full border-gray-300">
        <span class="px-2 text-xs text-gray-500 uppercase">Atau</span>
        <hr class="w-full border-gray-300">
    </div>

    <!-- Form tersembunyi khusus untuk mengirim hasil biometrik ke route bawaan Spatie -->
    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    <input type="hidden" name="start_authentication_response" id="start-auth-response">
    </form>

    <button type="button" id="btn-login-passkey" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        🔑 Masuk dengan Passkey / Biometrik
    </button>

    <script src="https://unpkg.com/@simplewebauthn/browser@10.0.0/dist/bundle/index.umd.min.js"></script>
    <script>
        document.getElementById('btn-login-passkey').addEventListener('click', async function () {
            try {
                // 1. Minta challenge options dari Spatie
                const optionsResponse = await fetch("{{ route('passkeys.authentication_options') }}", {
                    headers: { 'Accept': 'application/json' }
                });

                if (!optionsResponse.ok) {
                    throw new Error('Gagal memuat opsi autentikasi Passkey.');
                }

                const options = await optionsResponse.json();

                // 2. Munculkan dialog Touch ID / Biometrik di macOS
                const authResponse = await SimpleWebAuthnBrowser.startAuthentication(options);

                // 3. SETELAH TOUCH ID SUKSES: Kirim sinyal login ke backend
                const loginResponse = await fetch("{{ route('login.passkey-success') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        auth_data: authResponse
                    })
                });

                const data = await loginResponse.json();

                if (loginResponse.ok && data.status === 'success') {
                    // Redirect langsung ke daftar posts
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Gagal login setelah verifikasi biometrik.');
                }

            } catch (error) {
                console.error(error);
                // Jika user menekan cancel pada prompt Touch ID atau sensor gagal
                alert('Autentikasi Touch ID dibatalkan atau tidak dikenali.');
            }
        });
    </script>
</x-guest-layout>
