@extends('admin.partials.layout2')

@section('content')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif

    <style>
        body {
            overflow-x: hidden;
            background: linear-gradient(135deg, #1C2245 0%, #30366A 100%); /* Latar belakang dari desain asli */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem; /* Padding untuk layar kecil */
        }

        .admin-login-container {
            background-color: #ffffff;
            padding: 2rem; /* Padding lebih besar */
            border-radius: 15px; /* Radius lebih besar */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px; /* Lebar maksimum form */
            text-align: center;
        }

        .form-title {
            position: relative;
            font-size: 1.75rem; /* Ukuran font judul disesuaikan */
            margin-bottom: 2rem; /* Jarak bawah lebih besar */
            color: #1C2245; /* Warna judul agar kontras dengan background container */
            font-weight: 700;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px; /* Lebar garis bawah */
            height: 3px;
            background: linear-gradient(to right, #30366A, #1C2245); /* Gradient disesuaikan */
            border-radius: 3px;
        }

        .input-box {
            position: relative;
            width: 100%;
            margin-bottom: 1.5rem; /* Jarak antar input */
        }

        .input-box input {
            width: 100%;
            padding: 0.875rem 2.5rem 0.875rem 1rem; /* Padding input disesuaikan */
            border-radius: 8px; /* Radius input */
            border: 1px solid #ced4da; /* Border lebih jelas */
            outline: none;
            font-size: 1rem;
            color: #495057;
            background-color: #f8f9fa; /* Background input sedikit berbeda */
            transition: all 0.3s ease;
        }

        .input-box input:focus {
            border-color: #1C2245;
            box-shadow: 0 0 0 0.2rem rgba(48, 54, 106, 0.25); /* Shadow fokus disesuaikan */
            background-color: #ffffff;
        }

        .input-box i.fa-solid {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.25rem;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .input-box input:focus ~ i.fa-solid {
            color: #1C2245;
        }

        .input-error {
            border-color: #EF4444 !important;
            background-color: rgba(254, 226, 226, 0.5) !important;
        }

        .input-error::placeholder {
            color: #EF4444;
            opacity: 0.7;
        }

        .error-message {
            color: #EF4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
            font-weight: 500;
            text-align: left; /* Pesan error rata kiri */
        }

        .error-message.visible {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .input-box i.fa-solid.icon-hidden {
            display: none !important;
        }

        .form-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            background: linear-gradient(135deg, #1C2245 0%, #30366A 100%); /* Warna tombol admin */
            color: white;
            font-weight: bold;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none; /* Hapus border default button */
            cursor: pointer; /* Tambah cursor pointer */
        }

        .form-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px); /* Efek hover sedikit naik */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Animasi dari desain asli */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease forwards;
        }
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

    </style>

    <div class="admin-login-container animate-fadeIn">
        <form id="adminLoginForm" action="{{ route('manualLogin') }}" method="POST" novalidate>
            @csrf
            <h1 class="form-title">Admin Login</h1>

            <div class="input-box">
                <input id="usernameOrEmail" type="text" aria-label="Username or Email"
                       placeholder="Username or Email" required name="usernameOrEmail">
                <i class="fa-solid fa-user"></i>
                <small class="error-message"></small>
            </div>

            <div class="input-box">
                <input id="loginPassword" type="password" aria-label="Password" placeholder="Password"
                       required name="loginPassword">
                <i class="fa-solid fa-eye password-toggle cursor-pointer"></i>
                <small class="error-message"></small>
            </div>

            <button type="submit" class="form-btn">
                Sign In
            </button>
        </form>
    </div>

    <script>
        // --- Input Validation Helper Functions ---
        function showError(inputElement, message) {
            const inputBox = inputElement.closest('.input-box');
            const errorElement = inputBox.querySelector('.error-message');
            const iconElement = inputBox.querySelector('i.fa-solid');

            inputElement.classList.add('input-error');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.add('visible');
            }
            if (iconElement && !iconElement.classList.contains('password-toggle')) { // Jangan sembunyikan ikon mata
                iconElement.classList.add('icon-hidden');
            }
        }

        function clearError(inputElement) {
            const inputBox = inputElement.closest('.input-box');
            const errorElement = inputBox.querySelector('.error-message');
            const iconElement = inputBox.querySelector('i.fa-solid');

            inputElement.classList.remove('input-error');
            if (errorElement) {
                errorElement.classList.remove('visible');
                errorElement.textContent = '';
            }
            if (iconElement && !iconElement.classList.contains('password-toggle')) { // Jangan tampilkan kembali ikon mata secara paksa jika sudah diubah
                iconElement.classList.remove('icon-hidden');
            }
        }

        // --- Login Form Validation ---
        const adminLoginForm = document.getElementById('adminLoginForm');
        const usernameOrEmailInput = document.getElementById('usernameOrEmail');
        const loginPasswordInput = document.getElementById('loginPassword');

        if (adminLoginForm) {
            adminLoginForm.addEventListener('submit', function(event) {
                let isValid = true;
                adminLoginForm.querySelectorAll('.input-box input').forEach(input => clearError(input));

                if (!usernameOrEmailInput.value.trim()) {
                    showError(usernameOrEmailInput, 'Username or Email is required.');
                    isValid = false;
                }

                if (!loginPasswordInput.value.trim()) {
                    showError(loginPasswordInput, 'Password is required.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                    adminLoginForm.classList.add('animate-shake');
                    setTimeout(() => {
                        adminLoginForm.classList.remove('animate-shake');
                    }, 500);
                } else {
                    Swal.fire({
                        title: "Logging in...",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        }

        // --- Live Validation on Blur and Clear on Input ---
        document.querySelectorAll('.admin-login-container .input-box input').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.id === 'usernameOrEmail') {
                    if (!this.value.trim()) showError(this, 'Username or Email is required.');
                    else clearError(this);
                } else if (this.id === 'loginPassword') {
                    if (!this.value.trim()) showError(this, 'Password is required.');
                    else clearError(this);
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('input-error')) {
                    clearError(this);
                }
            });
        });

        // --- Password Toggle Functionality ---
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>
@endsection