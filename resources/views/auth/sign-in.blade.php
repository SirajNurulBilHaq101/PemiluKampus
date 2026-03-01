<x-layout>
    <div class="min-h-screen flex items-center justify-center bg-base-200 px-4">
        <div class="card bg-base-100 shadow-lg w-full max-w-sm">
            <div class="card-body">

                {{-- Header --}}
                <div class="text-center mb-2">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-content mb-3">
                        <i class="bi bi-clipboard2-data text-xl"></i>
                    </div>
                    <h2 class="text-xl font-bold">Selamat Datang</h2>
                    <p class="text-sm text-base-content/60 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div role="alert" class="alert alert-error alert-sm">
                        <i class="bi bi-exclamation-circle"></i>
                        <span class="text-sm">{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4 mt-2">
                    @csrf

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend text-sm">Email</legend>
                        <label class="input w-full">
                            <i class="bi bi-envelope opacity-50"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com" required autofocus />
                        </label>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend text-sm">Password</legend>
                        <label class="input w-full">
                            <i class="bi bi-lock opacity-50"></i>
                            <input type="password" name="password" placeholder="••••••••" required />
                        </label>
                    </fieldset>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="checkbox checkbox-sm" />
                        <span class="text-sm">Ingat saya</span>
                    </label>

                    <button type="submit" class="btn btn-primary w-full">
                        Masuk <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-layout>
