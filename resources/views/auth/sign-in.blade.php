<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 420px;">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-box-arrow-in-right fs-4"></i>
                    </div>
                    <h4 class="fw-bold">Selamat Datang</h4>
                    <p class="text-muted small">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center py-2 small" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Masuk <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-layout>
