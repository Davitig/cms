<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eloquent\Model;
use App\Models\Page;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sabre\Xml\Service as XmlService;

class AdminSitemapXmlController extends Controller
{
    /**
     * XML data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * List of default namespace prefixes.
     *
     * @var array
     */
    protected $namespaceMap = ['http://www.sitemaps.org/schemas/sitemap/0.9' => ''];

    /**
     * xhtml namespace prefix.
     *
     * @var string
     */
    protected $xhtml;

    /**
     * List of the application languages.
     *
     * @var array
     */
    protected $languages = [];

    /**
     * Main language of the application.
     *
     * @var string
     */
    protected $mainLanguage;

    /**
     * Indicates if the application has many languages.
     *
     * @var bool
     */
    protected $hasManyLanguage = false;

    /**
     * List of the listable types.
     *
     * @var array
     */
    protected $listableTypes = [];

    /**
     * List of the implicit types.
     *
     * @var array
     */
    protected $implicitTypes = [];

    /**
     * List of the explicit types.
     *
     * @var array
     */
    protected $explicitTypes = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->languages = languages();

        $this->mainLanguage = key($this->languages);

        if ($this->hasManyLanguage = (count($this->languages) > 1)) {
            $this->namespaceMap += [
                $this->xhtml = 'http://www.w3.org/1999/xhtml' => 'xhtml'
            ];
        }

        $this->listableTypes = cms_pages('listable');

        $this->implicitTypes = cms_pages('implicit');

        $this->explicitTypes = cms_pages('explicit');
    }

    /**
     * Store a newly created/update sitemap xml.
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
                $page->full_slug = $page->getFullSlug(), [], $this->hasManyLanguage
                ? $this->mainLanguage
                : null
            )]];

            if ($page->hasLanguage() && $this->hasManyLanguage) {
                foreach ($this->languages as $langKey => $langValue) {
                    $value['url'][] = $this->getLanguageLinks($page, null, $langKey);
                }
            }

            $this->data[] = $value;

            $this->setImplicitModels($page);
            $this->setExplicitModels($page);
        }

        $xml = new XmlService;
        $xml->namespaceMap = $this->namespaceMap;

        $doc = new DOMDocument;
        $doc->loadXML($xml->write("urlset", $this->data));

        $result = $doc->save(public_path('sitemap.xml'));

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return redirect()->back();
    }

    /**
     * Set an implicit models to the xml data.
     *
     * @param  \App\Models\Page $page
     * @return void
     */
    protected function setImplicitModels(Model $page)
    {
        if (! in_array($page->type, $this->listableTypes)
            && ! array_key_exists($page->type, $this->implicitTypes)
        ) {
            return;
        }

        $implicitModel = (new $this->implicitTypes[$page->type])
            ->find($page->type_id);

        if (! is_null($implicitModel)) {
            $model = cms_config('collections.models.' . $implicitModel->type);

            $items = (new $model)->where(
                Str::singular($implicitModel->getTable()) . '_id',
                $implicitModel->id
            )->whereVisible()->orderDesc()->get();

            foreach ($items as $item) {
                if (empty($item->slug)) {
                    continue;
                }

                $this->data[] = $this->getUrls($page, $item);
            }
        }
    }

    /**
     * Set an explicit models to the xml data.
     *
     * @param  \App\Models\Page $page
     * @return void
     */
    protected function setExplicitModels(Model $page)
    {
        if (! array_key_exists($page->type, $this->explicitTypes)) {
            return;
        }

        $model = (new $this->explicitTypes[$page->type]);

        if (! is_null($model)) {
            $items = (new $model)->whereVisible()->orderDesc()->get();

            foreach ($items as $item) {
                $this->data[] = $this->getUrls($page, $item);
            }
        }
    }

    /**
     * Get the urls.
     *
     * @param  \App\Models\Page $page
     * @param  \App\Models\Eloquent\Model $page
     * @return array
     */
    protected function getUrls(Page $page, Model $item)
    {
        $value = ['url' => ['loc' => web_url(
            [$page->full_slug, $item->slug], [], $this->hasManyLanguage
            ? $this->mainLanguage
            : null
        )]];

        if ($item->hasLanguage() && $this->hasManyLanguage) {
            foreach ($this->languages as $langKey => $langValue) {
                $value['url'][] = $this->getLanguageLinks(
                    $page, $item->slug, $langKey
                );
            }
        }

        return $value;
    }



    /**
     * Get an array of xml language links.
     *
     * @param  \App\Models\Page $page
     * @param  string|null $slug
     * @param  string $langKey
     * @return array
     */
    protected function getLanguageLinks(Model $page, $slug = null, $langKey)
    {
        return [
            'name' => "{{$this->xhtml}}link",
            'attributes' => [
                'rel' => 'alternate',
                'hreflang' => $langKey,
                'href' => web_url(
                    [$page->full_slug, $slug],
                    [],
                    $langKey
                )
            ]
        ];
    }
}
