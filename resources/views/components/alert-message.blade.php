<div>
    <!-- ========================================================================= -->
    <!-- SUCCESS NOTIFICATION ALERT BLOCK                                          -->
    <!-- ========================================================================= -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-white shadow-sm rounded-pill px-4 mb-3 custom-auto-dismiss-alert"
        role="alert" style="background: linear-gradient(135deg, #2dce89 0%, #2d8ceb 100%); font-size: 13px; padding-top: 8px; padding-bottom: 8px;">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="now-ui-icons ui-2_like mr-2" style="font-size: 14px;"></i>
                <span class="font-weight-bold">Success!</span> {{ session('success') }}
            </div>
            <button type="button" class="close text-white p-0 border-0 bg-transparent" data-dismiss="alert" aria-label="Close" style="opacity: 0.9; font-size: 14px;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- CUSTOM ERROR NOTIFICATION ALERT BLOCK                                     -->
    <!-- ========================================================================= -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-white shadow-sm rounded-pill px-4 mb-3 custom-auto-dismiss-alert"
        role="alert" style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); font-size: 13px; padding-top: 8px; padding-bottom: 8px;">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="now-ui-icons ui-1_simple-remove mr-2" style="font-size: 14px;"></i>
                <span class="font-weight-bold">Error!</span> {{ session('error') }}
            </div>
            <button type="button" class="close text-white p-0 border-0 bg-transparent" data-dismiss="alert" aria-label="Close" style="opacity: 0.9; font-size: 14px;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODERN "COOKIE/BADGE" STYLE SECURITY ALERT (Persistent via LocalStorage)   -->
    <!-- ========================================================================= -->
    @php
        $hasThrottleError = false;
        $seconds = 60;

        if ($errors->any()) {
            $securityErrors = collect($errors->all())->filter(function ($error) {
                return str_contains($error, 'Too many') ||
                       str_contains($error, 'throttle') ||
                       str_contains($error, 'security') ||
                       str_contains($error, 'expired') ||
                       str_contains($error, 'locked');
            });

            if ($securityErrors->isNotEmpty()) {
                $hasThrottleError = true;
                $errorMessage = $securityErrors->first();
                preg_match('/\d+/', $errorMessage, $matches);
                $seconds = isset($matches[0]) ? (int)$matches[0] : 60;
            }
        }
    @endphp

    <!-- Biscuit / Badge Card Style Container -->
    <div id="modern-security-alert" class="mb-3 text-center" style="display: none;">
        <div class="d-inline-flex align-items-center justify-content-center px-3 py-2 shadow-sm"
            style="background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%); border: 1px solid rgba(245, 54, 92, 0.2); border-radius: 50px; font-size: 12px; color: #b91c1c;">
            <i class="now-ui-icons objects_shield-39 mr-2 text-danger" style="font-size: 13px;"></i>
            <span>Too many attempts. Please wait
                <span id="modern-countdown" class="font-weight-bold text-danger" style="font-size: 13px;">{{ $seconds }}</span>s
            </span>
        </div>
    </div>

    <script>
        const serverHasThrottle = @json($hasThrottleError);
        const serverSeconds = @json($seconds);

        function dismissSecurityAlert() {
            const alertBox = document.getElementById('modern-security-alert');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
            localStorage.removeItem('lockout_expiry');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const alertBox = document.getElementById('modern-security-alert');
            const timerSpan = document.getElementById('modern-countdown');

            let expiryTime = localStorage.getItem('lockout_expiry');
            const now = Math.floor(Date.now() / 1000);

            if (serverHasThrottle) {
                expiryTime = now + serverSeconds;
                localStorage.setItem('lockout_expiry', expiryTime);
            }

            if (expiryTime && expiryTime > now) {
                let timeLeft = expiryTime - now;

                if (alertBox) alertBox.style.display = 'block';
                if (timerSpan) timerSpan.textContent = timeLeft;

                const interval = setInterval(function () {
                    const now_current = Math.floor(Date.now() / 1000);
                    timeLeft = expiryTime - now_current;

                    if (timerSpan) timerSpan.textContent = timeLeft > 0 ? timeLeft : 0;

                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        dismissSecurityAlert();
                        window.location.reload();
                    }
                }, 1000);
            } else {
                if (expiryTime) {
                    localStorage.removeItem('lockout_expiry');
                }
            }
        });
    </script>
</div>
