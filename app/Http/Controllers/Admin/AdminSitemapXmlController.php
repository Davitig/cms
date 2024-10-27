<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Base\Model;
use App\Models\Page\Page;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
     * List of the application languages.
     *
     * @var array
     */
    protected array $languages = [];

    /**
     * Main language of the application.
     *
     * @var string
     */
    protected string $mainLanguage;

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
     * List of the implicit types.
     *
     * @var array
     */
    protected array $implicitTypes = [];

    /**
     * List of the explicit types.
     *
     * @var array
     */
    protected array $explicitTypes = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->languages = languages();

        $this->mainLanguage = key($this->languages);

        if ($this->isMultilanguage = (count($this->languages) > 1)) {
            $this->namespaceMap += [
                $this->xhtml = 'http://www.w3.org/1999/xhtml' => 'xhtml'
            ];
        }

        $this->listableTypes = (array) cms_pages('listable');

        $this->implicitTypes = (array) cms_pages('implicit');

        $this->explicitTypes = (array) cms_pages('explicit');
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
                $page->full_slug = $page->getFullSlug(), [], $this->isMultilanguage
                ? $this->mainLanguage
                : null
            )]];

            if ($this->isMultilanguage) {
                foreach ($this->languages as $langValue => $values) {
                    $value['url'][] = $this->getLanguageLinks($page, null, $langValue);
                }
            }

            $this->data[] = $value;

            $this->setImplicitModels($page);
            $this->setExplicitModels($page);
        }

        $xml = new Service;
        $xml->namespaceMap = $this->namespaceMap;

        $doc = new DOMDocument;
        $doc->loadXML($xml->write("urlset", $this->data));

        $result = $doc->save(public_path('sitemap.xml'));

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back();
    }

    /**
     * Set an implicit models to the xml data.
     *
     * @param  \App\Models\Page\Page $page
     * @return void
     */
    protected function setImplicitModels(Model $page)
    {
        if (! in_array($page->type, $this->listableTypes)
            && ! array_key_exists($page->type, $this->implicitTypes)
        ) {
            return;
        }

        $implicitModel = (new $this->implicitTypes[$page->type])->find($page->type_id);

        if (! is_null($implicitModel)) {
            $model = cms_config('collections.models.' . $implicitModel->type);

            $items = (new $model)->where(
                Str::singular($implicitModel->getTable()) . '_id',
                $implicitModel->id
            )->whereVisible()->orderDesc()->get();

            foreach ($items as $item) {
                // entity without a show endpoint
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
     * @param  \App\Models\Page\Page $page
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
                // entity without a show endpoint
                if (empty($item->slug)) {
                    continue;
                }

                $this->data[] = $this->getUrls($page, $item);
            }
        }
    }

    /**
     * Get the urls.
     *
     * @param  \App\Models\Page\Page  $page
     * @param  \App\Models\Base\Model  $item
     * @return array
     */
    protected function getUrls(Page $page, Model $item)
    {
        $value = ['url' => ['loc' => web_url(
            [$page->full_slug, $item->slug], [], $this->isMultilanguage
            ? $this->mainLanguage
            : null
        )]];

        if ($this->isMultilanguage) {
            foreach ($this->languages as $langValue => $values) {
                $value['url'][] = $this->getLanguageLinks(
                    $page, $item->slug, $langValue
                );
            }
        }

        return $value;
    }



    /**
     * Get an array of xml language links.
     *
     * @param  \App\Models\Page\Page $page
     * @param  string|null  $slug
     * @param  string  $langValue
     * @return array
     */
    protected function getLanguageLinks(Model $page, ?string $slug, string $langValue)
    {
        return [
            'name' => "{{$this->xhtml}}link",
            'attributes' => [
                'rel' => 'alternate',
                'hreflang' => $langValue,
                'href' => web_url([$page->full_slug, $slug], [], $langValue)
            ]
        ];
    }
}
