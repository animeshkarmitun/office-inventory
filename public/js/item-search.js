$(function() {
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    let searchTimeout;
    function fetchItems(query, pageUrl = null) {
        let url = pageUrl || window.ITEMS_ROUTE_URL;
        $.ajax({
            url: url,
            type: 'GET',
            data: { search: query },
            success: function(data) {
                $('#items-table-wrapper').html(data.html);
            }
        });
    }
    $('#item-search').on('input', function() {
        clearTimeout(searchTimeout);
        let query = $(this).val();
        searchTimeout = setTimeout(function() {
            fetchItems(query);
        }, 300);
    });
    $(document).on('click', '#items-table-wrapper .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let query = $('#item-search').val();
        fetchItems(query, url);
    });
}); 