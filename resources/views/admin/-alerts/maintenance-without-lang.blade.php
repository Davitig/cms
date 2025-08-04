<div @class([
'alert alert-outline-danger lang-visibility-alert', 'd-none' => ($count = language()->countVisible()) || ! language()->getSettings('down_without_language')
]) role="alert" data-count="{{ $count }}">
    Website is in maintenance mode. See available
    <a href="{{ cms_route('languages.index') }}" class="text-danger text-decoration-underline" target="_blank">languages</a>
    or
    <a href="{{ cms_route('settings.language.index') }}" class="text-danger text-decoration-underline" target="_blank">settings</a>
</div>
