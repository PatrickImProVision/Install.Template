<?php

namespace App\Controllers;

use App\Libraries\Installer;
use App\Models\AboutUsItemModel;
use App\Models\ServicesItemModel;
use App\Models\SiteSettingsModel;
use App\Models\TechStackItemModel;
use App\Models\ProductsItemModel;
use App\Models\ValuesItemModel;
use App\Models\ContactItemModel;
use App\Models\SeoPageModel;

class Home extends BaseController
{
    /** Whether the logical table name exists (respects DBPrefix). */
    protected function dbTableExists(string $table): bool
    {
        try {
            return \Config\Database::connect()->tableExists($table);
        } catch (\Throwable) {
            return false;
        }
    }

    public function index(): string
    {
        return $this->renderWelcome(null);
    }

    public function aboutUs(): string
    {
        $data = $this->sitePageData();

        $aboutTableOk = $this->dbTableExists('about_us_items');
        $items        = [];

        if ($aboutTableOk) {
            try {
                $items = model(AboutUsItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $layout                     = $this->buildAboutUsViewData($items);
        $aboutHasPublicBlocks       = $layout['pageHeading'] !== null
            || $layout['mission'] !== null
            || $layout['badges'] !== []
            || $layout['stackCards'] !== [];
        $data['aboutSchemaMissing'] = ! $aboutTableOk;
        $data['aboutLayout']        = $layout;
        $data['aboutUnavailable']   = $aboutTableOk && ($items === [] || ! $aboutHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'about-us', $this->publicSeoFallback($data, 'About us', 'Mission, structure, and partners.'));

        return view('about_us', $data);
    }

    /**
     * @param list<array<string, mixed>> $items
     *
     * @return array{
     *     pageHeading: array<string, mixed>|null,
     *     mission: array<string, mixed>|null,
     *     badges: list<array<string, mixed>>,
     *     stackCards: list<array<string, mixed>>
     * }
     */
    protected function buildAboutUsViewData(array $items): array
    {
        $pageHeading = null;
        $mission     = null;
        $badges      = [];
        $stackCards  = [];

        foreach ($items as $it) {
            if (($it['placement'] ?? '') === 'page_header' && ($it['kind'] ?? '') === 'page_heading') {
                $pageHeading = $it;
            }
        }

        foreach ($items as $it) {
            if (($it['placement'] ?? '') !== 'intro') {
                continue;
            }
            if (($it['kind'] ?? '') === 'mission') {
                $mission = $it;
            }
            if (($it['kind'] ?? '') === 'badge') {
                $badges[] = $it;
            }
        }

        foreach ($items as $it) {
            if (($it['placement'] ?? '') === 'stack' && ($it['kind'] ?? '') === 'company_card') {
                $stackCards[] = $it;
            }
        }

        return [
            'pageHeading' => $pageHeading,
            'mission'     => $mission,
            'badges'      => $badges,
            'stackCards'  => $stackCards,
        ];
    }

    /**
     * True when contact/footer CMS rows include at least one block the public layout uses.
     *
     * @param array<string, mixed>|null $pageHeading
     * @param list<array<string, mixed>> $items
     */
    protected function contactItemsIncludePublicBlocks(?array $pageHeading, array $items): bool
    {
        if ($pageHeading !== null) {
            return true;
        }

        foreach ($items as $it) {
            $kind = (string) ($it['kind'] ?? '');
            $cg   = (string) ($it['column_group'] ?? '');

            if ($kind === 'brand') {
                return true;
            }

            if ($kind === 'legal' && trim((string) ($it['description'] ?? '')) !== '') {
                return true;
            }

            if ($cg === 'company' && in_array($kind, ['column_heading', 'company_entry'], true)) {
                return true;
            }

            if ($cg === 'contact' && in_array($kind, ['column_heading', 'contact_entry'], true)) {
                return true;
            }
        }

        return false;
    }

    public function services(): string
    {
        $data = $this->sitePageData();

        $servicesTableOk = $this->dbTableExists('services_items');
        $items           = [];

        if ($servicesTableOk) {
            try {
                $items = model(ServicesItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $heading = null;
        $cards   = [];

        foreach ($items as $it) {
            if (($it['kind'] ?? '') === 'page_heading') {
                $heading = $it;
            } elseif (($it['kind'] ?? '') === 'service_card') {
                $cards[] = $it;
            }
        }

        $servicesHasPublicBlocks       = $heading !== null || $cards !== [];
        $data['servicesSchemaMissing'] = ! $servicesTableOk;
        $data['servicesHeading']       = $heading;
        $data['serviceCards']          = $cards;
        $data['servicesUnavailable']   = $servicesTableOk && ($items === [] || ! $servicesHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'services', $this->publicSeoFallback($data, 'Services', 'Services and offerings.'));

        return view('services', $data);
    }

    public function products(): string
    {
        $data = $this->sitePageData();

        $productsTableOk = $this->dbTableExists('products_items');
        $items           = [];

        if ($productsTableOk) {
            try {
                $items = model(ProductsItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $heading = null;
        $cards   = [];

        foreach ($items as $it) {
            if (($it['kind'] ?? '') === 'page_heading') {
                $heading = $it;
            } elseif (($it['kind'] ?? '') === 'product_card') {
                $cards[] = $it;
            }
        }

        $productsHasPublicBlocks       = $heading !== null || $cards !== [];
        $data['productsSchemaMissing'] = ! $productsTableOk;
        $data['productsHeading']       = $heading;
        $data['productCards']          = $cards;
        $data['productsUnavailable']   = $productsTableOk && ($items === [] || ! $productsHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'products', $this->publicSeoFallback($data, 'Products & services', 'Products and offerings.'));

        return view('products', $data);
    }

    public function techStack(): string
    {
        $data = $this->sitePageData();

        $techTableOk = $this->dbTableExists('tech_stack_items');
        $items       = [];

        if ($techTableOk) {
            try {
                $items = model(TechStackItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $heading = null;
        $cards   = [];

        foreach ($items as $it) {
            if (($it['kind'] ?? '') === 'page_heading') {
                $heading = $it;
            } elseif (($it['kind'] ?? '') === 'tech_card') {
                $cards[] = $it;
            }
        }

        $techHasPublicBlocks       = $heading !== null || $cards !== [];
        $data['techSchemaMissing'] = ! $techTableOk;
        $data['techHeading']       = $heading;
        $data['techCards']         = $cards;
        $data['techUnavailable']   = $techTableOk && ($items === [] || ! $techHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'tech-stack', $this->publicSeoFallback($data, 'Technology stack', 'Tools and platforms we use.'));

        return view('tech_stack', $data);
    }

    public function values(): string
    {
        $data = $this->sitePageData();

        $valuesTableOk = $this->dbTableExists('values_items');
        $items         = [];

        if ($valuesTableOk) {
            try {
                $items = model(ValuesItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $heading = null;
        $rows    = [];

        foreach ($items as $it) {
            if (($it['kind'] ?? '') === 'page_heading') {
                $heading = $it;
            } elseif (($it['kind'] ?? '') === 'value_item') {
                $rows[] = $it;
            }
        }

        $valuesHasPublicBlocks       = $heading !== null || $rows !== [];
        $data['valuesSchemaMissing'] = ! $valuesTableOk;
        $data['valuesHeading']       = $heading;
        $data['valueItems']          = $rows;
        $data['valuesUnavailable']   = $valuesTableOk && ($items === [] || ! $valuesHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'values', $this->publicSeoFallback($data, 'Our values', 'What we stand for.'));

        return view('values', $data);
    }

    public function contact(): string
    {
        $data = $this->sitePageData();

        $contactTableOk = $this->dbTableExists('contact_items');
        $items          = [];

        if ($contactTableOk) {
            try {
                $items = model(ContactItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
            } catch (\Throwable) {
                $items = [];
            }
        }

        $pageHeading = null;

        foreach ($items as $it) {
            if (($it['kind'] ?? '') === 'page_heading') {
                $pageHeading = $it;
                break;
            }
        }

        $contactHasPublicBlocks = $this->contactItemsIncludePublicBlocks($pageHeading, $items);

        $data['contactSchemaMissing'] = ! $contactTableOk;
        $data['contactRows']          = $items;
        $data['contactPageHeading']   = $pageHeading;
        $data['contactUnavailable']   = $contactTableOk && ($items === [] || ! $contactHasPublicBlocks);

        $data = $this->withPublicSeo($data, 'contact', $this->publicSeoFallback($data, 'Contact', 'How to reach us.'));

        return view('contact', $data);
    }

    /**
     * @return array{installed: bool, siteName: string|null, siteDescription: string|null, seoPages: array<string, array<string, mixed>>}
     */
    protected function sitePageData(): array
    {
        helper('url');

        $installed         = Installer::isInstalled();
        $siteName          = null;
        $siteDescription   = null;

        if ($installed) {
            try {
                $row = model(SiteSettingsModel::class)->find(1);
                if ($row !== null) {
                    $siteName        = $row['site_name'] !== '' ? $row['site_name'] : null;
                    $siteDescription = $row['site_description'] !== '' && $row['site_description'] !== null
                        ? $row['site_description']
                        : null;
                }
            } catch (\Throwable) {
                // Leave defaults if the database is unreachable.
            }
        }

        $seoPages = [];
        try {
            foreach (model(SeoPageModel::class)->orderBy('page_key', 'ASC')->findAll() as $r) {
                $key = (string) ($r['page_key'] ?? '');
                if ($key !== '') {
                    $seoPages[$key] = $r;
                }
            }
        } catch (\Throwable) {
            $seoPages = [];
        }

        return [
            'installed'       => $installed,
            'siteName'        => $siteName,
            'siteDescription' => $siteDescription,
            'seoPages'        => $seoPages,
        ];
    }

    /**
     * Neutral SEO defaults when the CMS row for this page is absent (no hardcoded marketing copy).
     *
     * @param array<string, mixed> $data
     *
     * @return array{title: string, description: string, keywords: string}
     */
    protected function publicSeoFallback(array $data, string $pageTitleBase, string $descriptionWhenNoSiteDesc, string $keywords = ''): array
    {
        $suffix = ! empty($data['siteName']) ? ' — ' . $data['siteName'] : '';

        return [
            'title'       => $pageTitleBase . $suffix,
            'description' => (! empty($data['installed']) && ! empty($data['siteDescription']))
                ? (string) $data['siteDescription']
                : $descriptionWhenNoSiteDesc,
            'keywords'    => $keywords,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @param array{title: string, description: string, keywords: string} $fallback
     *
     * @return array<string, mixed>
     */
    protected function withPublicSeo(array $data, string $pageKey, array $fallback): array
    {
        $seoPages = $data['seoPages'] ?? [];
        unset($data['seoPages']);

        $data['seo'] = $this->mergeSeo($pageKey, $fallback, $seoPages);

        return $data;
    }

    /**
     * @param array{title: string, description: string, keywords: string} $fallback
     * @param array<string, array<string, mixed>>                        $seoPages
     *
     * @return array{title: string, description: string, keywords: string}
     */
    protected function mergeSeo(string $pageKey, array $fallback, array $seoPages): array
    {
        $row = $seoPages[$pageKey] ?? null;

        if ($row === null) {
            return $fallback;
        }

        $title = trim((string) ($row['meta_title'] ?? ''));
        $desc  = trim((string) ($row['meta_description'] ?? ''));
        $kw    = trim((string) ($row['meta_keywords'] ?? ''));

        return [
            'title'       => $title !== '' ? $title : $fallback['title'],
            'description' => $desc !== '' ? $desc : $fallback['description'],
            'keywords'    => $kw !== '' ? $kw : $fallback['keywords'],
        ];
    }

    protected function renderWelcome(?string $scrollTo): string
    {
        $data             = $this->sitePageData();
        $data['scrollTo'] = $scrollTo;

        $homeFallback = $this->publicSeoFallback($data, 'Home', '');
        if (! empty($data['siteName'])) {
            $homeFallback['title'] = (string) $data['siteName'];
        }

        $data = $this->withPublicSeo($data, 'home', $homeFallback);

        return view('welcome_message', $data);
    }
}
