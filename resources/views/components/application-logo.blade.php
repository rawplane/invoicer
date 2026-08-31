<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Background rounded square with Emerald gradient -->
    <defs>
        <linearGradient id="invoicerGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
            <stop stop-color="#10B981"/>
            <stop offset="1" stop-color="#059669"/>
        </linearGradient>
    </defs>
    <rect width="48" height="48" rx="12" fill="url(#invoicerGrad)"/>
    <!-- Invoice/Receipt icon -->
    <path d="M14 10.5C14 9.67 14.67 9 15.5 9H32.5C33.33 9 34 9.67 34 10.5V37.5C34 38.33 33.33 39 32.5 39H15.5C14.67 39 14 38.33 14 37.5V10.5Z" fill="white" fill-opacity="0.95"/>
    <!-- Lines on receipt -->
    <rect x="18" y="15" width="12" height="2" rx="1" fill="#059669"/>
    <rect x="18" y="20" width="12" height="2" rx="1" fill="#A7F3D0"/>
    <rect x="18" y="25" width="8" height="2" rx="1" fill="#A7F3D0"/>
    <!-- Checkmark -->
    <path d="M21 31.5L23.5 34L28 29.5" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
