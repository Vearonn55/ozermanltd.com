<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Business Sectors', 'tr' => 'İş Sektörleri', 'ar' => 'قطاعات الأعمال'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl">
            <?= e(t([
                'en' => 'Lorem ipsum dolor sit amet — explore our diversified portfolio of business sectors driving growth across global markets.',
                'tr' => 'Lorem ipsum dolor sit amet — küresel pazarlarda büyümeyi destekleyen çeşitlendirilmiş iş sektörleri portföyümüzü keşfedin.',
                'ar' => 'استكشف محفظتنا المتنوعة من قطاعات الأعمال التي تدفع النمو عبر الأسواق العالمية.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
            <?php foreach ($sectors as $sector): ?>
            <a href="<?= url('sectors/' . $sector['slug']) ?>"
               class="group flex gap-6 bg-white rounded-sm overflow-hidden shadow-sm border border-brand-100 card-hover">
                <div class="w-2/5 shrink-0 overflow-hidden">
                    <img src="<?= e($sector['image']) ?>" alt="<?= e(t($sector['name'])) ?>"
                         class="w-full h-full object-cover min-h-[200px] group-hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-6 flex flex-col justify-center">
                    <h2 class="font-display text-2xl font-semibold text-brand-900 group-hover:text-gold-600 transition-colors mb-3">
                        <?= e(t($sector['name'])) ?>
                    </h2>
                    <p class="text-sm text-brand-500 leading-relaxed line-clamp-3 mb-4"><?= e(t($sector['overview'])) ?></p>
                    <span class="text-sm font-semibold text-brand-900 group-hover:text-gold-600 transition-colors">
                        <?= e(t(['en' => 'Learn More →', 'tr' => 'Daha Fazla →', 'ar' => 'اعرف المزيد ←'])) ?>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
