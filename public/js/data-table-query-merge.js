document.querySelectorAll('form[data-merge-get]').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // we'll navigate manually

        // read current query params
        const params = new URLSearchParams(location.search);

        // read form data
        const fd = new FormData(form);

        // remove any existing params that the form will control (so form overrides or removes properly)
        for (const key of fd.keys()) {
            params.delete(key);
        }

        // append all form entries (handles multiple values like checkboxes with same name)
        for (const [k, v] of fd.entries()) {
            params.append(k, v);
        }

        // navigate to the path + merged query
        const newQuery = params.toString();
        location.href = location.pathname + (newQuery ? '?' + newQuery : '');
    });
});