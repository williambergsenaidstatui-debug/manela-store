<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('search')<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>@break
        @case('user')<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2Z"/>@break
        @case('bag')<path d="M5 7h14l1 14H4L5 7Z"/><path d="M9 9V5a3 3 0 0 1 6 0v4"/>@break
        @case('menu')<path d="M3 6h18M3 12h18M3 18h18"/>@break
        @case('heart')<path d="M20.5 5.5a5 5 0 0 0-8.5 1 5 5 0 0 0-8.5-1C-.5 11 8 17 12 21c4-4 12.5-10 8.5-15.5Z"/>@break
        @case('diamond')<path d="m3 8 4-5h10l4 5-9 13L3 8Zm0 0h18M7 3l5 18 5-18"/>@break
        @case('shirt')<path d="m8 3-6 4 3 5 3-2v11h8V10l3 2 3-5-6-4a4 4 0 0 1-8 0Z"/>@break
        @case('star')<path d="m12 2 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-6Z"/>@break
        @case('truck')<path d="M2 5h12v13H8m6-9h4l4 5v4h-3m-5 0h1M2 9v9h2"/><circle cx="6" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>@break
        @case('card')<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 9h20M6 15h5"/>@break
        @case('headset')<path d="M4 13v-3a8 8 0 0 1 16 0v3M20 18v1a3 3 0 0 1-3 3h-4"/><rect x="2" y="11" width="4" height="8" rx="2"/><rect x="18" y="11" width="4" height="8" rx="2"/>@break
        @case('award')<path d="m12 2 3 2 4 1 1 4 2 3-2 3-1 4-4 1-3 2-3-2-4-1-1-4-2-3 2-3 1-4 4-1 3-2Z"/><circle cx="12" cy="11" r="4"/><path d="m10 15-1 4 3-1 3 1-1-4"/>@break
        @case('instagram')<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>@break
        @case('pin')<path d="M19 10c0 5-7 12-7 12S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2"/>@break
    @endswitch
</svg>
