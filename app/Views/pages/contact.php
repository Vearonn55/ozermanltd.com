<section class="page-banner bg-brand-900">
    <div class="absolute inset-0 opacity-20">
        <img src="<?= e(content()->banner('contact')) ?>" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'])) ?>
        </h1>
        <p class="text-brand-300/90 text-lg max-w-2xl font-light">
            <?= e(t([
                'en' => 'You can contact us to discuss brand representation and commercial partnerships.',
                'tr' => 'Marka temsilciliği ve ticari iş birlikleri hakkında görüşmek için bizimle bağlantı kurabilirsiniz.',
                'ar' => 'يمكنكم التواصل معنا لمناقشة تمثيل العلامات والتعاونات التجارية.',
            ])) ?>
        </p>
    </div>
</section>

<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div>
                <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-sm mb-8">
                    <?= e(t([
                        'en' => 'Thank you for your message. We will get back to you shortly.',
                        'tr' => 'Mesajınız için teşekkür ederiz. En kısa sürede size dönüş yapacağız.',
                        'ar' => 'شكرًا لرسالتك. سنعود إليك قريبًا.',
                    ])) ?>
                </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-sm mb-8">
                    <?php if (($_GET['error'] ?? '') === 'submit'): ?>
                    <?= e(t([
                        'en' => 'We could not send your message just now. Please wait a moment and try again.',
                        'tr' => 'Mesajınız şu anda gönderilemedi. Lütfen kısa bir süre sonra yeniden deneyin.',
                        'ar' => 'تعذّر إرسال رسالتك الآن. يرجى الانتظار قليلًا ثم المحاولة مرة أخرى.',
                    ])) ?>
                    <?php else: ?>
                    <?= e(t([
                        'en' => 'Please accept the privacy policy, fill in the required fields, and make sure cookies are enabled.',
                        'tr' => 'Lütfen gizlilik politikasını kabul edin, zorunlu alanları doldurun ve çerezlerin etkin olduğundan emin olun.',
                        'ar' => 'يرجى قبول سياسة الخصوصية وملء الحقول المطلوبة والتأكد من تفعيل ملفات تعريف الارتباط.',
                    ])) ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <h2 class="font-display text-2xl font-bold text-brand-800 mb-6 accent-line">
                    <?= e(t(['en' => 'Send Us a Message', 'tr' => 'Bize Mesaj Gönderin', 'ar' => 'أرسل لنا رسالة'])) ?>
                </h2>
                <form action="<?= url('contact') ?>" method="POST" class="space-y-5" data-track-form="contact">
                    <?= csrf_field() ?>
                    <input type="hidden" name="visitor_uuid" id="visitor_uuid" value="">
                    <div class="hp-field" aria-hidden="true">
                        <label for="company_website">Website</label>
                        <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Full Name', 'tr' => 'Ad Soyad', 'ar' => 'الاسم الكامل'])) ?> *
                            </label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                                   placeholder="<?= e(t(['en' => 'John Doe', 'tr' => 'Ad Soyad', 'ar' => 'الاسم'])) ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Email', 'tr' => 'E-posta', 'ar' => 'البريد الإلكتروني'])) ?> *
                            </label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                                   placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Phone', 'tr' => 'Telefon', 'ar' => 'الهاتف'])) ?>
                            </label>
                            <input type="tel" name="phone"
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Subject', 'tr' => 'Konu', 'ar' => 'الموضوع'])) ?>
                            </label>
                            <select name="subject"
                                    class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                <option><?= e(t(['en' => 'General inquiry', 'tr' => 'Genel soru', 'ar' => 'استفسار عام'])) ?></option>
                                <option><?= e(t(['en' => 'Brand partnership', 'tr' => 'Marka iş birliği', 'ar' => 'تعاون علامة'])) ?></option>
                                <option><?= e(t(['en' => 'Store visit', 'tr' => 'Mağaza ziyareti', 'ar' => 'زيارة المتجر'])) ?></option>
                                <option><?= e(t(['en' => 'After-sales support', 'tr' => 'Satış sonrası destek', 'ar' => 'دعم ما بعد البيع'])) ?></option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-700 mb-1.5">
                            <?= e(t(['en' => 'Message', 'tr' => 'Mesaj', 'ar' => 'الرسالة'])) ?> *
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none"
                                  placeholder="<?= e(t(['en' => 'How can we help you?', 'tr' => 'Size nasıl yardımcı olabiliriz?', 'ar' => 'كيف يمكننا مساعدتك؟'])) ?>"></textarea>
                    </div>
                    <div class="space-y-3 border border-brand-200 bg-brand-50 p-4 rounded-sm">
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_privacy" required class="mt-1">
                            <span>
                                <?= e(t(['en' => 'I have read and accept the', 'tr' => 'Okudum ve kabul ediyorum:', 'ar' => 'قرأت وأوافق على'])) ?>
                                <a href="<?= url('privacy-policy') ?>" class="text-orange-600 hover:underline"><?= e(t(['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'])) ?></a>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_analytics" class="mt-1">
                            <span><?= e(t(['en' => 'I agree to analytics and usage event tracking to improve the website.', 'tr' => 'Web sitesini geliştirmek için analitik ve kullanım olayı takibini kabul ediyorum.', 'ar' => 'أوافق على التحليلات وتتبع استخدام الموقع لتحسينه.'])) ?></span>
                        </label>
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_marketing" class="mt-1">
                            <span><?= e(t(['en' => 'I agree to receive messages from Özerman Ticaret.', 'tr' => 'Özerman Ticaret’ten ileti almayı kabul ediyorum.', 'ar' => 'أوافق على تلقي رسائل من أوزرمان للتجارة.'])) ?></span>
                        </label>
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto bg-brand-900 hover:bg-brand-800 text-white font-semibold px-10 py-3.5 rounded-sm transition-colors">
                        <?= e(t(['en' => 'Send Message', 'tr' => 'Mesaj Gönder', 'ar' => 'إرسال الرسالة'])) ?>
                    </button>
                </form>
            </div>

            <!-- Office Locations -->
            <div>
                <h2 class="font-display text-2xl font-bold text-brand-800 mb-6 accent-line">
                    <?= e(t(['en' => 'Our Offices', 'tr' => 'Ofislerimiz', 'ar' => 'مكاتبنا'])) ?>
                </h2>
                <div class="space-y-6">
                    <?php foreach ($offices as $office): ?>
                    <div class="bg-brand-50 p-6 rounded-sm border border-brand-200 <?= $office['is_headquarters'] ? 'ring-2 ring-orange-400/30' : '' ?>">
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="font-display text-lg font-semibold text-brand-800"><?= e(t($office['label'])) ?></h3>
                            <?php if ($office['is_headquarters']): ?>
                            <span class="text-xs bg-orange-500 text-white font-semibold px-2 py-0.5 rounded-sm uppercase">HQ</span>
                            <?php endif; ?>
                        </div>
                        <ul class="space-y-2 text-sm text-brand-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <?= e(trim(implode(', ', array_filter([t_filled($office['address']), t_filled($office['city']), t_filled($office['country'])], static fn(string $part): bool => $part !== '')))) ?>
                            </li>
                            <?php if (!empty($office['phone'])): ?>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:<?= e($office['phone']) ?>" class="hover:text-orange-600 transition-colors"><?= e($office['phone']) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($office['whatsapp'])): ?>
                            <li class="flex items-center gap-2">
                                <span class="w-4 text-center text-orange-500 shrink-0 text-xs font-bold">W</span>
                                <a href="https://wa.me/<?= e(preg_replace('/\D+/', '', (string) $office['whatsapp'])) ?>" class="hover:text-orange-600 transition-colors" target="_blank" rel="noopener"><?= e($office['whatsapp']) ?></a>
                            </li>
                            <?php endif; ?>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:<?= e($office['email']) ?>" class="hover:text-orange-600 transition-colors"><?= e($office['email']) ?></a>
                            </li>
                            <?php if (t_filled($office['hours']) !== ''): ?>
                            <li class="text-brand-400 text-xs mt-2"><?= e(t_filled($office['hours'])) ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
