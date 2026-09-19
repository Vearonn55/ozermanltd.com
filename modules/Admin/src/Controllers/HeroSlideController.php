<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\HeroSlideRepository;

class HeroSlideController extends BaseAdminController
{
    private HeroSlideRepository $repo;

    public function __construct()
    {
        $this->repo = new HeroSlideRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $this->render('hero-slides.index', [
            'pageTitle' => 'Hero Slides',
            'items' => $this->repo->all(),
        ]);
    }

    public function create(): void
    {
        $this->authorize('editor');
        $this->render('hero-slides.form', [
            'pageTitle' => 'New Hero Slide',
            'item' => null,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function store(): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $data = $this->payloadFromRequest();
        if ($data === null) {
            return;
        }

        $id = $this->repo->create($data);
        if ($id <= 0) {
            flash('error', 'Please enter a title in at least one language.');
            redirect(admin_url('hero-slides/create'));
        }
        $this->logActivity('create', 'hero_slide', $id);
        flash('success', 'Hero slide created.');
        redirect(admin_url('hero-slides'));
    }

    public function edit(int $id): void
    {
        $this->authorize('editor');
        $item = $this->repo->find($id);

        if (!$item) {
            flash('error', 'Hero slide not found.');
            redirect(admin_url('hero-slides'));
        }

        $this->render('hero-slides.form', [
            'pageTitle' => 'Edit Hero Slide',
            'item' => $item,
            'languages' => $this->repo->languages(),
        ]);
    }

    public function update(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!$this->repo->find($id)) {
            flash('error', 'Hero slide not found.');
            redirect(admin_url('hero-slides'));
        }

        $data = $this->payloadFromRequest();
        if ($data === null) {
            return;
        }

        $this->repo->update($id, $data);
        $this->logActivity('update', 'hero_slide', $id);
        flash('success', 'Hero slide updated.');
        redirect(admin_url('hero-slides'));
    }

    public function toggleStatus(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $item = $this->repo->find($id);
        if ($item) {
            $active = !((int) ($item['is_active'] ?? 0) === 1);
            $this->repo->setActive($id, $active);
            $this->logActivity($active ? 'activate' : 'deactivate', 'hero_slide', $id);
            flash('success', $active ? 'Slide is now visible.' : 'Slide hidden.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('hero-slides'));
    }

    public function destroy(int $id): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        $this->repo->delete($id);
        $this->logActivity('delete', 'hero_slide', $id);
        flash('success', 'Hero slide deleted.');
        redirect(admin_url('hero-slides'));
    }

    /** @return array<string, mixed>|null */
    private function payloadFromRequest(): ?array
    {
        $translations = $this->parseTranslations($_POST, ['title', 'subtitle', 'cta_text']);
        $hasTitle = false;
        foreach ($translations as $fields) {
            if (!empty($fields['title'])) {
                $hasTitle = true;
                break;
            }
        }
        if (!$hasTitle) {
            flash('error', 'Please enter a title in at least one language.');
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('hero-slides'));
        }

        return [
            'media_id' => (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null),
            'cta_url' => trim($_POST['cta_url'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'translations' => $translations,
        ];
    }
}
