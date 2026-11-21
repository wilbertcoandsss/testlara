<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Kartu Manajemen Passkey -->
<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-6">
    <div class="max-w-xl">
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">Passkeys (Biometrik / Kunci Keamanan)</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tambahkan Passkey untuk login cepat menggunakan sidik jari, pengenalan wajah, atau PIN perangkat tanpa password.
                </p>
            </header>

            @if (session('status') === 'passkey-created')
                <p class="mt-2 text-sm text-green-600 font-medium">Passkey berhasil ditambahkan!</p>
            @endif

            @error('passkey')
                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror

            <!-- Form Tambah Passkey -->
            <form id="register-passkey-form" method="POST" action="{{ route('passkeys.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <x-input-label for="passkey-name" value="Nama Perangkat (Contoh: Laptop Kerja, iPhone)" />
                    <x-text-input id="passkey-name" name="name" type="text" class="mt-1 block w-full" required />
                </div>

                <!-- Input tersembunyi untuk menampung hasil JSON dari sensor biometrik -->
                <input type="hidden" name="passkey" id="passkey-credential">

                <x-primary-button type="submit">
                    Tambah Passkey Baru
                </x-primary-button>
            </form>

            <!-- Daftar Passkey yang Terdaftar -->
            @if (auth()->user()->passkeys->isNotEmpty())
                <div class="mt-8 border-t pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Perangkat Terdaftar:</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach (auth()->user()->passkeys as $passkey)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $passkey->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        Terakhir digunakan: {{ $passkey->last_used_at ? $passkey->last_used_at->diffForHumans() : 'Belum pernah' }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('passkeys.destroy', $passkey->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
</div>

<!-- Load SimpleWebAuthn langsung via CDN (Tanpa Node.js) -->
<script src="https://unpkg.com/@simplewebauthn/browser@10.0.0/dist/bundle/index.umd.min.js"></script>
<script>
    document.getElementById('register-passkey-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;

        try {
            // 1. Ambil challenge registrasi dari server
            const response = await fetch("{{ route('passkeys.register-options') }}", {
                headers: { 'Accept': 'application/json' }
            });
            const options = await response.json();

            // 2. Munculkan prompt biometrik / PIN di browser
            const passkey = await SimpleWebAuthnBrowser.startRegistration(options);

            // 3. Masukkan hasil respons biometrik ke input hidden dan kirim form
            document.getElementById('passkey-credential').value = JSON.stringify(passkey);
            form.submit();
        } catch (error) {
            console.error(error);
            alert('Pendaftaran Passkey dibatalkan atau tidak didukung oleh browser ini.');
        }
    });
</script>
        </div>
    </div>
</x-app-layout>
