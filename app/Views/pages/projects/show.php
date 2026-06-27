<section class="relative h-[50vh] min-h-[400px] overflow-hidden">
    <img src="<?= e($project['image']) ?>" alt="<?= e(t($project['title'])) ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-brand-950/90 via-brand-900/60 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <nav class="text-sm text-brand-300 mb-4">
                <a href="<?= url('') ?>" class="hover:text-white"><?= e(t(['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'])) ?></a>
                <span class="mx-2">/</span>
                <a href="<?= url('projects') ?>" class="hover:text-white"><?= e(t(['en' => 'Projects', 'tr' => 'Projeler', 'ar' => 'المشاريع'])) ?></a>
                <span class="mx-2">/</span>
                <span class="text-white"><?= e(t($project['title'])) ?></span>
            </nav>
            <div class="flex flex-wrap items-center gap-4 mb-3">
                <span class="badge-<?= e($project['status']) ?> text-xs font-semibold px-3 py-1 rounded-full uppercase">
                    <?= e(t(['ongoing' => ['en' => 'Ongoing', 'tr' => 'Devam Ediyor', 'ar' => 'جاري'], 'completed' => ['en' => 'Completed', 'tr' => 'Tamamlandı', 'ar' => 'مكتمل'], 'planning' => ['en' => 'Planning', 'tr' => 'Planlama', 'ar' => 'تخطيط']][$project['status']])) ?>
                </span>
                <span class="text-gold-400 text-sm font-medium"><?= e(t($project['category'])) ?></span>
            </div>
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white"><?= e(t($project['title'])) ?></h1>
            <p class="text-brand-300 mt-2"><?= e($project['location']) ?> · <?= e($project['delivery_date']) ?></p>
        </div>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-16">
            <div class="lg:col-span-2">
                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Project Overview', 'tr' => 'Proje Özeti', 'ar' => 'نظرة عامة على المشروع'])) ?>
                </h2>
                <p class="text-brand-600 leading-relaxed text-lg mb-10"><?= e(t($project['description'])) ?></p>

                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Key Features', 'tr' => 'Temel Özellikler', 'ar' => 'الميزات الرئيسية'])) ?>
                </h2>
                <div class="text-brand-600 leading-relaxed whitespace-pre-line"><?= e(t($project['features'])) ?></div>
            </div>
            <div>
                <div class="bg-brand-50 p-8 rounded-sm border border-brand-100 sticky top-28 space-y-6">
                    <h3 class="font-display text-lg font-semibold text-brand-900">
                        <?= e(t(['en' => 'Project Details', 'tr' => 'Proje Detayları', 'ar' => 'تفاصيل المشروع'])) ?>
                    </h3>
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-brand-400 uppercase tracking-wider text-xs"><?= e(t(['en' => 'Location', 'tr' => 'Konum', 'ar' => 'الموقع'])) ?></dt>
                            <dd class="text-brand-900 font-medium mt-1"><?= e($project['location']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-brand-400 uppercase tracking-wider text-xs"><?= e(t(['en' => 'Delivery', 'tr' => 'Teslimat', 'ar' => 'التسليم'])) ?></dt>
                            <dd class="text-brand-900 font-medium mt-1"><?= e($project['delivery_date']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-brand-400 uppercase tracking-wider text-xs"><?= e(t(['en' => 'Category', 'tr' => 'Kategori', 'ar' => 'الفئة'])) ?></dt>
                            <dd class="text-brand-900 font-medium mt-1"><?= e(t($project['category'])) ?></dd>
                        </div>
                        <?php if ($project['start_price']): ?>
                        <div>
                            <dt class="text-brand-400 uppercase tracking-wider text-xs"><?= e(t(['en' => 'Starting Price', 'tr' => 'Başlangıç Fiyatı', 'ar' => 'السعر الابتدائي'])) ?></dt>
                            <dd class="text-brand-900 font-medium mt-1 text-lg">£<?= number_format($project['start_price']) ?></dd>
                        </div>
                        <?php endif; ?>
                    </dl>
                    <a href="<?= url('contact') ?>"
                       class="block w-full text-center bg-gold-500 hover:bg-gold-400 text-brand-950 font-semibold px-6 py-3 rounded-sm transition-colors">
                        <?= e(t(['en' => 'Enquire Now', 'tr' => 'Şimdi Bilgi Alın', 'ar' => 'استفسر الآن'])) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery placeholder -->
<section class="py-20 bg-brand-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl font-bold text-brand-900 mb-10 accent-line">
            <?= e(t(['en' => 'Project Gallery', 'tr' => 'Proje Galerisi', 'ar' => 'معرض المشروع'])) ?>
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" x-data="{ lightbox: false, activeImage: '' }">
            <?php
            $galleryImages = [
                $project['image'],
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&q=80',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&q=80',
                'https://images.unsplash.com/photo-1600566753190-17f0baa8a6cb?w=600&q=80',
                'https://images.unsplash.com/photo-1600573472550-8090b5e0745e?w=600&q=80',
            ];
            foreach ($galleryImages as $img): ?>
            <button @click="lightbox = true; activeImage = '<?= e($img) ?>'"
                    class="overflow-hidden rounded-sm aspect-[4/3] group">
                <img src="<?= e($img) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
            </button>
            <?php endforeach; ?>

            <!-- Lightbox -->
            <div x-show="lightbox" x-cloak @click="lightbox = false" @keydown.escape.window="lightbox = false"
                 class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4">
                <img :src="activeImage" alt="" class="max-w-full max-h-full object-contain rounded-sm">
                <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-gold-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
