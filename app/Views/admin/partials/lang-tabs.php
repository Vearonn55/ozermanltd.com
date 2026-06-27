<?php
/**
 * @var array $languages
 * @var array|null $item
 * @var array $fields  e.g. ['title' => 'text', 'content' => 'textarea']
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
            $value = $trans[$name] ?? '';
            $label = ucwords(str_replace('_', ' ', $name));
            $inputName = "translations_{$code}_{$name}";
        ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1"><?= e($label) ?></label>
            <?php if ($type === 'textarea'): ?>
            <textarea name="<?= e($inputName) ?>" rows="6"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= e($value) ?></textarea>
            <?php else: ?>
            <input type="text" name="<?= e($inputName) ?>" value="<?= e($value) ?>"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
