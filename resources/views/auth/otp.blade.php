<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced OTP Input</title>
    @vite('resources/css/app.css')
    <style>
        .otp-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .otp-input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .otp-input.success {
            border-color: #10b981;
            background-color: #f0fdf4;
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Verifikasi OTP</h2>

        <div class="mb-4">
            <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
                Masukkan kode OTP (6 digit)
            </label>

            <div class="relative">
                <!-- Icon -->
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>

                <!-- OTP Input Container -->
                <div id="otpContainer" class="flex space-x-2 pl-10">
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="0" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="1" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="2" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="3" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="4" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg transition-all duration-200 focus:border-blue-500"
                        data-index="5" />
                </div>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden mt-2 text-sm text-red-600">
                Kode OTP tidak valid. Silakan coba lagi.
            </div>

            <!-- Success Message -->
            <div id="successMessage" class="hidden mt-2 text-sm text-green-600">
                Kode OTP berhasil diverifikasi!
            </div>
        </div>

        <!-- Resend OTP -->
        <div class="text-center mb-4">
            <p class="text-sm text-gray-600">Tidak menerima kode?</p>
            <button id="resendBtn"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium disabled:text-gray-400 disabled:cursor-not-allowed">
                Kirim ulang OTP (<span id="countdown">60</span>s)
            </button>
        </div>

        <!-- Submit Button -->
        <button id="verifyBtn"
            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200"
            disabled>
            Verifikasi OTP
        </button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        class OTPInput {
            constructor() {
                this.inputs = document.querySelectorAll('.otp-input');
                this.container = document.getElementById('otpContainer');
                this.verifyBtn = document.getElementById('verifyBtn');
                this.resendBtn = document.getElementById('resendBtn');
                this.errorMessage = document.getElementById('errorMessage');
                this.successMessage = document.getElementById('successMessage');
                this.countdownElement = document.getElementById('countdown');
                this.noHp = "{{ $noHp }}";

                this.currentOTP = '';
                this.maxLength = 6;
                this.countdown = 60;
                this.countdownInterval = null;

                this.init();
                this.startCountdown();
            }

            init() {
                this.inputs.forEach((input, index) => {
                    // Input event untuk mengetik
                    input.addEventListener('input', (e) => this.handleInput(e, index));

                    // Keydown untuk navigasi dan backspace
                    input.addEventListener('keydown', (e) => this.handleKeydown(e, index));

                    // Paste event
                    input.addEventListener('paste', (e) => this.handlePaste(e));

                    // Focus event
                    input.addEventListener('focus', (e) => e.target.select());

                    // Hanya izinkan angka
                    input.addEventListener('keypress', (e) => {
                        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e
                                .key)) {
                            e.preventDefault();
                        }
                    });
                });

                // Verify button
                this.verifyBtn.addEventListener('click', () => this.verifyOTP());

                // Resend button
                this.resendBtn.addEventListener('click', () => this.resendOTP());

                // Focus pada input pertama
                this.inputs[0].focus();
            }

            handleInput(e, index) {
                const value = e.target.value;

                if (value && /[0-9]/.test(value)) {
                    // Pindah ke input berikutnya
                    if (index < this.inputs.length - 1) {
                        this.inputs[index + 1].focus();
                    }
                }

                this.updateOTP();
                this.clearMessages();
            }

            handleKeydown(e, index) {
                // Backspace
                if (e.key === 'Backspace') {
                    if (!e.target.value && index > 0) {
                        this.inputs[index - 1].focus();
                        this.inputs[index - 1].value = '';
                    }
                    this.updateOTP();
                }

                // Arrow keys navigation
                if (e.key === 'ArrowLeft' && index > 0) {
                    this.inputs[index - 1].focus();
                }
                if (e.key === 'ArrowRight' && index < this.inputs.length - 1) {
                    this.inputs[index + 1].focus();
                }

                // Enter untuk submit
                if (e.key === 'Enter' && this.currentOTP.length === this.maxLength) {
                    this.verifyOTP();
                }
            }

            handlePaste(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');

                if (pastedData.length <= this.maxLength) {
                    for (let i = 0; i < pastedData.length && i < this.inputs.length; i++) {
                        this.inputs[i].value = pastedData[i];
                    }

                    // Focus pada input terakhir yang terisi atau input berikutnya
                    const nextIndex = Math.min(pastedData.length, this.inputs.length - 1);
                    this.inputs[nextIndex].focus();

                    this.updateOTP();
                }
            }

            updateOTP() {
                this.currentOTP = Array.from(this.inputs).map(input => input.value).join('');
                this.verifyBtn.disabled = this.currentOTP.length !== this.maxLength;
            }

            verifyOTP() {
                this.showLoading();
                $.ajax({
                    url: '{{ route('auth.otp') }}',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        otp: this.currentOTP,
                        no_hp: this.noHp
                    },
                    success: (response) => {
                        if (response.status == false) {
                            this.showError();
                            return;
                        }
                        this.showSuccess();
                        window.location.href = '{{ route('keuangan.index') }}';
                    },
                    error: (xhr) => {
                        alert("Login gagal:", xhr.responseJSON.message);
                    },
                });
            }

            showLoading() {
                this.verifyBtn.disabled = true;
                this.verifyBtn.innerHTML = 'Memverifikasi...';
                this.inputs.forEach(input => input.disabled = true);
            }

            showSuccess() {
                this.clearMessages();
                this.successMessage.classList.remove('hidden');
                this.inputs.forEach(input => {
                    input.classList.add('success');
                    input.classList.remove('error');
                });
                this.verifyBtn.innerHTML = 'Berhasil ✓';
                this.verifyBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                this.verifyBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }

            showError() {
                this.clearMessages();
                this.errorMessage.classList.remove('hidden');
                this.container.classList.add('shake');

                this.inputs.forEach(input => {
                    input.classList.add('error');
                    input.classList.remove('success');
                    input.disabled = false;
                });

                this.verifyBtn.disabled = false;
                this.verifyBtn.innerHTML = 'Verifikasi OTP';

                // Hapus animasi shake setelah selesai
                setTimeout(() => {
                    this.container.classList.remove('shake');
                }, 500);

                // Clear input dan focus ke pertama
                setTimeout(() => {
                    this.clearOTP();
                    this.inputs[0].focus();
                }, 1500);
            }

            clearMessages() {
                this.errorMessage.classList.add('hidden');
                this.successMessage.classList.add('hidden');
            }

            clearOTP() {
                this.inputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('error', 'success');
                    input.disabled = false;
                });
                this.currentOTP = '';
                this.verifyBtn.disabled = true;
                this.verifyBtn.innerHTML = 'Verifikasi OTP';
                this.verifyBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                this.verifyBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                this.clearMessages();
            }

            startCountdown() {
                this.resendBtn.disabled = true;
                this.countdownInterval = setInterval(() => {
                    this.countdown--;
                    this.countdownElement.textContent = this.countdown;

                    if (this.countdown <= 0) {
                        clearInterval(this.countdownInterval);
                        this.resendBtn.disabled = false;
                        this.resendBtn.innerHTML = 'Kirim ulang OTP';
                    }
                }, 1000);
            }

            resendOTP() {
                // Reset countdown
                this.countdown = 60;
                this.countdownElement.textContent = this.countdown;

                // Jangan ubah seluruh innerHTML, cukup update teks dari span
                this.resendBtn.innerHTML = 'Kirim ulang OTP (<span id="countdown"></span>s)';

                // Re-assign reference ke countdownElement karena innerHTML di atas me-replace-nya
                this.countdownElement = document.getElementById('countdown');
                this.countdownElement.textContent = this.countdown;

                $.ajax({
                    url: "{{ route('auth.login') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_hp: this.noHp
                    },
                    success: (response) => {
                        this.clearOTP();
                        this.startCountdown();
                        alert('OTP baru telah dikirim silahkan cek whatsapp anda!');
                    },
                    error: (xhr) => {
                        alert("Login gagal:", xhr.responseJSON.message);
                    }
                });
            }

        }

        // Initialize OTP Input
        const otpInput = new OTPInput();
    </script>
</body>

</html>
