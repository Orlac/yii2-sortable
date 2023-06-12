(function ($) {
    $.fn.overSortable = function () {
        
        if ($(this).attr('overSortable')) {
            return;
        }

        console.log('overSortable');

        $(this).attr('overSortable', 1);

        /**
         * grid view element
         * @type {[type]}
         */
        var self = this;

        $(this).on('click', 'a[over-sortable]', function(e){
            e.preventDefault();

            var method = $(this).data('method') || 'get';
            var url = $(this).attr('href');

            $.ajax({
                type: method,
                url: url,
                data: {},
                success: success,
            }).fail(error);

            
            return false;
        })

        function success(data){
            $(self).yiiGridView('applyFilter');
        }

        function error(data){
            console.error('on sortable', data);
            alert(data);
        }

    };

    var grids = [];
    $.fn.overSortable.bind = function(gridId){
        if(grids.indexOf(gridId) < 0){
            grids.push(gridId);

            $(document).on('pjax:complete', function () {
                $('#'+gridId).overSortable();
            });
            $('#'+gridId).overSortable();
        }
    }

})(window.jQuery);
