<section class="page-banner bg-brand-900">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('stores')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="eyebrow text-orange-400"><?= e(t(['en' => 'Retail', 'tr' => 'Mağazacılık', 'ar' => 'التجزئة'])) ?></span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mt-3 mb-4">
            <?= e(t(['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'])) ?>
        </h1>
        <p class="text-brand-300/90 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'Visit our stores to see products in person and get support from our specialist team. We offer free delivery and installation across the island.',
                'tr' => 'Mağazalarımızda ürünleri yakından inceleyebilir, uzman ekibimizden destek alabilirsiniz. Ada genelinde ücretsiz teslimat ve kurulum sunuyoruz.',
                'ar' => 'يمكنكم معاينة المنتجات في متاجرنا والحصول على دعم فريقنا المختص. نقدّم التوصيل والتركيب مجانًا في جميع أنحاء الجزيرة.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($stores as $store): ?>
            <?php partial('store-card', ['store' => $store]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-brand-100/70">
    <div class="max-w-3xl mx-auto px-4 text-center reveal">
        <p class="text-brand-600 mb-6">
            <?= e(t([
                'en' => 'You can contact us to discuss brand representation and commercial partnerships.',
                'tr' => 'Marka temsilciliği ve ticari iş birlikleri hakkında görüşmek için bizimle bağlantı kurabilirsiniz.',
                'ar' => 'يمكنكم التواصل معنا لمناقشة تمثيل العلامات والتعاونات التجارية.',
            ])) ?>
        </p>
        <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 bg-brand-900 hover:bg-brand-800 text-white font-semibold px-8 py-3 rounded-sm transition-colors duration-200">
            <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişime Geçin', 'ar' => 'اتصل بنا'])) ?>
        </a>
    </div>
</section>
