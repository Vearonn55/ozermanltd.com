<section class="bg-brand-900 py-20 lg:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1423666639041-f56000c27a9e?w=1600&q=80" alt="" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            <?= e(t(['en' => 'Contact Us', 'tr' => 'İletişim', 'ar' => 'اتصل بنا'])) ?>
        </h1>
        <p class="text-brand-300 text-lg max-w-2xl">
            <?= e(t([
                'en' => 'Lorem ipsum dolor sit amet — we would love to hear from you. Reach out to discuss partnerships, projects, or general inquiries.',
                'tr' => 'Lorem ipsum dolor sit amet — sizden haber almak isteriz. Ortaklıklar, projeler veya genel sorular için bize ulaşın.',
                'ar' => 'يسعدنا التواصل معكم. تواصل معنا لمناقشة الشراكات والمشاريع أو الاستفسارات العامة.',
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
                    <?= e(t([
                        'en' => 'Please accept the privacy policy and ensure cookies are enabled to submit the form.',
                        'tr' => 'Lütfen gizlilik politikasını kabul edin ve formu göndermek için çerezlerin etkin olduğundan emin olun.',
                        'ar' => 'يرجى قبول سياسة الخصوصية والتأكد من تفعيل ملفات تعريف الارتباط لإرسال النموذج.',
                    ])) ?>
                </div>
                <?php endif; ?>

                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Send Us a Message', 'tr' => 'Bize Mesaj Gönderin', 'ar' => 'أرسل لنا رسالة'])) ?>
                </h2>
                <form action="<?= url('contact') ?>" method="POST" class="space-y-5" data-track-form="contact">
                    <input type="hidden" name="visitor_uuid" id="visitor_uuid" value="">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Full Name', 'tr' => 'Ad Soyad', 'ar' => 'الاسم الكامل'])) ?> *
                            </label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent"
                                   placeholder="<?= e(t(['en' => 'John Doe', 'tr' => 'Ad Soyad', 'ar' => 'الاسم'])) ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Email', 'tr' => 'E-posta', 'ar' => 'البريد الإلكتروني'])) ?> *
                            </label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent"
                                   placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Phone', 'tr' => 'Telefon', 'ar' => 'الهاتف'])) ?>
                            </label>
                            <input type="tel" name="phone"
                                   class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-700 mb-1.5">
                                <?= e(t(['en' => 'Subject', 'tr' => 'Konu', 'ar' => 'الموضوع'])) ?>
                            </label>
                            <select name="subject"
                                    class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                                <option><?= e(t(['en' => 'General Inquiry', 'tr' => 'Genel Soru', 'ar' => 'استفسار عام'])) ?></option>
                                <option><?= e(t(['en' => 'Project Inquiry', 'tr' => 'Proje Sorusu', 'ar' => 'استفسار عن مشروع'])) ?></option>
                                <option><?= e(t(['en' => 'Partnership', 'tr' => 'Ortaklık', 'ar' => 'شراكة'])) ?></option>
                                <option><?= e(t(['en' => 'Media & Press', 'tr' => 'Medya ve Basın', 'ar' => 'الإعلام والصحافة'])) ?></option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-700 mb-1.5">
                            <?= e(t(['en' => 'Message', 'tr' => 'Mesaj', 'ar' => 'الرسالة'])) ?> *
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-brand-200 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent resize-none"
                                  placeholder="<?= e(t(['en' => 'How can we help you?', 'tr' => 'Size nasıl yardımcı olabiliriz?', 'ar' => 'كيف يمكننا مساعدتك؟'])) ?>"></textarea>
                    </div>
                    <div class="space-y-3 border border-brand-100 bg-brand-50 p-4 rounded-sm">
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_privacy" required class="mt-1">
                            <span>
                                <?= e(t(['en' => 'I have read and accept the', 'tr' => 'Okudum ve kabul ediyorum:', 'ar' => 'قرأت وأوافق على'])) ?>
                                <a href="<?= url('privacy-policy') ?>" class="text-gold-600 hover:underline"><?= e(t(['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası', 'ar' => 'سياسة الخصوصية'])) ?></a>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_analytics" class="mt-1">
                            <span><?= e(t(['en' => 'I agree to analytics and usage event tracking to improve the website.', 'tr' => 'Web sitesini geliştirmek için analitik ve kullanım olayı takibini kabul ediyorum.', 'ar' => 'أوافق على التحليلات وتتبع استخدام الموقع لتحسينه.'])) ?></span>
                        </label>
                        <label class="flex items-start gap-3 text-sm text-brand-700">
                            <input type="checkbox" name="consent_marketing" class="mt-1">
                            <span><?= e(t(['en' => 'I agree to receive marketing communications from Ozerman Ltd.', 'tr' => 'Ozerman Ltd\'den pazarlama iletişimleri almayı kabul ediyorum.', 'ar' => 'أوافق على تلقي اتصالات تسويقية من Ozerman Ltd.'])) ?></span>
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
                <h2 class="font-display text-2xl font-bold text-brand-900 mb-6 accent-line">
                    <?= e(t(['en' => 'Our Offices', 'tr' => 'Ofislerimiz', 'ar' => 'مكاتبنا'])) ?>
                </h2>
                <div class="space-y-6">
                    <?php foreach ($offices as $office): ?>
                    <div class="bg-brand-50 p-6 rounded-sm border border-brand-100 <?= $office['is_headquarters'] ? 'ring-2 ring-gold-400/30' : '' ?>">
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="font-display text-lg font-semibold text-brand-900"><?= e(t($office['label'])) ?></h3>
                            <?php if ($office['is_headquarters']): ?>
                            <span class="text-xs bg-gold-500 text-brand-950 font-semibold px-2 py-0.5 rounded-full uppercase">HQ</span>
                            <?php endif; ?>
                        </div>
                        <ul class="space-y-2 text-sm text-brand-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <?= e(t($office['address'])) ?>, <?= e($office['city']) ?>, <?= e(t($office['country'])) ?>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:<?= e($office['phone']) ?>" class="hover:text-gold-600 transition-colors"><?= e($office['phone']) ?></a>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:<?= e($office['email']) ?>" class="hover:text-gold-600 transition-colors"><?= e($office['email']) ?></a>
                            </li>
                            <li class="text-brand-400 text-xs mt-2"><?= e(t($office['hours'])) ?></li>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Map placeholder -->
                <div class="mt-8 bg-brand-100 rounded-sm overflow-hidden h-64 flex items-center justify-center">
                    <div class="text-center text-brand-400">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <p class="text-sm"><?= e(t(['en' => 'Google Maps Integration', 'tr' => 'Google Haritalar Entegrasyonu', 'ar' => 'تكامل خرائط جوجل'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
