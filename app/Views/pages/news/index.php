<section class="page-banner bg-brand-900">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('news')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Corporate', 'tr' => 'Kurumsal', 'ar' => 'المؤسسي'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'From Bebecik Online and Daphne Baby to Özerman Ticaret, founded in 2020.',
                'tr' => 'Bebecik Online ve Daphne Baby’den 2020’de kurulan Özerman Ticaret’e.',
                'ar' => 'من بيبيسيك أونلاين ودافني بيبي إلى أوزرمان للتجارة التي تأسست عام 2020.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <?php if ($articles === []): ?>
        <p class="text-brand-500">
            <?= e(t([
                'en' => 'Milestones will appear here soon.',
                'tr' => 'Kilometre taşları yakında burada yer alacaktır.',
                'ar' => 'ستظهر المحطات هنا قريبًا.',
            ])) ?>
        </p>
        <?php endif; ?>
        <?php foreach ($articles as $i => $article): ?>
        <article class="reveal grid lg:grid-cols-2 gap-10 items-center">
            <a href="<?= url('news/' . $article['slug']) ?>" class="overflow-hidden border border-brand-200 <?= $i % 2 === 1 ? 'lg:order-2' : '' ?>">
                <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>"
                     class="w-full h-72 object-cover" loading="lazy">
            </a>
            <div class="<?= $i % 2 === 1 ? 'lg:order-1' : '' ?>">
                <div class="flex items-center gap-3 mb-3">
                    <span class="eyebrow"><?= e(t($article['category'])) ?></span>
                    <span class="text-xs text-brand-400"><?= e(format_date($article['publish_date'])) ?></span>
                </div>
                <h2 class="font-display text-3xl font-bold text-brand-800 mb-4 accent-line">
                    <a href="<?= url('news/' . $article['slug']) ?>" class="hover:text-orange-600 transition-colors">
                        <?= e(t($article['title'])) ?>
                    </a>
                </h2>
                <p class="text-brand-600 leading-relaxed mb-6"><?= e(t($article['excerpt'])) ?></p>
                <a href="<?= url('news/' . $article['slug']) ?>" class="inline-flex items-center gap-2 text-brand-800 font-semibold hover:text-orange-600 transition-colors duration-200">
                    <?= e(t(['en' => 'Read More →', 'tr' => 'Devamını Oku →', 'ar' => 'اقرأ المزيد ←'])) ?>
                </a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
