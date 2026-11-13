<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui nama, foto profil, dan informasi akun Anda.
        </p>
    </header>

    {{-- Avatar & Foto Profil --}}
    <div class="mt-6 flex flex-col items-center sm:flex-row sm:items-end gap-4">
        {{-- Preview Avatar --}}
        <div class="relative">
            <img id="foto-preview"
                 src="{{ $user->foto_profil ? Storage::url($user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e3a5f&color=fff&size=96' }}"
                 alt="Foto Profil"
                 class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md" />
        </div>

        {{-- Tombol aksi foto --}}
        <div class="flex flex-col gap-2">
            <label for="foto_profil_input"
                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg cursor-pointer
                       bg-navy-800 text-white hover:bg-navy-900 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ganti Foto
            </label>

            @if ($user->foto_profil)
                <form method="POST" action="{{ route('profile.foto.destroy') }}" id="form-hapus-foto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg
                               bg-white border border-red-300 text-red-600 hover:bg-red-50 transition-colors shadow-sm"
                        onclick="return confirm('Hapus foto profil?')">
                        <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Foto
                    </button>
                </form>
            @endif
        </div>
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6"
          enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Hidden input untuk file foto (tersembunyi, trigger dari label di atas) --}}
        <input type="file" id="foto_profil_input" name="foto_profil"
               accept="image/jpeg,image/png,image/webp" class="hidden" />

        {{-- Nama --}}
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- NIM --}}
        <div>
            <x-input-label for="nim" value="NIM (Nomor Induk Mahasiswa)" />
            <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full"
                          :value="old('nim', $user->nim)" required
                          placeholder="Contoh: 2410123456" />
            <x-input-error class="mt-2" :messages="$errors->get('nim')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Alamat email Anda belum diverifikasi.

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Klik di sini untuk kirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Link verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Fakultas & Program Studi (2 kolom di sm ke atas) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Fakultas --}}
            <div>
                <x-input-label for="profile_fakultas" value="Fakultas" />
                <x-text-input id="profile_fakultas" name="fakultas" type="text" class="mt-1 block w-full"
                              :value="old('fakultas', $user->fakultas)" required
                              placeholder="Contoh: Fakultas Teknik" />
                <x-input-error class="mt-2" :messages="$errors->get('fakultas')" />
            </div>

            {{-- Program Studi --}}
            <div>
                <x-input-label for="profile_program_studi" value="Program Studi" />
                <x-text-input id="profile_program_studi" name="program_studi" type="text" class="mt-1 block w-full"
                              :value="old('program_studi', $user->program_studi)" required
                              placeholder="Contoh: Teknik Informatika" />
                <x-input-error class="mt-2" :messages="$errors->get('program_studi')" />
            </div>
        </div>

        {{-- Nomor HP --}}
        <div>
            <x-input-label for="no_hp" value="Nomor HP (Opsional)" />
            <x-text-input id="no_hp" name="no_hp" type="tel" class="mt-1 block w-full"
                          :value="old('no_hp', $user->no_hp)" autocomplete="tel"
                          placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan Perubahan</x-primary-button>

            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Batal
            </a>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >Profil berhasil diperbarui.</p>
            @endif

            @if (session('status') === 'foto-deleted')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >Foto profil berhasil dihapus.</p>
            @endif
        </div>
    </form>

    <script>
        // Foto preview dengan FileReader
        const fotoInput = document.getElementById('foto_profil_input');
        const fotoPreview = document.getElementById('foto-preview');

        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</section>
