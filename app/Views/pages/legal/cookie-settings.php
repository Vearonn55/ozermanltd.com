<section class="bg-brand-900 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Cookie Settings', 'tr' => 'Çerez Ayarları', 'ar' => 'إعدادات ملفات تعريف الارتباط'])) ?>
        </h1>
    </div>
</section>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-brand-50 border border-brand-100 rounded-lg p-8 space-y-6">
            <p class="text-brand-700">
                <?= e(t(['en' => 'Manage how Ozerman Ltd collects and uses your data. Essential cookies are always enabled.', 'tr' => 'Ozerman Ltd\'nin verilerinizi nasıl topladığını ve kullandığını yönetin. Gerekli çerezler her zaman etkindir.', 'ar' => 'إدارة كيفية جمع Ozerman Ltd لبياناتك واستخدامها. ملفات تعريف الارتباط الأساسية مفعّلة دائمًا.'])) ?>
            </p>

            <label class="flex items-start gap-3">
                <input type="checkbox" checked disabled class="mt-1">
                <span>
                    <strong><?= e(t(['en' => 'Essential', 'tr' => 'Gerekli', 'ar' => 'أساسي'])) ?></strong><br>
                    <span class="text-sm text-brand-600"><?= e(t(['en' => 'Required for consent memory and core site features.', 'tr' => 'Onay hafızası ve temel site özellikleri için gereklidir.', 'ar' => 'مطلوب لتذكر الموافقة والميزات الأساسية.'])) ?></span>
                </span>
            </label>

            <label class="flex items-start gap-3">
                <input type="checkbox" id="settings-analytics" data-consent-toggle="analytics" class="mt-1">
                <span>
                    <strong><?= e(t(['en' => 'Analytics & event tracking', 'tr' => 'Analitik ve olay takibi', 'ar' => 'التحليلات وتتبع الأحداث'])) ?></strong><br>
                    <span class="text-sm text-brand-600"><?= e(t(['en' => 'Page views, clicks, scroll depth, navigation events.', 'tr' => 'Sayfa görüntülemeleri, tıklamalar, kaydırma derinliği, gezinme olayları.', 'ar' => 'مشاهدات الصفحة والنقرات وعمق التمرير وأحداث التنقل.'])) ?></span>
                </span>
            </label>

            <label class="flex items-start gap-3">
                <input type="checkbox" id="settings-marketing" data-consent-toggle="marketing" class="mt-1">
                <span>
                    <strong><?= e(t(['en' => 'Marketing communications', 'tr' => 'Pazarlama iletişimleri', 'ar' => 'الاتصالات التسويقية'])) ?></strong><br>
                    <span class="text-sm text-brand-600"><?= e(t(['en' => 'Allows follow-up emails if you also provide contact details.', 'tr' => 'İletişim bilgilerinizi verirseniz takip e-postalarına izin verir.', 'ar' => 'يسمح برسائل المتابعة إذا قدمت أيضًا بيانات الاتصال.'])) ?></span>
                </span>
            </label>

            <div class="flex flex-wrap gap-3 pt-4">
                <button type="button" data-consent-save class="bg-brand-900 hover:bg-brand-800 text-white font-semibold px-6 py-3 rounded-sm">
                    <?= e(t(['en' => 'Save preferences', 'tr' => 'Tercihleri kaydet', 'ar' => 'حفظ التفضيلات'])) ?>
                </button>
                <button type="button" data-consent-action="accept-all" class="bg-gold-500 hover:bg-gold-400 text-brand-950 font-semibold px-6 py-3 rounded-sm">
                    <?= e(t(['en' => 'Accept all', 'tr' => 'Tümünü kabul et', 'ar' => 'قبول الكل'])) ?>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.OzermanConsent) return;
    var consent = window.OzermanConsent.getConsent();
    if (consent) {
        document.getElementById('settings-analytics').checked = !!consent.analytics;
        document.getElementById('settings-marketing').checked = !!consent.marketing;
    }
    document.querySelector('[data-consent-save]').addEventListener('click', function () {
        window.OzermanConsent.savePreferences({
            essential: true,
            analytics: document.getElementById('settings-analytics').checked,
            marketing: document.getElementById('settings-marketing').checked,
            source: 'settings_page'
        });
        alert(<?= json_encode(t(['en' => 'Preferences saved.', 'tr' => 'Tercihler kaydedildi.', 'ar' => 'تم حفظ التفضيلات.'])) ?>);
    });
});
</script>
