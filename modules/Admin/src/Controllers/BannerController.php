<?php

declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Repositories\BannerRepository;
use Admin\Repositories\MediaRepository;

class BannerController extends BaseAdminController
{
    private BannerRepository $repo;

    public function __construct()
    {
        $this->repo = new BannerRepository();
    }

    public function index(): void
    {
        $this->authorize('editor');
        $this->render('banners.index', [
            'pageTitle' => 'Banners & Images',
            'items' => $this->repo->all(),
        ]);
    }

    public function update(string $location): void
    {
        $this->authorize('editor');
        $this->verifyCsrf();

        if (!array_key_exists($location, BannerRepository::LOCATIONS)) {
            flash('error', 'Unknown banner location.');
            redirect(admin_url('banners'));
        }

        $mediaId = (($_POST['media_id'] ?? '') !== '' ? (int) $_POST['media_id'] : null);
        $fallback = trim((string) ($_POST['fallback_url'] ?? ''));

        if ($mediaId !== null) {
            $media = (new MediaRepository())->find($mediaId);
            if (!$media) {
                flash('error', 'Selected media was not found.');
                redirect(admin_url('banners'));
            }
        }

        $this->repo->save($location, $mediaId, $fallback !== '' ? $fallback : null);
        $this->logActivity('update', 'site_banner', null, ['location' => $location]);
        flash('success', 'Banner updated: ' . BannerRepository::LOCATIONS[$location]);
        redirect(admin_url('banners'));
    }
}
