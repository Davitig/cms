<?php

namespace App\Http\Controllers\Admin\CmsUser;

use App\Http\Controllers\Controller;
use App\Models\CmsUser\CmsUser;
use Closure;
use Illuminate\Container\Attributes\Storage as StorageAttr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUserImageController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, Closure $next) {
                if (! $request->user('cms')->hasFullAccess() &&
                    $request->user('cms')->id != $request->route('cms_user')) {
                    throw new AccessDeniedHttpException('Forbidden');
                }

                return $next($request);
            })
        ];
    }

    /**
     * Display the photo of the resource.
     *
     * @param  \Illuminate\Filesystem\LocalFilesystemAdapter  $filesystem
     * @param  string  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function getPhoto(#[StorageAttr('cms_users')] LocalFilesystemAdapter $filesystem, string $id)
    {
        return $this->getImage(
            $filesystem, $id, 'photo.png', public_path('assets/default/img/avatar.png')
        );
    }

    /**
     * Display the cover of the resource.
     *
     * @param  \Illuminate\Filesystem\LocalFilesystemAdapter  $filesystem
     * @param  string  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function getCover(#[StorageAttr('cms_users')] LocalFilesystemAdapter $filesystem, string $id)
    {
        return $this->getImage(
            $filesystem, $id, 'cover.png',
            public_path('assets/img/pages/profile-banner.png')
        );
    }

    /**
     * Get the image of the resource.
     *
     * @param  \Illuminate\Filesystem\LocalFilesystemAdapter  $filesystem
     * @param  string  $id
     * @param  string  $name
     * @param  string  $default
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    protected function getImage(
        LocalFilesystemAdapter $filesystem, string $id, string $name, string $default
    )
    {
        $path = $filesystem->getPathUsingId($id, 'photos/' . $name);

        if ($filesystem->exists($path)) {
            $path = $filesystem->path($path);
        } else {
            $path = $default;

            if (!(new Filesystem)->exists($path)) {
                return null;
            }
        }

        try {
            return response()->file($path, ['Cache-Control' => 'max-age=86400']); // 1 day
        } catch (FileNotFoundException) {
            return null;
        }
    }

    /**
     * Store a newly created resource image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, string $id)
    {
        (new CmsUser)->whereKey($id)->existsOr(
            fn () => throw new ModelNotFoundException
        );

        return match ($request->get('image_type')) {
            'photo' => $this->storePhoto($request, $id, null, 150),
            'cover' => $this->storeCover($request, $id, 1400),
            default => $this->response($request, false, trans('general.invalid_input'))
        };
    }

    /**
     * Store a newly created resource photo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  int|null  $scaleWidth
     * @param  int|null  $scaleHeight
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function storePhoto(
        Request $request, string $id, ?int $scaleWidth = null, ?int $scaleHeight = null
    )
    {
        $request->validate([
            'photo' => ['required', File::image()->max(1024)]
        ]);

        return $this->storeImage($request, $id, 'photo', $scaleWidth, $scaleHeight);
    }

    /**
     * Store a newly created resource cover in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  int|null  $scaleWidth
     * @param  int|null  $scaleHeight
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function storeCover(
        Request $request, string $id, ?int $scaleWidth = null, ?int $scaleHeight = null
    )
    {
        $request->validate([
            'cover' => ['required', File::image()->max(2 * 1024)]
        ]);

        return $this->storeImage($request, $id, 'cover', $scaleWidth, $scaleHeight);
    }

    /**
     * Store a resource image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  string  $name
     * @param  int|null  $scaleWidth
     * @param  int|null  $scaleHeight
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function storeImage(
        Request $request, string $id, string $name, ?int $scaleWidth = null, ?int $scaleHeight = null
    )
    {
        $filesystem = Storage::disk('cms_users');

        $filesystem->makeDirectory(
            $path = $filesystem->getPathUsingId($id, 'photos')
        );

        if ($scaleWidth || $scaleHeight) {
            Image::read($request->file($name))->scaleDown($scaleWidth, $scaleHeight)->save(
                $filesystem->path($path) . '/' . $name . '.png'
            );
        }

        return $this->response($request, true, trans('general.uploaded'));
    }

    /**
     * Remove the specified resource image from filesystem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $id)
    {
        (new CmsUser)->whereKey($id)->existsOr(
            fn () => throw new ModelNotFoundException
        );

        return match ($request->get('image_type')) {
            'photo' => $this->deleteImage($request, $id, 'photo'),
            'cover' => $this->deleteImage($request, $id, 'cover'),
            default => $this->response($request, false, trans('general.invalid_input'))
        };
    }

    /**
     * Remove the image from filesystem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  string  $name
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function deleteImage(Request $request, string $id, string $name)
    {
        $filesystem = Storage::disk('cms_users');

        $result = $filesystem->delete(
            $filesystem->getPathUsingId($id, 'photos/' . $name . '.png')
        );

        return $this->response($request, $result, trans('general.deleted'));
    }

    /**
     * Get the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $result
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function response(Request $request, bool $result, ?string $message = null)
    {
        if ($request->expectsJson()) {
            return response()->json(fill_data($result, $message));
        }

        return back()->with('alert', fill_data($result, $message));
    }
}
