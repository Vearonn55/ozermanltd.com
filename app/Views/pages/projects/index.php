<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Our Projects', 'tr' => 'Projelerimiz', 'ar' => 'مشاريعنا'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl">
            <?= e(t([
                'en' => 'Lorem ipsum dolor sit amet — browse our portfolio of residential, commercial, and industrial developments worldwide.',
                'tr' => 'Lorem ipsum dolor sit amet — dünya çapında konut, ticari ve endüstriyel gelişmelerden oluşan portföyümüze göz atın.',
                'ar' => 'تصفح محفظتنا من التطويرات السكنية والتجارية والصناعية حول العالم.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28" x-data="{ filter: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filter -->
        <div class="flex flex-wrap gap-3 mb-12">
            <?php
            $filters = [
                'all' => ['en' => 'All', 'tr' => 'Tümü', 'ar' => 'الكل'],
                'ongoing' => ['en' => 'Ongoing', 'tr' => 'Devam Eden', 'ar' => 'جاري'],
                'completed' => ['en' => 'Completed', 'tr' => 'Tamamlanan', 'ar' => 'مكتمل'],
                'planning' => ['en' => 'Planning', 'tr' => 'Planlama', 'ar' => 'تخطيط'],
            ];
            foreach ($filters as $key => $label): ?>
            <button @click="filter = '<?= e($key) ?>'"
                    :class="filter === '<?= e($key) ?>' ? 'bg-brand-900 text-white' : 'bg-brand-50 text-brand-600 hover:bg-brand-100'"
                    class="px-5 py-2 text-sm font-semibold rounded-full transition-colors">
                <?= e(t($label)) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <?php foreach ($projects as $project): ?>
            <div x-show="filter === 'all' || filter === '<?= e($project['status']) ?>'"
                 x-transition
                 class="group bg-white rounded-sm overflow-hidden shadow-sm border border-brand-100 card-hover">
                <a href="<?= url('projects/' . $project['slug']) ?>">
                    <div class="relative overflow-hidden">
                        <img src="<?= e($project['image']) ?>" alt="<?= e(t($project['title'])) ?>"
                             class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        <span class="absolute top-4 left-4 badge-<?= e($project['status']) ?> text-xs font-semibold px-3 py-1 rounded-full uppercase">
                            <?= e(t($filters[$project['status']])) ?>
                        </span>
                    </div>
                </a>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gold-600 font-semibold uppercase tracking-wider"><?= e(t($project['category'])) ?></span>
                        <span class="text-xs text-brand-400"><?= e($project['location']) ?></span>
                    </div>
                    <h2 class="font-display text-xl font-semibold text-brand-900 mb-2">
                        <a href="<?= url('projects/' . $project['slug']) ?>" class="hover:text-gold-600 transition-colors">
                            <?= e(t($project['title'])) ?>
                        </a>
                    </h2>
                    <p class="text-sm text-brand-500 line-clamp-2 mb-4"><?= e(t($project['description'])) ?></p>
                    <div class="flex items-center justify-between text-sm">
                        <?php if ($project['start_price']): ?>
                        <span class="font-semibold text-brand-900">
                            <?= e(t(['en' => 'From', 'tr' => 'Başlangıç', 'ar' => 'من'])) ?>
                            £<?= number_format($project['start_price']) ?>
                        </span>
                        <?php endif; ?>
                        <span class="text-brand-400"><?= e($project['delivery_date']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
