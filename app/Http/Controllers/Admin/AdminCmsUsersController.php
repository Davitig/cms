<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsUserRequest;
use App\Models\CmsUser;
use App\Models\CmsUserRole;
use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUsersController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CmsUser $model) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, Closure $next) {
                if (! $request->user('cms')->hasFullAccess()) {
                    throw new AccessDeniedHttpException('Forbidden');
                }

                return $next($request);
            }, only: ['create', 'store']),
            new Middleware(function (Request $request, Closure $next) {
                if (! $request->user('cms')->hasFullAccess()
                    && $request->user('cms')->id != $request->route('cms_user')
                ) {
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
        $data['items'] = $this->model->when(! $request->user('cms')->hasFullAccess(),
            function ($q) use ($request) {
                $request->offsetUnset('role');

                return $q->whereKey($request->user('cms')->id);
            }
        )->joinRole()->adminFilter($request)
            ->orderDesc()
            ->paginate(20);

        $data['roles'] = (new CmsUserRole)->pluck('role', 'id');

        return view('admin.cms_users.index', $data);
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

        return view('admin.cms_users.create', $data);
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

        if (! $request->filled('password')) {
            unset($input['password']);
        } else {
            $input['password'] = bcrypt($input['password']);
        }

        $id = $this->model->create($input)->id;

        $this->storePhoto($id, $request->file('photo'));

        app('db')->table('cms_settings')->insert(['cms_user_id' => $id]);

        return redirect(cms_route('cmsUsers.edit', [$id]))
            ->with('alert', fill_data('success', trans('general.created')));
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

        return view('admin.cms_users.show', $data);
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

        $data['photoExists'] = $this->checkUserPhotoExists($id);

        return view('admin.cms_users.edit', $data);
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

        $this->model->findOrFail($id)->update($input);

        $this->storePhoto($id, $request->file('photo'));

        unset($input['password'], $input['password_confirmation']);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
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

        $this->model->whereKey($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }

    /**
     * Display the user photo.
     *
     * @param  \Illuminate\Filesystem\FilesystemManager  $filesystem
     * @param  string  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function getPhoto(FilesystemManager $filesystem, string $id)
    {
        $filesystem = $filesystem->disk('cms_users');

        $path = $filesystem->getUserPhotosDirectory($id, 'photo.png');

        if ($filesystem->exists($path)) {
            $path = $filesystem->path($path);
        } else {
            $path = public_path('assets/libs/images/user-2.png');

            if (! (new Filesystem)->exists($path)) {
                return null;
            }
        }

        try {
            return response()->file($path);
        } catch (FileNotFoundException) {
            return null;
        }
    }

    /**
     * Store the user photo in storage.
     *
     * @param  string  $id
     * @param  \Illuminate\Http\UploadedFile|null  $file
     * @return bool
     */
    protected function storePhoto(string $id, ?UploadedFile $file): bool
    {
        if (is_null($file)) {
            return false;
        }

        $filesystem = Storage::disk('cms_users');

        $path = $filesystem->getUserPhotosDirectory($id);

        $filesystem->makeDirectory($path);

        Image::read($file)->scale(null, 150)->save(
            $filesystem->path($path) . '/photo.png'
        );

        return true;
    }

    /**
     * Check if a user photo exists.
     *
     * @param  string  $id
     * @return bool
     */
    protected function checkUserPhotoExists(string $id): bool
    {
        $filesystem = Storage::disk('cms_users');

        return $filesystem->exists($filesystem->getUserPhotosDirectory($id, 'photo.png'));
    }
}
