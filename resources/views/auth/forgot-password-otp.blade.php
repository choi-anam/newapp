<x-guest-layout>
    <div class="auth-header-new">
        <div class="header-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h2 class="fw-bold mb-2">Lupa Password? 🔐</h2>
        <p class="text-muted">Masukkan alamat email Anda dan kami akan mengirimkan instruksi untuk mereset password</p>
    </div>

    <div class="card-body p-5">
        <!-- Session Status -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.forgot.store') }}" class="mb-3">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input 
                        type="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="john@example.com"
                        autofocus
                        required
                        autocomplete="email"
                    />
                </div>
                <div id="emailStatus" class="d-none mt-2">
                    <small id="emailMessage" class="form-text"></small>
                </div>
                <div id="emailCheckInfo" class="alert alert-info d-none mt-2 py-2 px-3" role="alert">
                    <small><i class="bi bi-hourglass-split"></i> <span id="checkingText">Memverifikasi email...</span></small>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary d-grid w-100 mb-3 py-2 fw-semibold" disabled>
                <i class="bi bi-send"></i> Kirim Link Reset Password
            </button>
        </form>

        <hr class="my-4">

        <!-- Alternative Methods -->
        <div class="alternative-methods mb-3">
            <p class="text-center text-muted small mb-3">
                <strong>Atau pilih metode reset lainnya:</strong>
            </p>
            
            <div class="d-grid gap-2">
                <a href="{{ route('password.request') }}" class="btn btn-outline-primary py-2">
                    <i class="bi bi-envelope-check"></i> Email Verification Link
                </a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center gap-2 text-decoration-none mt-3">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke login</span>
            </a>
        </div>
    </div>

    <style>
        .auth-header-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.95;
        }

        .auth-header-new h2 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .auth-header-new p {
            font-size: 0.95rem;
            max-width: 500px;
            margin: 0 auto;
            opacity: 0.95;
            line-height: 1.6;
        }

        .input-group-merge .input-group-text {
            background-color: #f5f5f9;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #667eea;
        }

        .input-group-merge .form-control {
            border-left: none;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
        }

        .input-group-merge .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }

        .input-group-merge .form-control:focus + .input-group-text,
        .input-group-merge .input-group-text:has(+ .form-control:focus) {
            border-color: #667eea;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-outline-primary {
            color: #667eea;
            border: 1.5px solid #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .alternative-methods {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 3px solid #667eea;
        }

        hr {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 1.5rem 0;
        }

        .form-label {
            color: #495057;
            margin-bottom: 0.75rem;
        }

        @media (prefers-color-scheme: dark) {
            .input-group-merge .input-group-text {
                background-color: #2d2d2d;
                border-color: #444;
                color: #667eea;
            }

            .input-group-merge .form-control {
                background-color: #1a1a1a;
                border-color: #444;
                color: #e0e0e0;
            }

            .input-group-merge .form-control:focus {
                border-color: #667eea;
                background-color: #1a1a1a;
            }

            .alternative-methods {
                background-color: #2d2d2d;
                border-left-color: #667eea;
            }

            .text-muted {
                color: #999 !important;
            }

            .form-label {
                color: #ccc;
            }

            hr {
                border-top-color: #444;
            }
        }
    </style>

    <script>
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');
        const emailStatus = document.getElementById('emailStatus');
        const emailMessage = document.getElementById('emailMessage');
        const emailCheckInfo = document.getElementById('emailCheckInfo');
        const checkingText = document.getElementById('checkingText');
        let checkTimeout;

        emailInput.addEventListener('input', function() {
            clearTimeout(checkTimeout);
            const email = this.value.trim();
            
            // Reset status
            emailStatus.classList.add('d-none');
            emailCheckInfo.classList.add('d-none');
            submitBtn.disabled = true;
            
            if (!email) {
                return;
            }

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailStatus.classList.remove('d-none');
                emailMessage.innerHTML = '<i class="bi bi-exclamation-circle"></i> Format email tidak valid';
                emailMessage.classList.remove('text-success');
                emailMessage.classList.add('text-danger');
                return;
            }

            // Show checking status
            emailCheckInfo.classList.remove('d-none');
            checkingText.textContent = 'Memverifikasi email...';

            // Check if email exists in database
            checkTimeout = setTimeout(() => {
                fetch('{{ route("api.check-email") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    emailCheckInfo.classList.add('d-none');
                    emailStatus.classList.remove('d-none');
                    
                    if (data.exists) {
                        emailMessage.innerHTML = '<i class="bi bi-check-circle"></i> Email terdaftar ✓';
                        emailMessage.classList.remove('text-danger');
                        emailMessage.classList.add('text-success');
                        submitBtn.disabled = false;
                    } else {
                        emailMessage.innerHTML = '<i class="bi bi-exclamation-circle"></i> Email tidak terdaftar di sistem';
                        emailMessage.classList.remove('text-success');
                        emailMessage.classList.add('text-danger');
                        submitBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    emailCheckInfo.classList.add('d-none');
                    emailStatus.classList.add('d-none');
                    // Allow submit on error
                    submitBtn.disabled = false;
                });
            }, 500); // Debounce 500ms
        });

        // Prevent form submission if button is disabled
        document.querySelector('form').addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                alert('Silakan masukkan email yang valid dan terdaftar di sistem');
            }
        });
    </script>
</x-guest-layout>
