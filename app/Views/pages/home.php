<!-- Hero Slider -->
<section class="relative h-[85vh] min-h-[600px] overflow-hidden" x-data="{
    current: 0,
    slides: <?= e(json_encode(array_map(fn($s) => [
        'title' => t($s['title']),
        'subtitle' => t($s['subtitle']),
        'cta_text' => t($s['cta_text']),
        'cta_url' => url($s['cta_url']),
        'image' => $s['image'],
    ], $slides))) ?>,
    autoplay: null,
    init() {
        this.autoplay = setInterval(() => { this.current = (this.current + 1) % this.slides.length }, 6000);
    },
    destroy() { clearInterval(this.autoplay); }
}">
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="current === index"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 hero-slide"
             :style="'background-image: url(' + slide.image + ')'">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950/90 via-brand-900/70 to-brand-900/40"></div>
            <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <div class="max-w-2xl">
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6" x-text="slide.title"></h1>
                    <p class="text-lg text-brand-200 leading-relaxed mb-8 max-w-xl" x-text="slide.subtitle"></p>
                    <a :href="slide.cta_url"
                       class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-400 text-brand-950 font-semibold px-8 py-3.5 rounded-sm transition-colors">
                        <span x-text="slide.cta_text"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </template>

    <!-- Slider Controls -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        <template x-for="(slide, index) in slides" :key="'dot-'+index">
            <button @click="current = index"
                    :class="current === index ? 'bg-gold-500 w-8' : 'bg-white/40 w-2'"
                    class="h-2 rounded-full transition-all duration-300"></button>
        </template>
    </div>
    <button @click="current = (current - 1 + slides.length) % slides.length"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="current = (current + 1) % slides.length"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
</section>

<!-- Stats Bar -->
<section class="bg-brand-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($stats as $stat): ?>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-display font-bold text-gold-500 mb-2"><?= e($stat['value']) ?></div>
                <div class="text-sm text-brand-300 uppercase tracking-wider"><?= e(t($stat['label'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Company Introduction -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-gold-500 text-sm font-semibold uppercase tracking-widest">
                    <?= e(t(['en' => 'Who We Are', 'tr' => 'Biz Kimiz', 'ar' => 'من نحن'])) ?>
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-900 mt-3 mb-6 accent-line">
                    <?= e(t([
                        'en' => 'A Global Business Group Built on Trust & Excellence',
                        'tr' => 'Güven ve Mükemmellik Üzerine Kurulu Küresel İş Grubu',
                        'ar' => 'مجموعة أعمال عالمية مبنية على الثقة والتميز',
                    ])) ?>
                </h2>
                <p class="text-brand-600 leading-relaxed mb-6">
                    <?= e(t([
                        'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ozerman Ltd is a diversified international business group with operations spanning trading, construction, real estate, manufacturing, logistics, and energy across 28 countries worldwide.',
                        'tr' => 'Lorem ipsum dolor sit amet. Ozerman Ltd, dünya genelinde 28 ülkede ticaret, inşaat, gayrimenkul, üretim, lojistik ve enerji alanlarında faaliyet gösteren çeşitlendirilmiş uluslararası bir iş grubudur.',
                        'ar' => 'أوزرمان المحدودة مجموعة أعمال دولية متنوعة تعمل في التجارة والبناء والعقارات والتصنيع واللوجستيات والطاقة في 28 دولة حول العالم.',
                    ])) ?>
                </p>
                <a href="<?= url('about-us') ?>" class="inline-flex items-center gap-2 text-brand-900 font-semibold hover:text-gold-600 transition-colors">
                    <?= e(t(['en' => 'Learn More About Us', 'tr' => 'Hakkımızda Daha Fazla', 'ar' => 'اعرف المزيد عنا'])) ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80"
                     alt="Ozerman Ltd headquarters"
                     class="rounded-sm shadow-2xl w-full"
                     loading="lazy">
                <div class="absolute -bottom-6 -left-6 bg-gold-500 text-brand-950 p-6 rounded-sm shadow-lg hidden sm:block">
                    <div class="text-3xl font-display font-bold">36+</div>
                    <div class="text-sm font-medium">
                        <?= e(t(['en' => 'Years of Excellence', 'tr' => 'Yıllık Mükemmellik', 'ar' => 'سنوات من التميز'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Sectors -->
<section class="py-20 lg:py-28 bg-brand-50 section-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-gold-500 text-sm font-semibold uppercase tracking-widest">
                <?= e(t(['en' => 'What We Do', 'tr' => 'Ne Yapıyoruz', 'ar' => 'ماذا نفعل'])) ?>
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-900 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Our Business Sectors', 'tr' => 'İş Sektörlerimiz', 'ar' => 'قطاعات أعمالنا'])) ?>
            </h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($sectors as $sector): ?>
            <a href="<?= url('sectors/' . $sector['slug']) ?>"
               class="group bg-white rounded-sm overflow-hidden shadow-sm card-hover border border-brand-100">
                <div class="h-48 overflow-hidden">
                    <img src="<?= e($sector['image']) ?>" alt="<?= e(t($sector['name'])) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-6">
                    <h3 class="font-display text-xl font-semibold text-brand-900 group-hover:text-gold-600 transition-colors mb-2">
                        <?= e(t($sector['name'])) ?>
                    </h3>
                    <p class="text-sm text-brand-500 line-clamp-2"><?= e(mb_substr(t($sector['overview']), 0, 120)) ?>...</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-12">
            <a href="<?= url('sectors') ?>" class="inline-flex items-center gap-2 border-2 border-brand-900 text-brand-900 hover:bg-brand-900 hover:text-white font-semibold px-8 py-3 rounded-sm transition-colors">
                <?= e(t(['en' => 'View All Sectors', 'tr' => 'Tüm Sektörleri Gör', 'ar' => 'عرض جميع القطاعات'])) ?>
            </a>
        </div>
    </div>
</section>

<!-- Featured Projects -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-16 gap-4">
            <div>
                <span class="text-gold-500 text-sm font-semibold uppercase tracking-widest">
                    <?= e(t(['en' => 'Our Work', 'tr' => 'Çalışmalarımız', 'ar' => 'أعمالنا'])) ?>
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-900 mt-3 accent-line">
                    <?= e(t(['en' => 'Featured Projects', 'tr' => 'Öne Çıkan Projeler', 'ar' => 'المشاريع المميزة'])) ?>
                </h2>
            </div>
            <a href="<?= url('projects') ?>" class="text-brand-600 hover:text-gold-600 font-semibold text-sm transition-colors">
                <?= e(t(['en' => 'View All Projects →', 'tr' => 'Tüm Projeleri Gör →', 'ar' => 'عرض جميع المشاريع ←'])) ?>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice(array_values($projects), 0, 3) as $project): ?>
            <a href="<?= url('projects/' . $project['slug']) ?>" class="group card-hover">
                <div class="relative overflow-hidden rounded-sm mb-4">
                    <img src="<?= e($project['image']) ?>" alt="<?= e(t($project['title'])) ?>"
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    <span class="absolute top-4 left-4 badge-<?= e($project['status']) ?> text-xs font-semibold px-3 py-1 rounded-full uppercase">
                        <?= e(t(['ongoing' => ['en' => 'Ongoing', 'tr' => 'Devam Ediyor', 'ar' => 'جاري'], 'completed' => ['en' => 'Completed', 'tr' => 'Tamamlandı', 'ar' => 'مكتمل'], 'planning' => ['en' => 'Planning', 'tr' => 'Planlama', 'ar' => 'تخطيط']][$project['status']])) ?>
                    </span>
                </div>
                <span class="text-xs text-gold-600 font-semibold uppercase tracking-wider"><?= e(t($project['category'])) ?></span>
                <h3 class="font-display text-xl font-semibold text-brand-900 mt-1 group-hover:text-gold-600 transition-colors">
                    <?= e(t($project['title'])) ?>
                </h3>
                <p class="text-sm text-brand-500 mt-1"><?= e($project['location']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-20 lg:py-28 bg-brand-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-gold-500 text-sm font-semibold uppercase tracking-widest">
                <?= e(t(['en' => 'Stay Informed', 'tr' => 'Bilgilendirilin', 'ar' => 'ابق على اطلاع'])) ?>
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-900 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Latest News', 'tr' => 'Son Haberler', 'ar' => 'آخر الأخبار'])) ?>
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($news as $article): ?>
            <article class="bg-white rounded-sm overflow-hidden shadow-sm card-hover border border-brand-100">
                <a href="<?= url('news/' . $article['slug']) ?>">
                    <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>"
                         class="w-full h-48 object-cover" loading="lazy">
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold text-gold-600 uppercase"><?= e(t($article['category'])) ?></span>
                        <span class="text-xs text-brand-400"><?= e(format_date($article['publish_date'])) ?></span>
                    </div>
                    <h3 class="font-display text-lg font-semibold text-brand-900 mb-2">
                        <a href="<?= url('news/' . $article['slug']) ?>" class="hover:text-gold-600 transition-colors">
                            <?= e(t($article['title'])) ?>
                        </a>
                    </h3>
                    <p class="text-sm text-brand-500 line-clamp-3"><?= e(t($article['excerpt'])) ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-20 bg-brand-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-white mb-6">
            <?= e(t([
                'en' => 'Ready to Partner With Us?',
                'tr' => 'Bizimle Ortak Olmaya Hazır mısınız?',
                'ar' => 'هل أنت مستعد للشراكة معنا؟',
            ])) ?>
        </h2>
        <p class="text-brand-300 text-lg mb-8 max-w-2xl mx-auto">
            <?= e(t([
                'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Get in touch with our team to explore partnership opportunities and project inquiries.',
                'tr' => 'Lorem ipsum dolor sit amet. Ortaklık fırsatlarını ve proje sorularını keşfetmek için ekibimizle iletişime geçin.',
                'ar' => 'تواصل مع فريقنا لاستكشاف فرص الشراكة واستفسارات المشاريع.',
            ])) ?>
        </p>
        <a href="<?= url('contact') ?>"
           class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-400 text-brand-950 font-semibold px-10 py-4 rounded-sm transition-colors text-lg">
            <?= e(t(['en' => 'Contact Us Today', 'tr' => 'Bugün İletişime Geçin', 'ar' => 'اتصل بنا اليوم'])) ?>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
