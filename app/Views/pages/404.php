<section class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="text-center px-4">
        <div class="text-8xl font-display font-bold text-brand-100 mb-4">404</div>
        <h1 class="font-display text-3xl font-bold text-brand-900 mb-4">
            <?= e(t(['en' => 'Page Not Found', 'tr' => 'Sayfa Bulunamadı', 'ar' => 'الصفحة غير موجودة'])) ?>
        </h1>
        <p class="text-brand-500 mb-8 max-w-md mx-auto">
            <?= e(t([
                'en' => 'Lorem ipsum — the page you are looking for does not exist or has been moved.',
                'tr' => 'Aradığınız sayfa mevcut değil veya taşınmış olabilir.',
                'ar' => 'الصفحة التي تبحث عنها غير موجودة أو تم نقلها.',
            ])) ?>
        </p>
        <a href="<?= url('') ?>"
           class="inline-flex items-center gap-2 bg-brand-900 hover:bg-brand-800 text-white font-semibold px-8 py-3 rounded-sm transition-colors">
            <?= e(t(['en' => 'Back to Home', 'tr' => 'Ana Sayfaya Dön', 'ar' => 'العودة للرئيسية'])) ?>
        </a>
    </div>
</section>
