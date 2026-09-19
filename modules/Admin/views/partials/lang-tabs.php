<?php
/**
 * @var array $languages
 * @var array|null $item
 * @var array $fields  e.g. ['title' => 'text', 'content' => 'richtext', 'excerpt' => 'textarea']
 *
 * Supported field types: text, textarea, richtext.
 * Field names may map to array config: ['type' => 'text', 'label' => 'Custom', 'slugify_from' => 'title']
 */
$activeLang = $languages[0]['code'] ?? 'en';
?>
<div x-data="{ tab: '<?= e($activeLang) ?>' }" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="flex border-b border-slate-200 bg-slate-50">
        <?php foreach ($languages as $lang): ?>
        <button type="button"
                @click="tab = '<?= e($lang['code']) ?>'"
                :class="tab === '<?= e($lang['code']) ?>' ? 'border-b-2 border-blue-600 text-blue-600 bg-white' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2.5 text-sm font-medium transition-colors">
            <?= e($lang['name']) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($languages as $lang):
        $code = $lang['code'];
        $trans = ($item ?? [])['translations'][$code] ?? [];
    ?>
    <div x-show="tab === '<?= e($code) ?>'" x-cloak class="p-6 space-y-4">
        <?php foreach ($fields as $name => $type):
            $config = is_array($type) ? $type : ['type' => $type];
            $fieldType = $config['type'] ?? 'text';
            $value = $trans[$name] ?? '';
            $label = $config['label'] ?? ucwords(str_replace('_', ' ', $name));
            $inputName = "translations_{$code}_{$name}";
        ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1"><?= e($label) ?></label>
            <?php if ($fieldType === 'richtext'): ?>
            <div class="js-richtext rounded-lg border border-slate-300 overflow-hidden" dir="<?= $code === 'ar' ? 'rtl' : 'ltr' ?>">
                <textarea name="<?= e($inputName) ?>" class="js-richtext-source hidden"><?= e($value) ?></textarea>
                <div class="js-richtext-editor bg-white" style="min-height: 200px;"></div>
            </div>
            <?php elseif ($fieldType === 'code'):
                $codeLang = $config['lang'] ?? 'html';
            ?>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs" style="color:var(--cui-muted)"><?= e(strtoupper($codeLang)) ?></span>
                    <button type="button" class="btn btn-secondary js-insert-media" data-target="<?= e($inputName) ?>" data-lang="<?= e($codeLang) ?>">Insert media</button>
                </div>
                <textarea name="<?= e($inputName) ?>" class="form-control code-editor js-code-editor" data-code-lang="<?= e($codeLang) ?>"
                          spellcheck="false" rows="<?= $codeLang === 'html' ? 16 : 10 ?>"><?= e($value) ?></textarea>
            </div>
            <?php elseif ($fieldType === 'textarea'): ?>
            <textarea name="<?= e($inputName) ?>" rows="6"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= e($value) ?></textarea>
            <?php else: ?>
            <input type="text" name="<?= e($inputName) ?>" value="<?= e($value) ?>"
                   <?= $name === 'title' && $code === $activeLang ? 'required' : '' ?>
                   <?= $name === 'title' || $name === 'name' ? 'data-slug-source="' . e($code) . '"' : '' ?>
                   <?= $name === 'slug' ? 'data-slug-target="' . e($code) . '"' : '' ?>
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
