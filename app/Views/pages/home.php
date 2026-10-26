<!-- Hero Slider -->
<section class="relative h-[78vh] min-h-[520px] overflow-hidden bg-brand-900" x-data="{
    current: 0,
    total: <?= (int) count($slides) ?>,
    timer: null,
    restart() {
        clearInterval(this.timer);
        if (this.total < 2) return;
        this.timer = setInterval(() => { this.current = (this.current + 1) % this.total }, 7000);
    },
    go(index) {
        this.current = (index + this.total) % this.total;
        this.restart();
    },
    init() {
        const urls = <?= e(json_encode(array_column($slides, 'image'))) ?>;
        urls.forEach((src) => { const img = new Image(); img.src = src; });
        this.restart();
    },
    destroy() { clearInterval(this.timer); }
}">
    <?php foreach ($slides as $index => $slide): ?>
    <div class="hero-slide absolute inset-0 <?= $index === 0 ? 'is-active' : '' ?>"
         :class="{ 'is-active': current === <?= (int) $index ?> }"
         <?= $index === 0 ? '' : 'inert' ?>
         :inert="current !== <?= (int) $index ?>"
         style="background-image: url('<?= e($slide['image']) ?>')"
         :aria-hidden="current !== <?= (int) $index ?>">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-950/80 via-brand-900/65 to-brand-900/30"></div>
        <div class="hero-copy-wrap relative h-full max-w-7xl mx-auto flex items-center">
            <div class="max-w-2xl hero-copy">
                <p class="eyebrow text-orange-400 mb-4">Özerman Ticaret</p>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                    <?= e(t($slide['title'])) ?>
                </h1>
                <p class="text-lg text-brand-200 leading-relaxed mb-8 max-w-xl font-light">
                    <?= e(t($slide['subtitle'])) ?>
                </p>
                <a href="<?= url($slide['cta_url']) ?>"
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-8 py-3.5 rounded-sm transition-colors duration-200">
                    <span><?= e(t($slide['cta_text'])) ?></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        <?php foreach ($slides as $index => $_slide): ?>
        <button type="button" @click="go(<?= (int) $index ?>)"
                :class="current === <?= (int) $index ?> ? 'bg-orange-500 w-8' : 'bg-white/40 w-2'"
                class="h-2 rounded-full transition-all duration-500 ease-in-out"
                aria-label="Slide <?= (int) ($index + 1) ?>"></button>
        <?php endforeach; ?>
    </div>
    <button type="button" @click="go(current - 1)"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-sm bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" @click="go(current + 1)"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-sm bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
</section>

<!-- Track-record strip -->
<section class="bg-brand-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($stats as $i => $stat): ?>
            <div class="text-center reveal" style="transition-delay: <?= (int) $i * 70 ?>ms">
                <div class="text-3xl sm:text-4xl font-display font-bold text-orange-500 mb-2"><?= e($stat['value']) ?></div>
                <div class="text-xs text-brand-300 uppercase tracking-[0.18em]"><?= e(t($stat['label'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Who we are -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <span class="eyebrow"><?= e(t(['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 mb-6 accent-line">
                    <?= e(t([
                        'en' => 'Local expertise, a long-term approach',
                        'tr' => 'Yerel uzmanlık, uzun vadeli yaklaşım',
                        'ar' => 'خبرة محلية ونهج طويل الأمد',
                    ])) ?>
                </h2>
                <p class="text-brand-600 leading-relaxed mb-6">
                    <?= e(t([
                        'en' => 'Özerman Ticaret was founded in 2020 by Emin Özerman in Cyprus. The entrepreneurial journey that began with Bebecik Online and Daphne Baby, opened in Famagusta in 2018, forms the foundation of today’s retail and distribution structure. Today we operate our own brand Daphne Baby together with the Cyprus distributorships of Lajivert and Aymini.',
                        'tr' => 'Özerman Ticaret, 2020 yılında Emin Özerman tarafından Kıbrıs’ta kurulmuştur. Bebecik Online ile başlayan girişimcilik yolculuğu ve 2018 yılında Gazimağusa’da hayata geçirilen Daphne Baby, şirketin bugünkü perakende ve distribütörlük yapısının temelini oluşturur. Bugün kendi markamız Daphne Baby ile Lajivert ve Aymini markalarının Kıbrıs distribütörlüklerini yürütüyoruz.',
                        'ar' => 'تأسست أوزرمان للتجارة عام 2020 على يد أمين أوزرمان في قبرص. بدأت الرحلة الريادية مع بيبيسيك أونلاين ومع دافني بيبي التي افتُتحت في فاماغوستا عام 2018، وهما أساس هيكل التجزئة والتوزيع اليوم. ندير اليوم علامتنا دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني في قبرص.',
                    ])) ?>
                </p>
                <a href="<?= url('about-us') ?>" class="inline-flex items-center gap-2 text-brand-800 font-semibold hover:text-orange-600 transition-colors duration-200">
                    <?= e(t(['en' => 'Our Corporate Approach', 'tr' => 'Kurumsal Yaklaşımımız', 'ar' => 'نهجنا المؤسسي'])) ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="relative reveal">
                <img src="<?= e(content()->banner('home_mid')) ?>"
                     alt="Özerman Ticaret"
                     class="rounded-sm shadow-xl w-full"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- What we do -->
<section class="py-20 lg:py-28 bg-brand-100/50 section-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="eyebrow"><?= e(t(['en' => 'Our Activities', 'tr' => 'Faaliyetlerimiz', 'ar' => 'أنشطتنا'])) ?></span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Areas of Work', 'tr' => 'Çalışma Alanlarımız', 'ar' => 'مجالات عملنا'])) ?>
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($operations as $i => $op): ?>
            <div class="reveal p-8 border border-brand-200 bg-brand-50/60 hover:border-orange-400/50 transition-colors duration-200" style="transition-delay: <?= (int) $i * 80 ?>ms">
                <div class="text-orange-500 text-sm font-semibold tracking-[0.2em] mb-4">0<?= $i + 1 ?></div>
                <h3 class="font-display text-xl font-bold text-brand-800 mb-3"><?= e(t($op['title'])) ?></h3>
                <p class="text-sm text-brand-600 leading-relaxed"><?= e(t($op['description'])) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Represented brands -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-16 gap-4 reveal">
            <div>
                <span class="eyebrow"><?= e(t(['en' => 'Our Portfolio', 'tr' => 'Portföyümüz', 'ar' => 'محفظتنا'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line">
                    <?= e(t(['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'])) ?>
                </h2>
            </div>
            <a href="<?= url('partnerships') ?>" class="text-brand-600 hover:text-orange-600 font-semibold text-sm transition-colors duration-200">
                <?= e(t(['en' => 'View all brands →', 'tr' => 'Tüm markaları gör →', 'ar' => 'عرض كل العلامات ←'])) ?>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($partnerships as $partner): ?>
            <?php partial('brand-card', ['partner' => $partner]); ?>
            <?php endforeach; ?>
        </div>
        <p class="text-center text-sm text-brand-500 mt-10 reveal">
            <?= e(t([
                'en' => 'Daphne Baby, within Özerman Ticaret, is the company’s own brand.',
                'tr' => 'Özerman Ticaret bünyesindeki Daphne Baby, şirketimizin kendi markasıdır.',
                'ar' => 'دافني بيبي ضمن أوزرمان للتجارة هي العلامة الخاصة بالشركة.',
            ])) ?>
        </p>
    </div>
</section>

<!-- Stores teaser -->
<section class="py-20 lg:py-28 bg-brand-100/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-16 gap-4 reveal">
            <div>
                <span class="eyebrow"><?= e(t(['en' => 'Retail', 'tr' => 'Mağazacılık', 'ar' => 'التجزئة'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line">
                    <?= e(t(['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'])) ?>
                </h2>
            </div>
            <a href="<?= url('our-stores') ?>" class="text-brand-600 hover:text-orange-600 font-semibold text-sm transition-colors duration-200">
                <?= e(t(['en' => 'All locations →', 'tr' => 'Tüm mağazalar →', 'ar' => 'كل المتاجر ←'])) ?>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice($stores, 0, 3) as $store): ?>
            <?php partial('store-card', ['store' => $store]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($news)): ?>
<!-- Milestones -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 reveal">
            <span class="eyebrow"><?= e(t(['en' => 'Corporate development', 'tr' => 'Kurumsal Gelişim', 'ar' => 'التطور المؤسسي'])) ?></span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line">
                <?= e(t(['en' => 'Our Milestones', 'tr' => 'Kilometre Taşlarımız', 'ar' => 'محطاتنا'])) ?>
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($news as $i => $article): ?>
            <article class="reveal p-8 border border-brand-200 bg-brand-50 card-hover" style="transition-delay: <?= (int) $i * 80 ?>ms">
                <p class="eyebrow"><?= e(t($article['category'])) ?></p>
                <h3 class="text-xl font-bold text-brand-800 mt-3">
                    <a href="<?= url('news/' . $article['slug']) ?>" class="hover:text-orange-600 transition-colors">
                        <?= e(t($article['title'])) ?>
                    </a>
                </h3>
                <p class="mt-3 text-sm text-brand-600 leading-relaxed"><?= e(t($article['excerpt'])) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

