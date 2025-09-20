<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #1d2671, #c33764);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 380px;
            color: white;
        }

        .glass-card h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        .glass-card p {
            text-align: center;
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 25px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 12px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            outline: none;
            box-shadow: none;
            color: #fff;
        }

        .btn-login {
            background: linear-gradient(45deg, #6dd5ed, #2193b0);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #2193b0, #6dd5ed);
            transform: scale(1.03);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>

    <div class="glass-card">
        <h2>Welcome Back</h2>
        <p>Masukkan email BPS dan password untuk login</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <input type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <input type="password"
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
            <button type="submit" class="btn btn-login w-100">
                Login
            </button>
        </form>
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</x-guest-layout>