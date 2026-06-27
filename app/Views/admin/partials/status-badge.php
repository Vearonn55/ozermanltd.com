<?php
$colors = [
    'published' => 'bg-emerald-100 text-emerald-800',
    'draft' => 'bg-slate-100 text-slate-700',
    'new' => 'bg-blue-100 text-blue-800',
    'received' => 'bg-blue-100 text-blue-800',
    'read' => 'bg-amber-100 text-amber-800',
    'replied' => 'bg-emerald-100 text-emerald-800',
    'archived' => 'bg-slate-100 text-slate-500',
    'ongoing' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-emerald-100 text-emerald-800',
    'planning' => 'bg-purple-100 text-purple-800',
    'active' => 'bg-emerald-100 text-emerald-800',
    'inactive' => 'bg-red-100 text-red-800',
];
$class = $colors[$status] ?? 'bg-slate-100 text-slate-700';
?>
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $class ?>">
    <?= e(ucfirst($status)) ?>
</span>
