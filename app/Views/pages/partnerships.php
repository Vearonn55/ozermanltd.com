<section class="page-banner bg-brand-900">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('partnerships')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="eyebrow text-orange-400"><?= e(t(['en' => 'Our Portfolio', 'tr' => 'Portföyümüz', 'ar' => 'محفظتنا'])) ?></span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mt-3 mb-4">
            <?= e(t(['en' => 'Our Brands', 'tr' => 'Markalarımız', 'ar' => 'علاماتنا'])) ?>
        </h1>
        <p class="text-brand-300/90 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'We develop our own brand Daphne Baby together with our Lajivert and Aymini distributorships within the same corporate structure.',
                'tr' => 'Kendi markamız Daphne Baby ile Lajivert ve Aymini distribütörlüklerimizi aynı kurumsal yapı içinde geliştiriyoruz.',
                'ar' => 'نطوّر علامتنا الخاصة دافني بيبي إلى جانب وكالات لاجيڤيرت وأيميني ضمن الهيكل المؤسسي نفسه.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <?php foreach ($partnerships as $i => $partner): ?>
        <article class="reveal grid lg:grid-cols-2 gap-10 items-center">
            <div class="overflow-hidden border border-brand-200 <?= $i % 2 === 1 ? 'lg:order-2' : '' ?>">
                <img src="<?= e($partner['image']) ?>" alt="<?= e($partner['name']) ?>"
                     class="w-full h-72 object-cover" loading="lazy">
            </div>
            <div class="<?= $i % 2 === 1 ? 'lg:order-1' : '' ?>">
                <p class="eyebrow mb-3"><?= e(t($partner['tagline'])) ?></p>
                <h2 class="font-display text-3xl font-bold text-brand-800 mb-4 accent-line"><?= e($partner['name']) ?></h2>
                <p class="text-brand-600 leading-relaxed mb-6"><?= e(t($partner['description'])) ?></p>
                <?php if (!empty($partner['url'])): ?>
                <a href="<?= e($partner['url']) ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 text-brand-800 font-semibold hover:text-orange-600 transition-colors duration-200">
                    <?= e(t(['en' => 'Visit brand website', 'tr' => 'Marka sitesini ziyaret et', 'ar' => 'زيارة موقع العلامة'])) ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="py-16 bg-brand-900">
    <div class="max-w-3xl mx-auto px-4 text-center reveal">
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mb-4">
            <?= e(t([
                'en' => 'Get in touch for new partnerships',
                'tr' => 'Yeni iş birlikleri için iletişime geçin',
                'ar' => 'تواصلوا معنا لشراكات جديدة',
            ])) ?>
        </h2>
        <p class="text-brand-300 mb-8 font-light">
            <?= e(t([
                'en' => 'You can contact us to discuss brand representation and commercial partnerships.',
                'tr' => 'Marka temsilciliği ve ticari iş birlikleri hakkında görüşmek için bizimle bağlantı kurabilirsiniz.',
                'ar' => 'يمكنكم التواصل معنا لمناقشة تمثيل العلامات والتعاونات التجارية.',
            ])) ?>
        </p>
        <a href="<?= url('contact') ?>"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-8 py-3.5 rounded-sm transition-colors duration-200">
            <?= e(t(['en' => 'Start a Conversation', 'tr' => 'Görüşmeye Başlayın', 'ar' => 'ابدأ محادثة'])) ?>
        </a>
    </div>
</section>
