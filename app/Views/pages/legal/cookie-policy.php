<section class="bg-brand-900 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Cookie Policy', 'tr' => 'Çerez Politikası', 'ar' => 'سياسة ملفات تعريف الارتباط'])) ?>
        </h1>
    </div>
</section>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 text-brand-700">
        <div>
            <h2 class="font-display text-xl font-bold text-brand-900 mb-2"><?= e(t(['en' => 'Essential', 'tr' => 'Gerekli', 'ar' => 'أساسي'])) ?></h2>
            <p><?= e(t(['en' => 'Required for site functionality and remembering your consent choices.', 'tr' => 'Site işlevselliği ve onay tercihlerinizi hatırlamak için gereklidir.', 'ar' => 'مطلوب لوظائف الموقع وتذكر خيارات الموافقة.'])) ?></p>
        </div>
        <div>
            <h2 class="font-display text-xl font-bold text-brand-900 mb-2"><?= e(t(['en' => 'Analytics', 'tr' => 'Analitik', 'ar' => 'التحليلات'])) ?></h2>
            <p><?= e(t(['en' => 'Optional. Helps us understand page usage, clicks, scroll depth, and navigation patterns. Loaded only after consent.', 'tr' => 'İsteğe bağlı. Sayfa kullanımını anlamamıza yardımcı olur. Yalnızca onay sonrası yüklenir.', 'ar' => 'اختياري. يساعدنا على فهم استخدام الصفحة. يُحمّل فقط بعد الموافقة.'])) ?></p>
        </div>
        <div>
            <h2 class="font-display text-xl font-bold text-brand-900 mb-2"><?= e(t(['en' => 'Marketing', 'tr' => 'Pazarlama', 'ar' => 'التسويق'])) ?></h2>
            <p><?= e(t(['en' => 'Optional. Allows follow-up communications if you opt in via forms or cookie settings.', 'tr' => 'İsteğe bağlı. Formlar veya çerez ayarlarından onay verirseniz takip iletişimine izin verir.', 'ar' => 'اختياري. يسمح بالمتابعة إذا وافقت عبر النماذج أو إعدادات ملفات تعريف الارتباط.'])) ?></p>
        </div>
        <a href="<?= url('cookie-settings') ?>" class="inline-block text-gold-600 hover:text-gold-500 font-medium">
            <?= e(t(['en' => 'Open Cookie Settings', 'tr' => 'Çerez Ayarlarını Aç', 'ar' => 'فتح إعدادات ملفات تعريف الارتباط'])) ?>
        </a>
    </div>
</section>
