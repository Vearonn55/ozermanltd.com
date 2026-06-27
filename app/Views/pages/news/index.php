<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'News & Announcements', 'tr' => 'Haberler ve Duyurular', 'ar' => 'الأخبار والإعلانات'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl">
            <?= e(t([
                'en' => 'Stay up to date with the latest news, project updates, and corporate announcements from Ozerman Ltd.',
                'tr' => 'Ozerman Ltd\'den son haberler, proje güncellemeleri ve kurumsal duyurularla güncel kalın.',
                'ar' => 'ابق على اطلاع بآخر الأخبار وتحديثات المشاريع والإعلانات المؤسسية من أوزرمان.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-8">
                <?php foreach ($articles as $i => $article): ?>
                <article class="<?= $i === 0 ? 'grid md:grid-cols-2 gap-8 bg-brand-50 p-6 rounded-sm border border-brand-100' : 'flex gap-6 pb-8 border-b border-brand-100' ?> card-hover">
                    <a href="<?= url('news/' . $article['slug']) ?>" class="<?= $i === 0 ? '' : 'w-48 shrink-0' ?> overflow-hidden rounded-sm">
                        <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>"
                             class="w-full <?= $i === 0 ? 'h-64' : 'h-32' ?> object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                    </a>
                    <div class="flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-semibold text-gold-600 uppercase"><?= e(t($article['category'])) ?></span>
                            <span class="text-xs text-brand-400"><?= e(format_date($article['publish_date'])) ?></span>
                        </div>
                        <h2 class="font-display <?= $i === 0 ? 'text-2xl' : 'text-lg' ?> font-semibold text-brand-900 mb-2">
                            <a href="<?= url('news/' . $article['slug']) ?>" class="hover:text-gold-600 transition-colors">
                                <?= e(t($article['title'])) ?>
                            </a>
                        </h2>
                        <p class="text-sm text-brand-500 <?= $i === 0 ? '' : 'line-clamp-2' ?>"><?= e(t($article['excerpt'])) ?></p>
                        <?php if ($i === 0): ?>
                        <a href="<?= url('news/' . $article['slug']) ?>" class="text-sm font-semibold text-brand-900 hover:text-gold-600 mt-4 transition-colors">
                            <?= e(t(['en' => 'Read More →', 'tr' => 'Devamını Oku →', 'ar' => 'اقرأ المزيد ←'])) ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Sidebar -->
            <aside>
                <div class="bg-brand-50 p-6 rounded-sm border border-brand-100 sticky top-28">
                    <h3 class="font-display text-lg font-semibold text-brand-900 mb-4">
                        <?= e(t(['en' => 'Search', 'tr' => 'Ara', 'ar' => 'بحث'])) ?>
                    </h3>
                    <form class="mb-8">
                        <input type="text" placeholder="<?= e(t(['en' => 'Search articles...', 'tr' => 'Makale ara...', 'ar' => 'ابحث في المقالات...'])) ?>"
                               class="w-full px-4 py-2.5 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                    </form>
                    <h3 class="font-display text-lg font-semibold text-brand-900 mb-4">
                        <?= e(t(['en' => 'Categories', 'tr' => 'Kategoriler', 'ar' => 'الفئات'])) ?>
                    </h3>
                    <ul class="space-y-2">
                        <?php
                        $categories = array_unique(array_map(fn($a) => t($a['category']), $articles));
                        foreach ($categories as $cat): ?>
                        <li>
                            <a href="#" class="text-sm text-brand-600 hover:text-gold-600 transition-colors"><?= e($cat) ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
