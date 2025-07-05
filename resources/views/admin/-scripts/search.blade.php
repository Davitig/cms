@push('body.bottom')
    <script type="text/javascript">
        // Override: main.js search
        $(function () {
            let data = {
                "navigation": {
                    "Dashboards": [
                        {
                            "name": "Dashboard",
                            "icon": "fa fa-dashboard",
                            "url": "{{ cms_route('dashboard.index') }}"
                        }
                    ],
                    "Site Map": [
                        {
                            "name": "Menus",
                            "icon": "fa fa-list",
                            "url": "{{ cms_route('menus.index') }}"
                        },
                        @foreach($menus as $item)
                        {
                            "name": "{{ $item->title }}",
                            "icon": "fa fa-indent",
                            "url": "{{ cms_route('pages.index', [$item->id]) }}"
                        },
                        @endforeach
                    ],
                    "Users": [
                        {
                            "name": "CMS Users",
                            "icon": "fa fa-user-tie",
                            "url": "{{ cms_route('cmsUsers.index') }}"
                        },
                        {
                            "name": "Profile",
                            "icon": "fa fa-user-check",
                            "url": "{{ cms_route('cmsUsers.show', [$userId = auth('cms')->id()]) }}"
                        },
                        {
                            "name": "Security",
                            "icon": "fa fa-user-shield",
                            "url": "{{ cms_route('cmsUsers.security', [$userId]) }}"
                        },
                        {
                            "name": "Preferences",
                            "icon": "fa fa-sliders",
                            "url": "{{ cms_route('cmsUsers.preferences.index', [$userId]) }}"
                        },
                        {
                            "name": "Roles",
                            "icon": "fa fa-user-tag",
                            "url": "{{ cms_route('cmsUserRoles.index') }}"
                        },
                        {
                            "name": "Permissions",
                            "icon": "fa fa-lock",
                            "url": "{{ cms_route('permissions.index') }}"
                        }
                    ],
                    "Products": [
                        {
                            "name": "Products",
                            "icon": "fa fa-store",
                            "url": "{{ cms_route('products.index') }}"
                        }
                    ],
                    "Collections": [
                        {
                            "name": "Collections",
                            "icon": "fa fa-list-alt",
                            "url": "{{ cms_route('collections.index') }}"
                        }
                    ],
                    "Languages": [
                        {
                            "name": "Languages",
                            "icon": "fa fa-language",
                            "url": "{{ cms_route('languages.index') }}"
                        },
                        {
                            "name": "Translations",
                            "icon": "fa fa-sort-alpha-asc",
                            "url": "{{ cms_route('translations.index') }}"
                        }
                    ],
                    "Settings": [
                        {
                            "name": "Web Settings",
                            "icon": "fa fa-layer-group",
                            "url": "{{ cms_route('webSettings.index') }}"
                        }
                    ]
                },
                "suggestions": {
                    "Popular": [
                        {
                            "name": "Dashboard",
                            "icon": "fa fa-dashboard",
                            "url": "{{ cms_route('dashboard.index') }}"
                        },
                        {
                            "name": "Menus",
                            "icon": "fa fa-list",
                            "url": "{{ cms_route('menus.index') }}"
                        },
                        {
                            "name": "Products",
                            "icon": "fa fa-store",
                            "url": "{{ cms_route('products.index') }}"
                        },
                        {
                            "name": "Collections",
                            "icon": "fa fa-list-alt",
                            "url": "{{ cms_route('collections.index') }}"
                        }
                    ],
                    "Users": [
                        {
                            "name": "CMS Users",
                            "icon": "fa fa-user-tie",
                            "url": "{{ cms_route('cmsUsers.index') }}"
                        },
                        {
                            "name": "Profile",
                            "icon": "fa fa-user-check",
                            "url": "{{ cms_route('cmsUsers.show', [$userId = auth('cms')->id()]) }}"
                        },
                        {
                            "name": "Roles",
                            "icon": "fa fa-user-tag",
                            "url": "{{ cms_route('cmsUserRoles.index') }}"
                        },
                        {
                            "name": "Permissions",
                            "icon": "fa fa-lock",
                            "url": "{{ cms_route('permissions.index') }}"
                        }
                    ],
                    "Other": [
                        {
                            "name": "Languages",
                            "icon": "fa fa-language",
                            "url": "{{ cms_route('languages.index') }}"
                        },
                        {
                            "name": "File Manager",
                            "icon": "fa fa-file-import",
                            "url": "{{ cms_route('fileManager') }}"
                        },
                        {
                            "name": "Translations",
                            "icon": "fa fa-sort-alpha-asc",
                            "url": "{{ cms_route('translations.index') }}"
                        }
                    ],
                    "Settings": [
                        {
                            "name": "Security",
                            "icon": "fa fa-user-shield",
                            "url": "{{ cms_route('cmsUsers.security', [$userId]) }}"
                        },
                        {
                            "name": "Preferences",
                            "icon": "fa fa-sliders",
                            "url": "{{ cms_route('cmsUsers.preferences.index', [$userId]) }}"
                        },
                        {
                            "name": "Web Settings",
                            "icon": "fa fa-layer-group",
                            "url": "{{ cms_route('webSettings.index') }}"
                        }
                    ]
                }
            };
            // Search Configuration
            const SearchConfig = {
                container: '#search',
                placeholder: 'Search [CTRL + K]',
                classNames: {
                    detachedContainer: 'd-flex flex-column',
                    detachedFormContainer: 'd-flex align-items-center justify-content-between border-bottom',
                    form: 'd-flex align-items-center',
                    input: 'search-control border-none',
                    detachedCancelButton: 'btn-search-close',
                    panel: 'flex-grow content-wrapper overflow-hidden position-relative',
                    panelLayout: 'h-100',
                    clearButton: 'd-none',
                    item: 'd-block'
                }
            };
            // Initialize search
            initializeSearch();
            function initializeSearch() {
                const searchElement = document.getElementById('search');
                if (!searchElement) return;

                return autocomplete({
                    ...SearchConfig,
                    openOnFocus: true,
                    onStateChange({ state, setQuery }) {
                        // When autocomplete is opened
                        if (state.isOpen) {
                            // Hide body scroll and add padding to prevent layout shift
                            document.body.style.overflow = 'hidden';
                            document.body.style.paddingRight = 'var(--bs-scrollbar-width)';
                            // Replace "Cancel" text with icon
                            const cancelIcon = document.querySelector('.aa-DetachedCancelButton');
                            if (cancelIcon) {
                                cancelIcon.innerHTML =
                                    '<span class="text-body-secondary">[esc]</span> <span class="icon-base icon-md fa fa-xmark text-heading"></span>';
                            }

                            // Perfect Scrollbar
                            if (!window.autoCompletePS) {
                                const panel = document.querySelector('.aa-Panel');
                                if (panel) {
                                    window.autoCompletePS = new PerfectScrollbar(panel);
                                }
                            }
                        } else {
                            // When autocomplete is closed
                            if (state.status === 'idle' && state.query) {
                                setQuery('');
                            }

                            // Restore body scroll and padding when autocomplete is closed
                            document.body.style.overflow = 'auto';
                            document.body.style.paddingRight = '';
                        }
                    },
                    render(args, root) {
                        const { render, html, children, state } = args;

                        // Initial Suggestions
                        if (!state.query) {
                            const initialSuggestions = html`
                                <div class="p-5 p-lg-12">
                                    <div class="row g-4">
                                        ${Object.entries(data.suggestions || {}).map(
                                            ([section, items]) => html`
                                                <div class="col-md-6 suggestion-section">
                                                    <p class="search-headings mb-2">${section}</p>
                                                    <div class="suggestion-items">
                                                        ${items.map(
                                                            item => html`
                                                                <a href="${item.url}" class="suggestion-item d-flex align-items-center">
                                                                    <i class="icon-base ${item.icon} icon-sm"></i>
                                                                    <span>${item.name}</span>
                                                                </a>
                                                            `
                                                        )}
                                                    </div>
                                                </div>
                                            `
                                        )}
                                    </div>
                                </div>
                            `;

                            render(initialSuggestions, root);
                            return;
                        }

                        // No items
                        if (!args.sections.length) {
                            render(
                                html`
                                    <div class="search-no-results-wrapper">
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <div class="text-center text-heading">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                                                    <g
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="0.6">
                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                        <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2m-5-4h.01M12 11v3" />
                                                    </g>
                                                </svg>
                                                <h5 class="mt-2">No results found</h5>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                root
                            );
                            return;
                        }

                        render(children, root);
                        window.autoCompletePS?.update();
                    },
                    getSources() {
                        const sources = [];

                        // Add navigation sources if available
                        if (data.navigation) {
                            // Add other navigation sources first
                            const navigationSources = Object.keys(data.navigation)
                                .filter(section => section !== 'files' && section !== 'members')
                                .map(section => ({
                                    sourceId: `nav-${section}`,
                                    getItems({ query }) {
                                        const items = data.navigation[section];
                                        if (!query) return items;
                                        return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
                                    },
                                    getItemUrl({ item }) {
                                        return item.url;
                                    },
                                    templates: {
                                        header({ items, html }) {
                                            if (items.length === 0) return null;
                                            return html`<span class="search-headings">${section}</span>`;
                                        },
                                        item({ item, html }) {
                                            return html`
                                                <a href="${item.url}" class="d-flex justify-content-between align-items-center">
                                                    <span class="item-wrapper"><i class="icon-base ${item.icon} icon-sm"></i>${item.name}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                                                        <g
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            color="currentColor">
                                                            <path d="M11 6h4.5a4.5 4.5 0 1 1 0 9H4" />
                                                            <path d="M7 12s-3 2.21-3 3s3 3 3 3" />
                                                        </g>
                                                    </svg>
                                                </a>
                                            `;
                                        }
                                    }
                                }));
                            sources.push(...navigationSources);

                            // Add Files source second
                            if (data.navigation.files) {
                                sources.push({
                                    sourceId: 'files',
                                    getItems({ query }) {
                                        const items = data.navigation.files;
                                        if (!query) return items;
                                        return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
                                    },
                                    getItemUrl({ item }) {
                                        return item.url;
                                    },
                                    templates: {
                                        header({ items, html }) {
                                            if (items.length === 0) return null;
                                            return html`<span class="search-headings">Files</span>`;
                                        },
                                        item({ item, html }) {
                                            return html`
                                                <a href="${item.url}" class="d-flex align-items-center position-relative px-4 py-2">
                                                    <div class="file-preview me-2">
                                                        <img src="${assetsPath}${item.src}" alt="${item.name}" class="rounded" width="42" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0">${item.name}</h6>
                                                        <small class="text-body-secondary">${item.subtitle}</small>
                                                    </div>
                                                    ${item.meta
                                                        ? html`
                                                            <div class="position-absolute end-0 me-4">
                                                                <span class="text-body-secondary small">${item.meta}</span>
                                                            </div>
                                                        `
                                                        : ''}
                                                </a>
                                            `;
                                        }
                                    }
                                });
                            }

                            // Add Members source last
                            if (data.navigation.members) {
                                sources.push({
                                    sourceId: 'members',
                                    getItems({ query }) {
                                        const items = data.navigation.members;
                                        if (!query) return items;
                                        return items.filter(item => item.name.toLowerCase().includes(query.toLowerCase()));
                                    },
                                    getItemUrl({ item }) {
                                        return item.url;
                                    },
                                    templates: {
                                        header({ items, html }) {
                                            if (items.length === 0) return null;
                                            return html`<span class="search-headings">Members</span>`;
                                        },
                                        item({ item, html }) {
                                            return html`
                                                <a href="${item.url}" class="d-flex align-items-center py-2 px-4">
                                                    <div class="avatar me-2">
                                                        <img src="${assetsPath}${item.src}" alt="${item.name}" class="rounded-circle" width="32" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0">${item.name}</h6>
                                                        <small class="text-body-secondary">${item.subtitle}</small>
                                                    </div>
                                                </a>
                                            `;
                                        }
                                    }
                                });
                            }
                        }

                        return sources;
                    }
                });
            }
        });
    </script>
@endpush
