<!-- Page Header -->
<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('about')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="eyebrow text-orange-400">
            <?= e(t(['en' => 'About Özerman Ticaret', 'tr' => 'Özerman Ticaret Hakkında', 'ar' => 'عن أوزرمان للتجارة'])) ?>
        </span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mt-3 mb-4">
            <?= e(t(['en' => 'About Us', 'tr' => 'Hakkımızda', 'ar' => 'من نحن'])) ?>
        </h1>
        <p class="text-brand-300/90 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'Discover our story as an importer limited company — values, vision, and the people behind the trade.',
                'tr' => 'İthalatçı limited şirket olarak hikâyemizi, değerlerimizi, vizyonumuzu ve ticaretin arkasındaki ekibi keşfedin.',
                'ar' => 'اكتشف قصتنا كشركة استيراد محدودة — القيم والرؤية والفريق وراء التجارة.',
            ])) ?>
        </p>
    </div>
</section>

<!-- Company History -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <h2 class="font-display text-3xl font-bold text-brand-800 mb-6 accent-line">
                    <?= e(t(['en' => 'Our Story', 'tr' => 'Hikâyemiz', 'ar' => 'قصتنا'])) ?>
                </h2>
                <div class="prose-ozerman text-brand-600">
                    <?= t($content['history']) ?>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 reveal">
                <?php foreach ($stats as $stat): ?>
                <div class="bg-brand-100/80 p-6 rounded-sm text-center border border-brand-200">
                    <div class="text-2xl font-display font-bold text-brand-800"><?= e($stat['value']) ?></div>
                    <div class="text-xs text-brand-500 mt-1 uppercase tracking-wider"><?= e(t($stat['label'])) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-20 bg-brand-100/50 section-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="reveal bg-brand-50 p-10 rounded-sm border border-brand-200">
                <div class="w-12 h-12 bg-brand-900 rounded-sm flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-800 mb-4">
                    <?= e(t(['en' => 'Our Vision', 'tr' => 'Vizyonumuz', 'ar' => 'رؤيتنا'])) ?>
                </h3>
                <p class="text-brand-600 leading-relaxed"><?= e(t($content['vision'])) ?></p>
            </div>
            <div class="reveal bg-brand-50 p-10 rounded-sm border border-brand-200">
                <div class="w-12 h-12 bg-brand-900 rounded-sm flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-800 mb-4">
                    <?= e(t(['en' => 'Our Mission', 'tr' => 'Misyonumuz', 'ar' => 'مهمتنا'])) ?>
                </h3>
                <p class="text-brand-600 leading-relaxed"><?= e(t($content['mission'])) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 accent-line mx-auto">
                <?= e(t(['en' => 'Core Values', 'tr' => 'Temel Değerler', 'ar' => 'قيمنا الأساسية'])) ?>
            </h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($values as $value): ?>
            <div class="reveal text-center p-8 rounded-sm border border-brand-200 hover:border-orange-400/60 transition-colors duration-200 card-hover bg-brand-50">
                <div class="w-14 h-14 bg-brand-100 rounded-sm flex items-center justify-center mx-auto mb-5">
                    <span class="text-orange-500 font-bold text-lg">●</span>
                </div>
                <h3 class="font-display text-lg font-semibold text-brand-800 mb-3"><?= e(t($value['title'])) ?></h3>
                <p class="text-sm text-brand-500 leading-relaxed"><?= e(t($value['description'])) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Management Team -->
<section class="py-20 lg:py-28 bg-brand-100/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="eyebrow">
                <?= e(t(['en' => 'Leadership', 'tr' => 'Liderlik', 'ar' => 'القيادة'])) ?>
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-brand-800 mt-3 accent-line mx-auto">
                <?= e(t(['en' => 'Management Team', 'tr' => 'Yönetim Ekibi', 'ar' => 'فريق الإدارة'])) ?>
            </h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($team as $member): ?>
            <div class="reveal bg-brand-50 rounded-sm overflow-hidden border border-brand-200 text-center card-hover">
                <img src="<?= e($member['image']) ?>" alt="<?= e(t($member['name'])) ?>"
                     class="w-full h-64 object-cover object-top" loading="lazy">
                <div class="p-6">
                    <h3 class="font-display text-lg font-semibold text-brand-800"><?= e(t($member['name'])) ?></h3>
                    <p class="text-sm text-orange-600 font-medium mt-1"><?= e(t($member['position'])) ?></p>
                    <p class="text-xs text-brand-500 mt-3 leading-relaxed line-clamp-3"><?= e(t($member['bio'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
