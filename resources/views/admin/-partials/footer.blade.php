<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div
            class="footer-container d-flex align-items-center justify-content-between py-4">
            <div class="text-body">
                {{date('Y')}} &copy; {{config('app.name')}} - Version: {{cms_config('version')}}
            </div>
            <div class="d-flex gap-4">
                <div class="social-icon my-4">
                    <a href="https://laravel.com" class="btn btn-icon btn-sm btn-danger" target="_blank">
                        <i class="icon-base fa-brands fa-laravel icon-20px"></i>
                    </a>
                </div>
                <div class="social-icon my-4">
                    <a href="https://github.com/Davitig/cms" class="btn btn-icon btn-sm btn-github" target="_blank">
                        <i class="icon-base fa-brands fa-github icon-20px"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- / Footer -->
