<div
    id="cookie-consent"
    class="fixed bottom-0 inset-x-0 z-50 hidden"
    data-policy-version="<?= e(analytics_config('policy_version')) ?>"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
        <div class="bg-brand-900 text-white rounded-lg shadow-2xl border border-brand-700 p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start gap-5">
                <div class="flex-1 text-sm text-brand-100 leading-relaxed">
                    <p class="font-semibold text-white mb-2">
                        <?= e(t(['en' => 'Your privacy choices', 'tr' => 'Gizlilik tercihleriniz', 'ar' => 'خيارات الخصوصية'])) ?>
                    </p>
                    <p>
                        <?= e(t([
                            'en' => 'We use essential cookies for site functionality. With your permission we also collect analytics events and optional marketing preferences. Read our Privacy Policy and Cookie Policy.',
                            'tr' => 'Site işlevselliği için gerekli çerezler kullanıyoruz. İzin verirseniz analitik olayları ve isteğe bağlı pazarlama tercihlerini de toplarız.',
                            'ar' => 'نستخدم ملفات تعريف الارتباط الأساسية لوظائف الموقع. بإذنك نجمع أيضًا أحداث التحليلات وتفضيلات التسويق الاختيارية.',
                        ])) ?>
                    </p>
                    <p class="mt-2">
                        <a href="<?= url('privacy-policy') ?>" class="text-gold-400 hover:underline"><?= e(t(['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'])) ?></a>
                        ·
                        <a href="<?= url('cookie-policy') ?>" class="text-gold-400 hover:underline"><?= e(t(['en' => 'Cookie Policy', 'tr' => 'Çerez Politikası', 'ar' => 'سياسة ملفات تعريف الارتباط'])) ?></a>
                    </p>
                </div>

                <div id="cookie-consent-panel" class="hidden lg:block w-full lg:w-96 space-y-3">
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" checked disabled class="mt-1">
                        <span><?= e(t(['en' => 'Essential (required)', 'tr' => 'Gerekli', 'ar' => 'أساسي (مطلوب)'])) ?></span>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" id="consent-analytics" data-consent-toggle="analytics" class="mt-1">
                        <span><?= e(t(['en' => 'Analytics & all usage events', 'tr' => 'Analitik ve tüm kullanım olayları', 'ar' => 'التحليلات وجميع أحداث الاستخدام'])) ?></span>
                    </label>
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" id="consent-marketing" data-consent-toggle="marketing" class="mt-1">
                        <span><?= e(t(['en' => 'Marketing communications', 'tr' => 'Pazarlama iletişimleri', 'ar' => 'الاتصالات التسويقية'])) ?></span>
                    </label>
                </div>

                <div class="flex flex-wrap gap-3 shrink-0">
                    <button type="button" data-consent-action="manage" class="px-4 py-2 text-sm font-medium rounded-md border border-brand-600 text-brand-100 hover:bg-brand-800 transition-colors">
                        <?= e(t(['en' => 'Manage', 'tr' => 'Yönet', 'ar' => 'إدارة'])) ?>
                    </button>
                    <button type="button" data-consent-action="reject" class="px-4 py-2 text-sm font-medium rounded-md border border-brand-600 text-brand-100 hover:bg-brand-800 transition-colors">
                        <?= e(t(['en' => 'Reject optional', 'tr' => 'Reddet', 'ar' => 'رفض الاختياري'])) ?>
                    </button>
                    <button type="button" data-consent-action="accept-all" class="px-4 py-2 text-sm font-medium rounded-md bg-gold-500 text-brand-950 hover:bg-gold-400 transition-colors">
                        <?= e(t(['en' => 'Accept all', 'tr' => 'Tümünü kabul et', 'ar' => 'قبول الكل'])) ?>
                    </button>
                    <button type="button" data-consent-action="save" class="hidden px-4 py-2 text-sm font-medium rounded-md bg-brand-700 text-white hover:bg-brand-600 transition-colors">
                        <?= e(t(['en' => 'Save choices', 'tr' => 'Seçimleri kaydet', 'ar' => 'حفظ الخيارات'])) ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
window.OZERMAN_ANALYTICS = {
    policyVersion: <?= json_encode(analytics_config('policy_version')) ?>,
    plausibleDomain: <?= json_encode(analytics_config('plausible.domain')) ?>,
    requireConsent: <?= analytics_config('plausible.require_consent') ? 'true' : 'false' ?>,
    locale: <?= json_encode(app_locale()) ?>
};
</script>
<script defer src="<?= asset('js/consent.js') ?>"></script>
<script defer src="<?= asset('js/analytics.js') ?>"></script>
