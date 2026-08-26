<div id="wafar-splash" class="wafar-splash" role="status" aria-label="جاري تحميل وفر كاش">
    <div class="wafar-splash__map" aria-hidden="true"></div>
    <div class="wafar-splash__glow" aria-hidden="true"></div>

    <div class="wafar-splash__content">
        <div class="wafar-splash__logo-wrap">
            <img src="{{ asset('assets/imges/wafar-cash-splash-logo.png') }}" alt="شعار وفر كاش" class="wafar-splash__logo">
        </div>
        <h1 class="wafar-splash__arabic">وفر كاش</h1>
        <p class="wafar-splash__english">WAFAR CASH</p>
        <span class="wafar-splash__tagline">مقارنة أذكى، توفير أكبر</span>
    </div>

    <button type="button" class="wafar-splash__skip" aria-label="تخطي شاشة البداية">تخطي <span aria-hidden="true">←</span></button>
</div>

<style>
    .wafar-splash { --pointer-x: 0px; --pointer-y: 0px; position: fixed; inset: 0; z-index: 9999; display: grid; place-items: center; overflow: hidden; isolation: isolate; background: #003525; opacity: 1; transition: opacity 450ms ease, visibility 450ms ease; }
    .wafar-splash.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .wafar-splash__map { position: absolute; inset: -5%; z-index: -2; background: linear-gradient(rgba(0, 47, 33, .28), rgba(0, 27, 19, .72)), url('{{ asset('assets/imges/wafar-cash-splash-map.png') }}') center / cover no-repeat; transform: translate(var(--pointer-x), var(--pointer-y)) scale(1.08); animation: wafar-map-drift 12s ease-in-out infinite alternate; will-change: transform; }
    .wafar-splash__glow { position: absolute; inset: 0; z-index: -1; background: radial-gradient(circle at center, rgba(126, 255, 137, .2), transparent 42%), linear-gradient(120deg, rgba(0, 0, 0, .2), rgba(0, 50, 34, .58)); }
    .wafar-splash__content { display: flex; flex-direction: column; align-items: center; padding: 2rem; text-align: center; color: #fff; transform: translateY(-.5rem); }
    .wafar-splash__logo-wrap { width: clamp(150px, 23vw, 260px); aspect-ratio: 1; display: grid; place-items: center; filter: drop-shadow(0 18px 24px rgba(0, 0, 0, .45)); animation: wafar-logo-enter .85s cubic-bezier(.2, .9, .3, 1.25) both, wafar-logo-float 3.5s 1s ease-in-out infinite; }
    .wafar-splash__logo { width: 100%; height: 100%; object-fit: contain; mix-blend-mode: screen; }
    .wafar-splash__arabic { margin: .5rem 0 .15rem; font-family: 'Tajawal', sans-serif; font-size: clamp(2rem, 5.2vw, 4.25rem); font-weight: 900; line-height: 1; color: #a6f56d; text-shadow: 0 3px 18px rgba(0, 0, 0, .5); animation: wafar-text-enter .6s .35s both; }
    .wafar-splash__english { margin: 0; direction: ltr; font-family: 'Tajawal', sans-serif; font-size: clamp(1rem, 2.25vw, 1.65rem); font-weight: 800; letter-spacing: .24em; color: #fff; animation: wafar-text-enter .6s .5s both; }
    .wafar-splash__tagline { margin-top: .8rem; padding-top: .75rem; border-top: 1px solid rgba(195, 255, 195, .35); font-family: 'Tajawal', sans-serif; font-size: clamp(.8rem, 1.5vw, 1rem); color: #d7f8d6; animation: wafar-text-enter .6s .65s both; }
    .wafar-splash__skip { position: absolute; bottom: clamp(1.25rem, 4vw, 2.5rem); left: clamp(1.25rem, 4vw, 2.5rem); border: 1px solid rgba(215, 248, 214, .55); border-radius: 999px; padding: .45rem .85rem; color: #fff; background: rgba(0, 42, 29, .44); font: 600 .85rem 'Tajawal', sans-serif; cursor: pointer; backdrop-filter: blur(5px); transition: background .2s ease, transform .2s ease; }
    .wafar-splash__skip:hover { background: rgba(36, 223, 100, .25); transform: translateX(-3px); }
    @keyframes wafar-map-drift { from { transform: translate(var(--pointer-x), var(--pointer-y)) scale(1.08); } to { transform: translate(calc(var(--pointer-x) - 1.5%), calc(var(--pointer-y) - 1%)) scale(1.16); } }
    @keyframes wafar-logo-enter { from { opacity: 0; transform: scale(.5) rotate(-8deg); } to { opacity: 1; transform: scale(1) rotate(0); } }
    @keyframes wafar-logo-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    @keyframes wafar-text-enter { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { .wafar-splash, .wafar-splash__logo-wrap, .wafar-splash__arabic, .wafar-splash__english, .wafar-splash__tagline, .wafar-splash__map { animation: none; transition: none; } }
</style>

<script>
    (() => {
        const splash = document.getElementById('wafar-splash');
        if (!splash) return;
        const hideSplash = () => { splash.classList.add('is-hidden'); window.setTimeout(() => splash.remove(), 500); };
        const skipButton = splash.querySelector('.wafar-splash__skip');

        skipButton?.addEventListener('click', hideSplash);
        splash.addEventListener('pointermove', (event) => {
            const x = ((event.clientX / window.innerWidth) - .5) * -10;
            const y = ((event.clientY / window.innerHeight) - .5) * -10;
            splash.style.setProperty('--pointer-x', `${x}px`);
            splash.style.setProperty('--pointer-y', `${y}px`);
        });

        // تظهر مرة عند دخول الموقع في كل جلسة متصفح، وليس عند كل تنقل داخلي.
        if (sessionStorage.getItem('wafar-splash-seen')) { splash.remove(); return; }

        sessionStorage.setItem('wafar-splash-seen', 'true');
        window.setTimeout(hideSplash, 2200);
    })();
</script>
