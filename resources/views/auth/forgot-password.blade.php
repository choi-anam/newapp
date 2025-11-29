<x-guest-layout>
    <div class="auth-header-new email-method-header">
        <div class="method-badge">📧 Email Link</div>
        <div class="header-icon">
            <i class="bi bi-envelope-open-heart"></i>
        </div>
        <h2 class="fw-bold mb-2">Reset Password via Email ✉️</h2>
    </div>

    <div class="card-body p-5">
        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="nama@email.com"
                        required 
                        autofocus
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
                <small class="form-text text-muted d-block mt-2">
                    <i class="bi bi-info-circle"></i> Masukkan email yang terdaftar pada akun Anda
                </small>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary d-grid w-100 mb-3 py-2 fw-semibold" disabled>
                <i class="bi bi-send"></i> Kirim Link Reset Password
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <p><a href="{{ route('password.method') }}" class="auth-link"><i class="bi bi-arrow-left"></i> Kembali ke Pilihan Metode</a></p>
    </div>

    <style>
        .auth-header-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .email-method-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }

        .method-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
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

        .btn-outline-secondary {
            border: 2px solid #667eea;
            color: #667eea;
            font-weight: 600;
            padding: 0.65rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        .divider-text {
            padding: 0 1rem;
            color: #999;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (prefers-color-scheme: dark) {
            .input-group-merge .input-group-text {
                background-color: #1a1a1a;
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

            .divider::before,
            .divider::after {
                background-color: #444;
            }

            .divider-text {
                color: #666;
            }

            .form-label {
                color: #ccc;
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
