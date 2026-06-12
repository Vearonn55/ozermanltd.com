<section class="relative h-[40vh] min-h-[300px] overflow-hidden">
    <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-brand-950/90 via-brand-900/50 to-transparent"></div>
</section>

<article class="py-16 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-brand-400 mb-6">
            <a href="<?= url('') ?>" class="hover:text-brand-900"><?= e(t(['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'])) ?></a>
            <span class="mx-2">/</span>
            <a href="<?= url('news') ?>" class="hover:text-brand-900"><?= e(t(['en' => 'News', 'tr' => 'Haberler', 'ar' => 'الأخبار'])) ?></a>
            <span class="mx-2">/</span>
            <span class="text-brand-600"><?= e(t($article['title'])) ?></span>
        </nav>

        <div class="flex items-center gap-4 mb-6">
            <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider bg-gold-50 px-3 py-1 rounded-full">
                <?= e(t($article['category'])) ?>
            </span>
            <time class="text-sm text-brand-400"><?= e(format_date($article['publish_date'])) ?></time>
        </div>

        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-brand-900 leading-tight mb-8">
            <?= e(t($article['title'])) ?>
        </h1>

        <p class="text-lg text-brand-500 leading-relaxed mb-10 border-l-4 border-gold-500 pl-6">
            <?= e(t($article['excerpt'])) ?>
        </p>

        <div class="prose-ozerman text-brand-600 text-lg leading-relaxed">
            <?= t($article['content']) ?>
        </div>

        <!-- Share -->
        <div class="flex items-center gap-4 mt-12 pt-8 border-t border-brand-100">
            <span class="text-sm font-semibold text-brand-900">
                <?= e(t(['en' => 'Share:', 'tr' => 'Paylaş:', 'ar' => 'شارك:'])) ?>
            </span>
            <?php foreach (['LinkedIn', 'Twitter', 'Facebook'] as $platform): ?>
            <a href="#" class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-500 hover:bg-brand-900 hover:text-white transition-colors text-xs font-bold">
                <?= e(substr($platform, 0, 1)) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</article>

<?php if (!empty($related)): ?>
<section class="py-16 bg-brand-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl font-bold text-brand-900 mb-8 accent-line">
            <?= e(t(['en' => 'Related Articles', 'tr' => 'İlgili Makaleler', 'ar' => 'مقالات ذات صلة'])) ?>
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach (array_slice(array_values($related), 0, 3) as $item): ?>
            <article class="bg-white rounded-sm overflow-hidden shadow-sm card-hover border border-brand-100">
                <a href="<?= url('news/' . $item['slug']) ?>">
                    <img src="<?= e($item['image']) ?>" alt="<?= e(t($item['title'])) ?>" class="w-full h-40 object-cover" loading="lazy">
                </a>
                <div class="p-5">
                    <time class="text-xs text-brand-400"><?= e(format_date($item['publish_date'])) ?></time>
                    <h3 class="font-display text-base font-semibold text-brand-900 mt-2">
                        <a href="<?= url('news/' . $item['slug']) ?>" class="hover:text-gold-600 transition-colors">
                            <?= e(t($item['title'])) ?>
                        </a>
                    </h3>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
