<div class="table-pagination-footer">
    <div class="table-pagination-btn-group">
        @if (isset($links))
        @if ($links['total'] === 0 || $links['current_page'] === 1)
        <c-button variant="outline" disabled>
            Previous
        </c-button>
        @else
        <c-link type="outline" id="previous">
            Previous
        </c-link>
        @endif
        @if ($links['total'] === 0 || $links['current_page'] === $links['last_page'])
        <c-button variant="outline" disabled>
            Next
        </c-button>
        @else
        <c-link type="outline" id="next">
            Next
        </c-link>
        @endif
        @else
        <c-button variant="outline">
            Previous
        </c-button>
        <c-button variant="outline">
            Next
        </c-button>
        @endif
    </div>
</div>

<script>
    (() => {
        function buildUrlWithParams(params, baseUrl = window.location.href) {
            const url = new URL(baseUrl);

            Object.entries(params).forEach(([key, value]) => {
                if (value === null || value === undefined) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, value);
                }
            });

            return url.toString();
        }

        let next = {{ $links['current_page'] + 1
    }};
    let previous = {{ $links['current_page'] - 1 }}

    console.log("Heelooo");

    const nextBtn = document.getElementById('next');

    if (nextBtn) {
        nextBtn.href =
            buildUrlWithParams({ page: next });
    }

    const prevBtn = document.getElementById('previous');

    if (prevBtn) {
        prevBtn.href =
            buildUrlWithParams({ page: previous });
    }

    }) ();
</script>