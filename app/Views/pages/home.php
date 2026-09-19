<!-- Hero Slider -->
<section class="relative h-[78vh] min-h-[520px] overflow-hidden bg-brand-900" x-data="{
    current: 0,
    total: <?= (int) count($slides) ?>,
    autoplay: null,
    init() {
        const urls = <?= e(json_encode(array_column($slides, 'image'))) ?>;
        urls.forEach((src) => { const img = new Image(); img.src = src; });
        this.autoplay = setInterval(() => { this.current = (this.current + 1) % this.total }, 7000);
    },
    destroy() { clearInterval(this.autoplay); }
}">
    <?php foreach ($slides as $index => $slide): ?>
    <div class="absolute inset-0 hero-slide transition-opacity duration-500 ease-out <?= $index === 0 ? 'opacity-100 z-[1]' : 'opacity-0 z-0 pointer-events-none' ?>"
         :class="current === <?= (int) $index ?> ? 'opacity-100 z-[1]' : 'opacity-0 z-0 pointer-events-none'"
         style="background-image: url('<?= e($slide['image']) ?>')"
         :aria-hidden="current !== <?= (int) $index ?>">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-950/80 via-brand-900/65 to-brand-900/30"></div>
        <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center <?= $index === 0 ? '' : 'hidden' ?>"
             :class="current === <?= (int) $index ?> ? 'block' : 'hidden'">
            <div class="max-w-2xl">
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
        <button type="button" @click="current = <?= (int) $index ?>"
                :class="current === <?= (int) $index ?> ? 'bg-orange-500 w-8' : 'bg-white/40 w-2'"
                class="h-2 rounded-full transition-all duration-300"
                aria-label="Slide <?= (int) ($index + 1) ?>"></button>
        <?php endforeach; ?>
    </div>
    <button type="button" @click="current = (current - 1 + total) % total"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-sm bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" @click="current = (current + 1) % total"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-sm bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
</section>

<!-- Track-record strip -->
<section class="bg-brand-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($stats as $stat): ?>
            <div class="text-center reveal">
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
                <span class="eyebrow"><?= e(t(['en' => 'Who We Are', 'tr' => 'Biz Kimiz', 'ar' => 'من نحن'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 mb-6 accent-line">
                    <?= e(t([
                        'en' => 'An importer limited company built on trust',
                        'tr' => 'Güven üzerine kurulu bir ithalatçı limited şirket',
                        'ar' => 'شركة استيراد محدودة مبنية على الثقة',
                    ])) ?>
                </h2>
                <p class="text-brand-600 leading-relaxed mb-6">
                    <?= e(t([
                        'en' => 'Özerman Ticaret imports and represents selected brands, then brings them to customers through wholesale pathways and retail showrooms. Our focus is disciplined trade — not a single product category.',
                        'tr' => 'Özerman Ticaret seçili markaları ithal eder, temsil eder ve toptan kanallar ile perakende showroomlar aracılığıyla müşterilere ulaştırır. Odağımız disiplinli ticarettir — tek bir ürün kategorisi değil.',
                        'ar' => 'تستورد أوزرمان للتجارة علامات مختارة وتمثلها ثم توصلها للعملاء عبر مسارات الجملة وصالات البيع. تركيزنا تجارة منضبطة — لا فئة منتج واحدة.',
                    ])) ?>
                </p>
                <a href="<?= url('about-us') ?>" class="inline-flex items-center gap-2 text-brand-800 font-semibold hover:text-orange-600 transition-colors duration-200">
                    <?= e(t(['en' => 'Learn More About Us', 'tr' => 'Hakkımızda Daha Fazla', 'ar' => 'اعرف المزيد عنا'])) ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="relative reveal">
                <img src="<?= e(content()->banner('home_mid')) ?>"
                     alt="Özerman Ticaret import operations"
                     class="rounded-sm shadow-xl w-full"
                     loading="lazy">
                <div class="absolute -bottom-5 -left-5 bg-orange-500 text-white p-5 rounded-sm shadow-lg hidden sm:block">
                    <div class="text-2xl font-display font-bold tracking-wide">IMPORT</div>
                    <div class="text-xs font-medium uppercase tracking-[0.2em] opacity-90">
                        <?= e(t(['en' => 'Trade first', 'tr' => 'Önce ticaret', 'ar' => 'التجارة أولاً'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What we do -->
<section class="py-20 lg:py-28 bg-brand-100/50 section-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="eyebrow"><?= e(t(['en' => 'What We Do', 'tr' => 'Ne Yapıyoruz', 'ar' => 'ماذا نفعل'])) ?></span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Our Operations', 'tr' => 'Operasyonlarımız', 'ar' => 'عملياتنا'])) ?>
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($operations as $i => $op): ?>
            <div class="reveal p-8 border border-brand-200 bg-brand-50/60 hover:border-orange-400/50 transition-colors duration-200">
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
                <span class="eyebrow"><?= e(t(['en' => 'Partnerships', 'tr' => 'İş Ortaklıkları', 'ar' => 'الشراكات'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line">
                    <?= e(t(['en' => 'Represented Brands', 'tr' => 'Temsil Ettiğimiz Markalar', 'ar' => 'العلامات التي نمثلها'])) ?>
                </h2>
            </div>
            <a href="<?= url('partnerships') ?>" class="text-brand-600 hover:text-orange-600 font-semibold text-sm transition-colors duration-200">
                <?= e(t(['en' => 'View all partnerships →', 'tr' => 'Tüm ortaklıkları gör →', 'ar' => 'عرض كل الشراكات ←'])) ?>
            </a>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <?php foreach ($partnerships as $partner): ?>
            <a href="<?= url('partnerships') ?>" class="group reveal card-hover block border border-brand-200 bg-brand-50 overflow-hidden">
                <div class="h-52 overflow-hidden">
                    <img src="<?= e($partner['image']) ?>" alt="<?= e($partner['name']) ?>"
                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-6">
                    <h3 class="font-display text-xl font-bold text-brand-800 group-hover:text-orange-600 transition-colors"><?= e($partner['name']) ?></h3>
                    <p class="text-xs uppercase tracking-[0.16em] text-brand-500 mt-1"><?= e(t($partner['tagline'])) ?></p>
                    <p class="text-sm text-brand-600 mt-3 line-clamp-2"><?= e(t($partner['description'])) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <p class="text-center text-sm text-brand-500 mt-10 reveal">
            <?= e(t([
                'en' => 'Our portfolio is not limited to furniture — these are current import partnerships within a broader trade model.',
                'tr' => 'Portföyümüz mobilyayla sınırlı değildir — bunlar daha geniş bir ticaret modelindeki güncel ithalat ortaklıklarıdır.',
                'ar' => 'محفظتنا لا تقتصر على الأثاث — هذه شراكات استيراد حالية ضمن نموذج تجاري أوسع.',
            ])) ?>
        </p>
    </div>
</section>

<!-- Stores teaser -->
<section class="py-20 lg:py-28 bg-brand-100/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-16 gap-4 reveal">
            <div>
                <span class="eyebrow"><?= e(t(['en' => 'Retail Presence', 'tr' => 'Perakende Varlığı', 'ar' => 'الحضور التجزئة'])) ?></span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line">
                    <?= e(t(['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'])) ?>
                </h2>
            </div>
            <a href="<?= url('our-stores') ?>" class="text-brand-600 hover:text-orange-600 font-semibold text-sm transition-colors duration-200">
                <?= e(t(['en' => 'All locations →', 'tr' => 'Tüm lokasyonlar →', 'ar' => 'كل المواقع ←'])) ?>
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach (array_slice($stores, 0, 3) as $store): ?>
            <a href="<?= url('our-stores') ?>" class="reveal group block bg-brand-50 border border-brand-200 overflow-hidden card-hover">
                <div class="h-40 overflow-hidden">
                    <img src="<?= e($store['image']) ?>" alt="<?= e(t($store['name'])) ?>"
                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-5">
                    <p class="text-xs uppercase tracking-[0.16em] text-orange-600 mb-1"><?= e(t($store['city'])) ?></p>
                    <h3 class="font-semibold text-brand-800 group-hover:text-orange-600 transition-colors"><?= e(t($store['name'])) ?></h3>
                    <p class="text-sm text-brand-500 mt-2"><?= e(t($store['address'])) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="eyebrow"><?= e(t(['en' => 'Stay Informed', 'tr' => 'Güncel Kalın', 'ar' => 'ابقَ على اطلاع'])) ?></span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Latest News', 'tr' => 'Son Haberler', 'ar' => 'آخر الأخبار'])) ?>
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($news as $article): ?>
            <article class="reveal bg-brand-50 overflow-hidden border border-brand-200 card-hover">
                <a href="<?= url('news/' . $article['slug']) ?>">
                    <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>"
                         class="w-full h-48 object-cover" loading="lazy">
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold text-orange-600 uppercase tracking-wider"><?= e(t($article['category'])) ?></span>
                        <span class="text-xs text-brand-400"><?= e(format_date($article['publish_date'])) ?></span>
                    </div>
                    <h3 class="font-display text-lg font-semibold text-brand-800 mb-2">
                        <a href="<?= url('news/' . $article['slug']) ?>" class="hover:text-orange-600 transition-colors">
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
    <div class="absolute inset-0 opacity-[0.12]">
        <img src="<?= e(content()->banner('home_cta')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-white mb-6">
            <?= e(t([
                'en' => 'Let’s talk import & retail partnerships',
                'tr' => 'İthalat ve perakende ortaklıklarını konuşalım',
                'ar' => 'لنتحدث عن شراكات الاستيراد والتجزئة',
            ])) ?>
        </h2>
        <p class="text-brand-300/90 text-lg mb-8 max-w-2xl mx-auto font-light">
            <?= e(t([
                'en' => 'Whether you represent a brand or need wholesale supply, our team is ready to explore a reliable working relationship.',
                'tr' => 'Bir markayı temsil ediyor veya toptan tedarik arıyorsanız, ekibimiz güvenilir bir iş ilişkisi için hazır.',
                'ar' => 'سواء كنت تمثل علامة أو تحتاج توريد جملة، فريقنا جاهز لاستكشاف علاقة عمل موثوقة.',
            ])) ?>
        </p>
        <a href="<?= url('contact') ?>"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-10 py-4 rounded-sm transition-colors duration-200 text-lg">
            <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişime Geçin', 'ar' => 'اتصل بنا'])) ?>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
