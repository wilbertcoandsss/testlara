<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction;
use Spatie\LaravelPasskeys\Actions\StorePasskeyAction;
use Spatie\LaravelPasskeys\Support\Config;

class PasskeyController extends Controller
{
    /**
     * 1. Menghasilkan JSON Options untuk registrasi Passkey di browser
     */
    public function registerOptions(Request $request)
    {
        $action = Config::getAction('generate_passkey_register_options', GeneratePasskeyRegisterOptionsAction::class);

        // Menghasilkan opsi WebAuthn sebagai string JSON
        $options = $action->execute($request->user());

        // Simpan di session untuk divalidasi saat penyimpanan
        Session::flash('passkey-registration-options', $options);

        return response($options)->header('Content-Type', 'application/json');
    }
    // Before
    /**
     * 2. Menyimpan kredensial Passkey baru milik user
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'passkey' => ['required', 'string'],
    //     ]);

    //     $action = Config::getAction('store_passkey', StorePasskeyAction::class);

    //     try {
    //         $action->execute(
    //             $request->user(),
    //             $request->input('passkey'),
    //             Session::get('passkey-registration-options'),
    //             $request->getHost(),
    //             ['name' => $request->input('name')]
    //         );

    //         return back()->with('status', 'passkey-created');
    //     } catch (\Throwable $e) {
    //         return back()->withErrors(['passkey' => 'Gagal mendaftarkan passkey: ' . $e->getMessage()]);
    //     }
    // }

    // After
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'passkey' => ['required', 'string'],
        ]);

        $action = Config::getAction('store_passkey', StorePasskeyAction::class);

        try {
            $previouslyGeneratedOptions = Session::pull('passkey-registration-options');
            $passkeyJson = $request->input('passkey');

            // WORKAROUND KHUSUS LOCALHOST (HTTP TANPA SSL):
            // Mengubah origin http://localhost menjadi https://localhost di dalam clientDataJSON
            // agar lolos dari pengecekan ketat "Invalid scheme. HTTPS required." milik webauthn-lib
            if (app()->environment('local') && ! $request->isSecure()) {
                $passkeyData = json_decode($passkeyJson, true);

                if (isset($passkeyData['response']['clientDataJSON'])) {
                    $clientDataJson = base64_decode(strtr($passkeyData['response']['clientDataJSON'], '-_', '+/'));
                    $clientData = json_decode($clientDataJson, true);

                    if (isset($clientData['origin']) && str_starts_with($clientData['origin'], 'http://localhost')) {
                        $clientData['origin'] = str_replace('http://', 'https://', $clientData['origin']);
                        $newClientDataJson = json_encode($clientData, JSON_UNESCAPED_SLASHES);
                        $passkeyData['response']['clientDataJSON'] = rtrim(strtr(base64_encode($newClientDataJson), '+/', '-_'), '=');
                        $passkeyJson = json_encode($passkeyData);
                    }
                }
            }

            $action->execute(
                $request->user(),
                $passkeyJson,
                $previouslyGeneratedOptions,
                $request->getHost(), // Menghasilkan 'localhost'
                ['name' => $request->input('name')]
            );

            return back()->with('status', 'passkey-created');
        } catch (\Throwable $e) {
            $realError = $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage();
            return back()->withErrors(['passkey' => 'Gagal: ' . $realError]);
        }
    }

    /**
     * 3. Menghapus Passkey
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->passkeys()->where('id', $id)->delete();

        return back()->with('status', 'passkey-deleted');
    }
}
