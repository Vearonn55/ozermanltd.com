<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('stores')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="eyebrow text-orange-400"><?= e(t(['en' => 'Retail Presence', 'tr' => 'Perakende Varlığı', 'ar' => 'الحضور التجزئة'])) ?></span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mt-3 mb-4">
            <?= e(t(['en' => 'Our Stores', 'tr' => 'Mağazalarımız', 'ar' => 'متاجرنا'])) ?>
        </h1>
        <p class="text-brand-300/90 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'Visit our showrooms to experience imported collections in person. Store details below are placeholders and will be updated with final addresses.',
                'tr' => 'İthal koleksiyonları yerinde deneyimlemek için showroomlarımızı ziyaret edin. Aşağıdaki bilgiler geçicidir; kesin adresler yakında güncellenecektir.',
                'ar' => 'زوروا صالات العرض لتجربة المجموعات المستوردة. التفاصيل أدناه مؤقتة وستُحدَّث لاحقًا.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($stores as $store): ?>
            <article class="reveal bg-brand-50 border border-brand-200 overflow-hidden card-hover">
                <div class="h-48 overflow-hidden">
                    <img src="<?= e($store['image']) ?>" alt="<?= e(t($store['name'])) ?>"
                         class="w-full h-full object-cover" loading="lazy">
                </div>
                <div class="p-6">
                    <p class="text-xs uppercase tracking-[0.16em] text-orange-600 mb-2"><?= e(t($store['city'])) ?></p>
                    <h2 class="font-display text-xl font-bold text-brand-800 mb-3"><?= e(t($store['name'])) ?></h2>
                    <ul class="space-y-2.5 text-sm text-brand-600">
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span><?= e(t($store['address'])) ?></span>
                        </li>
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:<?= e($store['phone']) ?>" class="hover:text-orange-600 transition-colors"><?= e($store['phone']) ?></a>
                        </li>
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:<?= e($store['email']) ?>" class="hover:text-orange-600 transition-colors"><?= e($store['email']) ?></a>
                        </li>
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span><?= e(t($store['hours'])) ?></span>
                        </li>
                    </ul>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-brand-100/70">
    <div class="max-w-3xl mx-auto px-4 text-center reveal">
        <p class="text-brand-600 mb-6">
            <?= e(t([
                'en' => 'Looking for wholesale or brand placement in our stores? Reach out to our team.',
                'tr' => 'Mağazalarımızda toptan satış veya marka yerleşimi mi arıyorsunuz? Ekibimize ulaşın.',
                'ar' => 'هل تبحث عن جملة أو تواجد علامة في متاجرنا؟ تواصل مع فريقنا.',
            ])) ?>
        </p>
        <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 bg-brand-900 hover:bg-brand-800 text-white font-semibold px-8 py-3 rounded-sm transition-colors duration-200">
            <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişime Geçin', 'ar' => 'اتصل بنا'])) ?>
        </a>
    </div>
</section>
