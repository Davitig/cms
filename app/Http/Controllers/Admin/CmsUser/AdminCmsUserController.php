<?php

namespace App\Http\Controllers\Admin\CmsUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsUserRequest;
use App\Models\CmsUser\CmsUser;
use App\Models\CmsUser\CmsUserRole;
use App\Scopes\Models\CmsUserFilter;
use Closure;
use Illuminate\Container\Attributes\Storage as StorageAttr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUserController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CmsUser $model)
    {
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, Closure $next) {
                if (!$request->user('cms')->hasFullAccess()) {
                    throw new AccessDeniedHttpException('Forbidden');
                }

                return $next($request);
            }, only: ['create', 'store']),
            new Middleware(function (Request $request, Closure $next) {
                if (!$request->user('cms')->hasFullAccess() &&
                    $request->user('cms')->id != $request->route('cms_user')) {
                    throw new AccessDeniedHttpException('Forbidden');
                }

                return $next($request);
            }, only: ['show', 'edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $data['items'] = $this->model->when(!$request->user('cms')->hasFullAccess(),
            function ($q) use ($request) {
                $request->offsetUnset('role');

                return $q->whereKey($request->user('cms')->id);
            }
        )->joinRole()
            ->tap(new CmsUserFilter($request))
            ->paginate(50);

        $data['roles'] = (new CmsUserRole)->pluck('role', 'id')->toArray();

        return view('admin.cms-users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['current'] = $this->model;

        $data['roles'] = (new CmsUserRole)->pluck('role', 'id');

        return view('admin.cms-users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CmsUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CmsUserRequest $request)
    {
        $input = $request->all();

        if ($request->filled('password')) {
            $input['password'] = bcrypt($input['password']);
        }

        $model = $this->model->create($input);

        $this->storePhoto($model, $request->file('photo'));

        return redirect(cms_route('cmsUsers.edit', [$model->id]))
            ->with('alert', fill_data(true, trans('general.created')));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $id)
    {
        $data['current'] = $this->model->joinRole()->findOrFail($id);

        return view('admin.cms-users.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $data['current'] = $this->model->joinRole()->findOrFail($id);

        $data['roles'] = (new CmsUserRole)->pluck('role', 'id');

        $data['photoExists'] = $this->photoExists($id);

        return view('admin.cms-users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CmsUserRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(CmsUserRequest $request, string $id)
    {
        $input = $request->all();

        if ($request->filled('password')) {
            $input['password'] = bcrypt($input['password']);
        }

        $model = tap($this->model->findOrFail($id))->update($input);

        if ($request->boolean('remove_photo')) {
            $this->deletePhoto($model);
        } else {
            $input['photo_updated'] = $this->storePhoto($model, $request->file('photo'));
        }

        unset($input['password'], $input['password_confirmation']);

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        if (request()->user('cms')->hasFullAccess()) {
            if (request()->user('cms')->id == $id) {
                throw new AccessDeniedHttpException('Forbidden');
            }
        } else {
            throw new AccessDeniedHttpException('Forbidden');
        }

        if ($result = $this->model->findOrFail($id)->delete()) {
            $this->deleteFilesDirectory($id);
        }

        if (request()->expectsJson()) {
            return response()->json(fill_data($result, trans('database.deleted')));
        }

        return back()->with('alert', fill_data($result, trans('database.deleted')));
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
        $path = $filesystem->getPathUsingId($id, 'photos/photo.png');

        if ($filesystem->exists($path)) {
            $path = $filesystem->path($path);
        } else {
            $path = public_path('assets/default/img/avatar.png');

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
     * Store a newly created resource photo in storage.
     *
     * @param  \App\Models\CmsUser\CmsUser  $model
     * @param  \Illuminate\Http\UploadedFile|null  $file
     * @return bool
     */
    protected function storePhoto(CmsUser $model, ?UploadedFile $file): bool
    {
        if (is_null($file) || is_null($model->id)) {
            return false;
        }

        $filesystem = Storage::disk('cms_users');

        $filesystem->makeDirectory(
            $path = $filesystem->getPathUsingId($model->id, 'photos')
        );

        Image::read($file)->scale(null, 150)->save(
            $filesystem->path($path) . '/photo.png'
        );

        return true;
    }

    /**
     * Remove the specified resource photo from filesystem.
     *
     * @param  \App\Models\CmsUser\CmsUser  $model
     * @return bool
     */
    protected function deletePhoto(CmsUser $model): bool
    {
        $filesystem = Storage::disk('cms_users');

        return $filesystem->delete(
            $filesystem->getPathUsingId($model->id, 'photos/photo.png')
        );
    }

    /**
     * Remove the specified resource directory from filesystem.
     *
     * @param  string  $id
     * @return bool
     */
    protected function deleteFilesDirectory(string $id): bool
    {
        $filesystem = Storage::disk('cms_users');

        return $filesystem->deleteDirectory($filesystem->getPathUsingId($id));
    }

    /**
     * Determine if the specified resource photo exists.
     *
     * @param  string  $id
     * @return bool
     */
    protected function photoExists(string $id): bool
    {
        $filesystem = Storage::disk('cms_users');

        return $filesystem->exists($filesystem->getPathUsingId($id, 'photos/photo.png'));
    }
}
