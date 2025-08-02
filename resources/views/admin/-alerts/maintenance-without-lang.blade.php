<div @class(['alert alert-outline-danger lang-visibility-alert', 'd-none' => $count = language()->countVisible()]) role="alert" data-count="{{ $count }}">
    Website is in maintenance mode when there is no visible <a href="{{ cms_route('languages.index') }}" class="text-danger text-decoration-underline" target="_blank">language</a>
</div>
