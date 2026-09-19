<section class="relative h-[40vh] min-h-[300px] overflow-hidden">
    <img src="<?= e($article['image']) ?>" alt="<?= e(t($article['title'])) ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-brand-950/90 via-brand-900/50 to-transparent"></div>
</section>

<article class="py-16 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-brand-400 mb-6">
            <a href="<?= url('') ?>" class="hover:text-brand-800"><?= e(t(['en' => 'Home', 'tr' => 'Ana Sayfa', 'ar' => 'الرئيسية'])) ?></a>
            <span class="mx-2">/</span>
            <a href="<?= url('news') ?>" class="hover:text-brand-800"><?= e(t(['en' => 'News', 'tr' => 'Haberler', 'ar' => 'الأخبار'])) ?></a>
            <span class="mx-2">/</span>
            <span class="text-brand-600"><?= e(t($article['title'])) ?></span>
        </nav>

        <div class="flex items-center gap-4 mb-6">
            <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider bg-gold-50 px-3 py-1 rounded-full">
                <?= e(t($article['category'])) ?>
            </span>
            <time class="text-sm text-brand-400"><?= e(format_date($article['publish_date'])) ?></time>
        </div>

        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-brand-800 leading-tight mb-8">
            <?= e(t($article['title'])) ?>
        </h1>

        <p class="text-lg text-brand-500 leading-relaxed mb-10 border-l-4 border-gold-500 pl-6">
            <?= e(t($article['excerpt'])) ?>
        </p>

        <div class="prose-ozerman text-brand-600 text-lg leading-relaxed">
            <?= t($article['content']) ?>
        </div>

        <?php
        $comments = $comments ?? [];
        $commentSuccess = get_flash('success');
        $commentError = get_flash('error');
        ?>
        <section class="mt-14 pt-10 border-t border-brand-200" id="reviews">
            <h2 class="font-display text-2xl font-bold text-brand-800 mb-6">
                <?= e(t(['en' => 'Reviews', 'tr' => 'Yorumlar', 'ar' => 'المراجعات'])) ?>
            </h2>

            <?php if ($commentSuccess): ?>
            <div class="mb-4 rounded-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm"><?= e($commentSuccess) ?></div>
            <?php endif; ?>
            <?php if ($commentError): ?>
            <div class="mb-4 rounded-sm bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm"><?= e($commentError) ?></div>
            <?php endif; ?>

            <?php if (empty($comments)): ?>
            <p class="text-sm text-brand-500 mb-8"><?= e(t(['en' => 'No reviews yet. Be the first to leave one.', 'tr' => 'Henüz yorum yok. İlk yorumu siz yazın.', 'ar' => 'لا توجد مراجعات بعد. كن أول من يكتب.'])) ?></p>
            <?php else: ?>
            <ul class="space-y-5 mb-10">
                <?php foreach ($comments as $comment): ?>
                <li class="border border-brand-200 bg-brand-50/60 p-5 rounded-sm">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <strong class="text-brand-800"><?= e($comment['author_name']) ?></strong>
                        <time class="text-xs text-brand-400"><?= e(format_date($comment['created_at'])) ?></time>
                    </div>
                    <p class="text-brand-600 text-sm leading-relaxed whitespace-pre-wrap"><?= e($comment['content']) ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <form method="POST" action="<?= url('news/' . ($article['slug'] ?? '') . '/review') ?>" class="space-y-4 max-w-xl">
                <?= csrf_field() ?>
                <h3 class="font-semibold text-brand-800"><?= e(t(['en' => 'Leave a review', 'tr' => 'Yorum yazın', 'ar' => 'اترك مراجعة'])) ?></h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-brand-600 mb-1"><?= e(t(['en' => 'Name', 'tr' => 'Ad', 'ar' => 'الاسم'])) ?></label>
                        <input type="text" name="author_name" required maxlength="100" class="w-full border border-brand-200 rounded-sm px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-brand-600 mb-1"><?= e(t(['en' => 'Email', 'tr' => 'E-posta', 'ar' => 'البريد'])) ?></label>
                        <input type="email" name="author_email" required maxlength="180" class="w-full border border-brand-200 rounded-sm px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-brand-600 mb-1"><?= e(t(['en' => 'Your review', 'tr' => 'Yorumunuz', 'ar' => 'مراجعتك'])) ?></label>
                    <textarea name="content" required rows="4" maxlength="2000" class="w-full border border-brand-200 rounded-sm px-3 py-2 text-sm"></textarea>
                </div>
                <button type="submit" class="bg-brand-900 hover:bg-brand-800 text-white font-semibold px-6 py-2.5 rounded-sm text-sm">
                    <?= e(t(['en' => 'Submit for review', 'tr' => 'İncelemeye gönder', 'ar' => 'إرسال للمراجعة'])) ?>
                </button>
                <p class="text-xs text-brand-400"><?= e(t(['en' => 'Reviews appear after moderation.', 'tr' => 'Yorumlar onaylandıktan sonra görünür.', 'ar' => 'تظهر المراجعات بعد الإشراف.'])) ?></p>
            </form>
        </section>

        <!-- Share -->
        <div class="flex items-center gap-4 mt-12 pt-8 border-t border-brand-200">
            <span class="text-sm font-semibold text-brand-800">
                <?= e(t(['en' => 'Share:', 'tr' => 'Paylaş:', 'ar' => 'شارك:'])) ?>
            </span>
            <?php foreach (['LinkedIn', 'Twitter', 'Facebook'] as $platform): ?>
            <a href="#" class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-500 hover:bg-brand-900 hover:text-white transition-colors text-xs font-bold">
                <?= e(substr($platform, 0, 1)) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</article>

<?php if (!empty($related)): ?>
<section class="py-16 bg-brand-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl font-bold text-brand-800 mb-8 accent-line">
            <?= e(t(['en' => 'Related Articles', 'tr' => 'İlgili Makaleler', 'ar' => 'مقالات ذات صلة'])) ?>
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach (array_slice(array_values($related), 0, 3) as $item): ?>
            <article class="bg-brand-50 rounded-sm overflow-hidden shadow-sm card-hover border border-brand-200">
                <a href="<?= url('news/' . $item['slug']) ?>">
                    <img src="<?= e($item['image']) ?>" alt="<?= e(t($item['title'])) ?>" class="w-full h-40 object-cover" loading="lazy">
                </a>
                <div class="p-5">
                    <time class="text-xs text-brand-400"><?= e(format_date($item['publish_date'])) ?></time>
                    <h3 class="font-display text-base font-semibold text-brand-800 mt-2">
                        <a href="<?= url('news/' . $item['slug']) ?>" class="hover:text-gold-600 transition-colors">
                            <?= e(t($item['title'])) ?>
                        </a>
                    </h3>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
