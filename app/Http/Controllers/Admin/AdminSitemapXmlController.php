<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use DOMDocument;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sabre\Xml\Service;

class AdminSitemapXmlController extends Controller
{
    /**
     * XML data.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * List of default namespace prefixes.
     *
     * @var array
     */
    protected array $namespaceMap = ['http://www.sitemaps.org/schemas/sitemap/0.9' => ''];

    /**
     * xhtml namespace prefix.
     *
     * @var string
     */
    protected string $xhtml;

    /**
     * The collection of the application languages.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $languages;

    /**
     * Indicates if the application is multilanguage.
     *
     * @var bool
     */
    protected bool $isMultilanguage = false;

    /**
     * List of the listable types.
     *
     * @var array
     */
    protected array $listableTypes = [];

    /**
     * List of the extended types.
     *
     * @var array
     */
    protected array $extendedTypes = [];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->languages = language()->all();

        if ($this->isMultilanguage = ($this->languages->count() > 1)) {
            $this->namespaceMap += [
                $this->xhtml = 'http://www.w3.org/1999/xhtml' => 'xhtml'
            ];
        }

        $this->listableTypes = (array) cms_pages('listable');

        $this->extendedTypes = (array) cms_pages('extended');
    }

    /**
     * Store a newly created/update sitemap XML.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $pages = (new Page)->whereVisible()->orderBy('menu_id')
            ->positionAsc()
            ->get();

        foreach ($pages as $page) {
            $value = ['url' => ['loc' => web_url(
                $page->url_path = $page->getUrlPath(), [], $this->isMultilanguage
                ? $this->languages->keys()->first()
                : null
            )]];

            if ($this->isMultilanguage) {
                foreach ($this->languages as $langValue => $model) {
                    $value['url'][] = $this->getLanguageLinks($page, null, $langValue);
                }
            }

            $this->data[] = $value;

            $this->setListableModels($page);
            $this->setExtendedModels($page);
        }

        $xml = new Service;
        $xml->namespaceMap = $this->namespaceMap;

        $doc = new DOMDocument;
        $doc->loadXML($xml->write('urlset', $this->data));

        $result = $doc->save(public_path('sitemap.xml'));

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back();
    }

    /**
     * Set a listable models to the XML data.
     *
     * @param  \App\Models\Page\Page $page
     * @return void
     */
    protected function setListableModels(Page $page): void
    {
        $listableType = null;

        foreach ($this->listableTypes as $type => $values) {
            if (array_key_exists($page->type, (array) $values)) {
                $listableType = $type;

                break;
            }
        }

        if (is_null($listableType)) {
            return;
        }

        $items = (new $this->listableTypes[$listableType][$page->type])
            ->collectionId($page->type_id)
            ->whereVisible()
            ->orderDesc()
            ->get();

        foreach ($items as $item) {
            if (empty($item->slug)) {
                continue;
            }

            $this->data[] = $this->getUrls($page, $item);
        }
    }

    /**
     * Set an extended models to the XML data.
     *
     * @param  \App\Models\Page\Page $page
     * @return void
     */
    protected function setExtendedModels(Page $page): void
    {
        if (! array_key_exists($page->type, $this->extendedTypes)) {
            return;
        }

        $items = (new $this->extendedTypes[$page->type])->whereVisible()->orderDesc()->get();

        foreach ($items as $item) {
            if (empty($item->slug)) {
                continue;
            }

            $this->data[] = $this->getUrls($page, $item);
        }
    }

    /**
     * Get the urls.
     *
     * @param  \App\Models\Page\Page  $page
     * @param  \Illuminate\Database\Eloquent\Model  $item
     * @return array
     */
    protected function getUrls(Page $page, Model $item): array
    {
        $value = ['url' => ['loc' => web_url(
            [$page->url_path, $item->slug], [], $this->isMultilanguage
            ? $this->languages->keys()->first()
            : null
        )]];

        if ($this->isMultilanguage) {
            foreach ($this->languages as $langValue => $model) {
                $value['url'][] = $this->getLanguageLinks(
                    $page, $item->slug, $langValue
                );
            }
        }

        return $value;
    }



    /**
     * Get an array of XML language links.
     *
     * @param  \App\Models\Page\Page $page
     * @param  string|null  $slug
     * @param  string  $langValue
     * @return array
     */
    protected function getLanguageLinks(Model $page, ?string $slug, string $langValue): array
    {
        return [
            'name' => "{{$this->xhtml}}link",
            'attributes' => [
                'rel' => 'alternate',
                'hreflang' => $langValue,
                'href' => web_url([$page->url_path, $slug], [], $langValue)
            ]
        ];
    }
}
