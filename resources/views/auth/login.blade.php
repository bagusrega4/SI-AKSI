<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298, #6dd5fa);
            min-height: 100vh;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 400px; width: 100%;">
            <div class="card-body p-5">
                <h2 class="text-center mb-3 fw-bold">Login</h2>
                <p class="text-center text-muted mb-4">Masukan username email BPS anda untuk login</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Email"
                            required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4 position-relative">
                        <input
                            type="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="Password"
                            required autocomplete="current-password">
                        <span class="password-toggle" onclick="togglePassword()">
                            <i id="toggleIcon" class="bi bi-eye"></i>
                        </span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</x-guest-layout>