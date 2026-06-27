<section class="bg-brand-900 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'])) ?>
        </h1>
        <p class="text-brand-300"><?= e(t(['en' => 'Last updated: June 2026', 'tr' => 'Son güncelleme: Haziran 2026', 'ar' => 'آخر تحديث: يونيو 2026'])) ?> · v<?= e(analytics_config('policy_version')) ?></p>
    </div>
</section>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose-ozerman text-brand-700 space-y-6">
        <?php foreach ([
            [
                'en' => 'Who we are',
                'tr' => 'Biz kimiz',
                'ar' => 'من نحن',
            ] => [
                'en' => 'Ozerman Ltd ("we", "us") operates ozermanltd.com. This policy explains what personal data we collect, why we collect it, and your rights.',
                'tr' => 'Ozerman Ltd ("biz") ozermanltd.com sitesini işletir. Bu politika hangi kişisel verileri topladığımızı, neden topladığımızı ve haklarınızı açıklar.',
                'ar' => 'تدير Ozerman Ltd ("نحن") موقع ozermanltd.com. توضح هذه السياسة البيانات الشخصية التي نجمعها ولماذا وحقوقك.',
            ],
            [
                'en' => 'What we collect',
                'tr' => 'Ne topluyoruz',
                'ar' => 'ما الذي نجمعه',
            ] => [
                'en' => 'With your consent we may collect: contact details you submit (name, email, phone, message), cookie preferences, page interactions (views, clicks, scroll depth), referrer, locale, browser type, and a pseudonymous visitor ID. We do not sell your data.',
                'tr' => 'Onayınızla şunları toplayabiliriz: gönderdiğiniz iletişim bilgileri, çerez tercihleri, sayfa etkileşimleri, yönlendirici, dil, tarayıcı türü ve takma ad ziyaretçi kimliği. Verilerinizi satmayız.',
                'ar' => 'بموافقتك قد نجمع: بيانات الاتصال التي ترسلها، تفضيلات ملفات تعريف الارتباط، تفاعلات الصفحة، المصدر، اللغة، نوع المتصفح، ومعرف زائر مستعار. لا نبيع بياناتك.',
            ],
            [
                'en' => 'Legal basis',
                'tr' => 'Hukuki dayanak',
                'ar' => 'الأساس القانوني',
            ] => [
                'en' => 'We process data based on your consent, contractual necessity (responding to inquiries), and legitimate interests (site security and aggregated analytics).',
                'tr' => 'Verileri onayınıza, sözleşmesel gerekliliğe (sorulara yanıt) ve meşru menfaatlere (site güvenliği ve toplu analitik) dayanarak işleriz.',
                'ar' => 'نعالج البيانات بناءً على موافقتك، الضرورة التعاقدية (الرد على الاستفسارات)، والمصالح المشروعة (أمان الموقع والتحليلات المجمعة).',
            ],
            [
                'en' => 'Retention & rights',
                'tr' => 'Saklama ve haklar',
                'ar' => 'الاحتفاظ والحقوق',
            ] => [
                'en' => 'Data is retained according to operational need and applicable law. You may request access, correction, or deletion by contacting info@ozermanltd.com. You can change cookie preferences at any time via Cookie Settings.',
                'tr' => 'Veriler operasyonel ihtiyaç ve yürürlükteki yasaya göre saklanır. info@ozermanltd.com adresinden erişim, düzeltme veya silme talep edebilirsiniz. Çerez Ayarlarından tercihlerinizi değiştirebilirsiniz.',
                'ar' => 'يتم الاحتفاظ بالبيانات وفق الحاجة التشغيلية والقانون المعمول به. يمكنك طلب الوصول أو التصحيح أو الحذف عبر info@ozermanltd.com. يمكنك تغيير تفضيلات ملفات تعريف الارتباط في أي وقت.',
            ],
        ] as $heading => $body): ?>
        <div>
            <h2 class="font-display text-2xl font-bold text-brand-900 mb-3"><?= e(t($heading)) ?></h2>
            <p><?= e(t($body)) ?></p>
        </div>
        <?php endforeach; ?>

        <p>
            <a href="<?= url('cookie-settings') ?>" class="text-gold-600 hover:text-gold-500 font-medium">
                <?= e(t(['en' => 'Manage cookie preferences', 'tr' => 'Çerez tercihlerini yönet', 'ar' => 'إدارة تفضيلات ملفات تعريف الارتباط'])) ?>
            </a>
        </p>
    </div>
</section>
