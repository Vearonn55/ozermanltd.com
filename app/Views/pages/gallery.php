<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Media Gallery', 'tr' => 'Medya Galerisi', 'ar' => 'معرض الوسائط'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl">
            <?= e(t([
                'en' => 'Explore our corporate events, project sites, and team culture through our media gallery.',
                'tr' => 'Medya galerimiz aracılığıyla kurumsal etkinliklerimizi, proje sahalarımızı ve ekip kültürümüzü keşfedin.',
                'ar' => 'استكشف فعالياتنا المؤسسية ومواقع المشاريع وثقافة الفريق من خلال معرض الوسائط.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28" x-data="{ activeTab: 0 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Collection Tabs -->
        <div class="flex flex-wrap gap-3 mb-12 border-b border-brand-100 pb-4">
            <?php foreach ($collections as $i => $collection): ?>
            <button @click="activeTab = <?= $i ?>"
                    :class="activeTab === <?= $i ?> ? 'text-brand-900 border-b-2 border-gold-500' : 'text-brand-400 hover:text-brand-600'"
                    class="px-4 py-2 text-sm font-semibold transition-colors">
                <?= e(t($collection['title'])) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <?php foreach ($collections as $i => $collection): ?>
        <div x-show="activeTab === <?= $i ?>" x-transition>
            <div class="mb-10">
                <h2 class="font-display text-2xl font-bold text-brand-900 mb-3"><?= e(t($collection['title'])) ?></h2>
                <p class="text-brand-500"><?= e(t($collection['description'])) ?></p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ lightbox: false, activeImage: '', activeCaption: '' }">
                <?php foreach ($collection['items'] as $item): ?>
                <button @click="lightbox = true; activeImage = '<?= e($item['image']) ?>'; activeCaption = '<?= e(t($item['caption'])) ?>'"
                        class="group relative overflow-hidden rounded-sm aspect-[4/3] card-hover">
                    <img src="<?= e($item['image']) ?>" alt="<?= e(t($item['caption'])) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-brand-950/0 group-hover:bg-brand-950/50 transition-colors flex items-end">
                        <p class="text-white text-sm font-medium p-4 translate-y-full group-hover:translate-y-0 transition-transform">
                            <?= e(t($item['caption'])) ?>
                        </p>
                    </div>
                </button>
                <?php endforeach; ?>

                <!-- Lightbox -->
                <div x-show="lightbox" x-cloak @click="lightbox = false" @keydown.escape.window="lightbox = false"
                     class="fixed inset-0 z-50 bg-black/90 flex flex-col items-center justify-center p-4">
                    <img :src="activeImage" alt="" class="max-w-full max-h-[80vh] object-contain rounded-sm">
                    <p x-text="activeCaption" class="text-white text-sm mt-4"></p>
                    <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-gold-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
