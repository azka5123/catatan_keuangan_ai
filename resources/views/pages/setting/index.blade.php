@extends('app')

@section('title', 'Pengaturan')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pengaturan Akun</h1>
                <p class="mt-2 text-gray-600">Kelola informasi profil dan preferensi akun Anda</p>
            </div>

            <!-- Settings Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('pengaturan.update', auth()->user()->id) }}" method="POST"
                    enctype="multipart/form-data" id="settingsForm">
                    @csrf
                    @method('PUT')

                    <!-- Form Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Profile Photo Section -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <img id="photo-preview"
                                        src="{{ auth()->user()->photo ? asset('dist/img/profil/' . auth()->user()->photo) : 'https://placehold.co/400' }}"
                                        alt="Foto Profil"
                                        class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                    <div class="absolute inset-0 rounded-full  bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center cursor-pointer"
                                        onclick="document.getElementById('photo').click()">
                                        <svg class="w-6 h-6 text-white opacity-0 hover:opacity-100 transition-opacity duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <input type="file" id="photo" name="photo" accept="image/*" class="hidden"
                                    onchange="previewImage(this)">
                                <button type="button" onclick="document.getElementById('photo').click()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    Pilih Foto
                                </button>
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau GIF maksimal 2MB</p>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama lengkap Anda"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" placeholder="contoh@email.com"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number Field -->
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor HP <span class="text-red-500">*</span> <small class="text-gray-500">(verifikasi OTP
                                    untuk mengganti nomor)</small>
                            </label>
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="tel" id="no_hp" name="no_hp"
                                        value="{{ old('no_hp', auth()->user()->no_hp) }}" placeholder="62xxxxxxxxxxx"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('no_hp') border-red-500 @enderror"
                                        required readonly>
                                    <div id="phone-status" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <!-- Verification status will appear here -->
                                    </div>
                                </div>
                                <button type="button" id="send-otp-btn" onclick="sendOTP()"
                                    class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787" />
                                        </svg>
                                        <span id="otp-btn-text">Kirim OTP</span>
                                    </div>
                                </button>
                            </div>
                            @error('no_hp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- OTP Verification Section -->
                            <div id="otp-section" class="mt-4 hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        <h4 class="text-sm font-medium text-blue-900">Verifikasi Nomor HP</h4>
                                    </div>
                                    <p class="text-sm text-blue-700 mb-3">
                                        Kode OTP telah dikirim ke WhatsApp <span id="phone-display"
                                            class="font-medium"></span>
                                    </p>

                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1">
                                            <label for="otp_code"
                                                class="block text-xs font-medium text-blue-700 mb-1">Masukkan Kode
                                                OTP</label>
                                            <input type="text" id="otp_code" name="otp_code" maxlength="6"
                                                placeholder="######"
                                                class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-lg font-mono tracking-widest"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                        <button type="button" id="verify-otp-btn" onclick="verifyOTP()"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Verifikasi
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between mt-3 text-xs">
                                        <div class="text-blue-600">
                                            <span id="countdown-text">Kirim ulang dalam <span id="countdown">60</span>
                                                detik</span>
                                        </div>
                                        <button type="button" id="resend-otp-btn" onclick="resendOTP()"
                                            class="text-blue-600 hover:text-blue-800 font-medium disabled:opacity-50 disabled:cursor-not-allowed hidden">
                                            Kirim Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <button type="button" onclick="resetForm()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset
                        </button>

                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="button-text">Simpan Perubahan</span>
                            <div class="loading-spinner hidden ml-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div
                    class="mt-4 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                document.getElementById('settingsForm').reset();
                // Reset photo preview to original
                document.getElementById('photo-preview').src =
                    "{{ auth()->user()->photo ? asset('dist/img/profil/' . auth()->user()->photo) : 'https://placehold.co/400' }}";
            }
        }

        // Form submission with loading state
        document.getElementById('settingsForm').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const buttonText = button.querySelector('.button-text');
            const spinner = button.querySelector('.loading-spinner');

            button.disabled = true;
            buttonText.textContent = 'Menyimpan...';
            spinner.classList.remove('hidden');
        });

        // Auto-format phone number to international format
        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (value.startsWith('62')) {
                value = value;
            }
            e.target.value = value;

            // Reset verification status when phone number changes
            resetVerificationStatus();
        });

        // OTP functionality
        let otpCountdown;
        let isPhoneVerified = false;

        function sendOTP() {
            const phoneNumber = document.getElementById('no_hp').value;
            const sendBtn = document.getElementById('send-otp-btn');
            const btnText = document.getElementById('otp-btn-text');

            if (!phoneNumber || phoneNumber.length < 10) {
                alert('Masukkan nomor HP yang valid');
                return;
            }

            // Disable button and show loading
            sendBtn.disabled = true;
            btnText.textContent = 'Mengirim...';

            $.ajax({
                url: "{{ route('auth.login') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_hp: phoneNumber,
                    type: 'update'
                },
                success: (response) => {
                    showOTPSection();
                    startCountdown();
                    document.getElementById('phone-display').textContent = formatPhoneDisplay(phoneNumber);
                },
                error: (xhr) => {
                    alert("Gagal validasi OTP:", xhr.responseJSON.message);
                },
                complete: () => {
                    sendBtn.disabled = false;
                    btnText.textContent = 'Kirim OTP';
                }
            });
        }

        function verifyOTP() {
            const otpCode = document.getElementById('otp_code').value;
            const phoneInput = document.getElementById('no_hp');
            const phoneNumber = phoneInput.value;
            const verifyBtn = document.getElementById('verify-otp-btn');

            if (!otpCode || otpCode.length !== 6) {
                alert('Masukkan kode OTP 6 digit');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Memverifikasi...';

            $.ajax({
                url: '{{ route('auth.otp') }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    otp: otpCode,
                    no_hp: phoneNumber
                },
                success: (response) => {
                    if (response.status == false) {
                        alert('Kode OTP tidak valid atau sudah tidak aktif: ' + data.message);
                        return;
                    }
                    showVerificationSuccess();
                    isPhoneVerified = true;
                    phoneInput.readOnly = false
                },
                error: (xhr) => {
                    alert("Gagal validasi OTP:", xhr.responseJSON.message);
                },
                complete: () => {
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verifikasi';
                }
            });
        }

        function resendOTP() {
            sendOTP();
        }

        function showOTPSection() {
            document.getElementById('otp-section').classList.remove('hidden');
            document.getElementById('otp_code').focus();
        }

        function hideOTPSection() {
            document.getElementById('otp-section').classList.add('hidden');
            document.getElementById('otp_code').value = '';
        }

        function startCountdown() {
            let seconds = 60;
            const countdownElement = document.getElementById('countdown');
            const countdownText = document.getElementById('countdown-text');
            const resendBtn = document.getElementById('resend-otp-btn');

            resendBtn.classList.add('hidden');
            countdownText.classList.remove('hidden');

            otpCountdown = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(otpCountdown);
                    countdownText.classList.add('hidden');
                    resendBtn.classList.remove('hidden');
                }
            }, 1000);
        }

        function showVerificationSuccess() {
            const phoneStatus = document.getElementById('phone-status');
            phoneStatus.innerHTML = `
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            `;

            hideOTPSection();

            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'mt-2 text-sm text-green-600 flex items-center';
            successDiv.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Nomor HP berhasil diverifikasi
            `;
            document.getElementById('otp-section').parentNode.appendChild(successDiv);

            // Remove success message after 3 seconds
            setTimeout(() => {
                if (successDiv.parentNode) {
                    successDiv.parentNode.removeChild(successDiv);
                }
            }, 3000);
        }

        function resetVerificationStatus() {
            const phoneStatus = document.getElementById('phone-status');
            phoneStatus.innerHTML = '';
            hideOTPSection();
            isPhoneVerified = false;

            if (otpCountdown) {
                clearInterval(otpCountdown);
            }
        }

        function formatPhoneDisplay(phone) {
            if (phone.startsWith('62')) {
                return '+' + phone.substring(0, 2) + ' ' + phone.substring(2, 5) + '-' + phone.substring(5, 9) + '-' + phone
                    .substring(9);
            }
            return phone;
        }

        // Add hidden input for verification status
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            // Add verification status to form
            const verifiedInput = document.createElement('input');
            verifiedInput.type = 'hidden';
            verifiedInput.name = 'phone_verified';
            verifiedInput.value = isPhoneVerified ? '1' : '0';
            this.appendChild(verifiedInput);
        });
    </script>
@endpush
