$(function () {

    var deferred = $.Deferred();

    var dataMined = [];

    deferred.progress(function (data) {
        dataMined = dataMined.concat(data);
    }).done(function () {
            $.ajax({
                url: 'http://localhost/wallbase-miner/public/api/job-request',
                data: JSON.stringify(dataMined),
                type: 'POST',
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                }
            });
    });

    function mine_favorites(index, deferred)
    {
        $.get("http://wallbase.cc/favorites/32223/" + index, function (html) {

            var $frag = $(html);

            if (!$frag.hasClass('notice1')) {

                $frag = $('<div />').html($frag);

                var data = [];

                $('.thumbnail', $frag).each(function () {

                    var item = {
                        tags: [],
                        id: [],
                        purity: 0,
                        thumbnail: '',
                        imageUrl: ''
                    };

                    var classes = $(this).attr('class');
                    var matches = classes.match(/\bpurity-([0-9]+)\b/);

                    if (matches.length) {
                        item.purity = matches[1];
                    }

                    var tagData = $(this).data('tags').split('||');

                    $.each(tagData, function (i, t) {
                        var segments = t.split('|');
                        item.tags.push(segments[0]);
                    });

                    var $img = $('img.file', this);
                    var src = $img.data('original');

                    item.thumbnail = src;
                    item.imageUrl = src.replace(/^http:\/\/thumbs/, "http://wallpapers").replace(/thumb-/, "wallpaper-");

                    item.id = $('.wrapper a.closeTrash', this).data('id');

                    data.push(item);
                });

                deferred.notify(data);
                mine_favorites(index + 32, deferred);

            } else {
                deferred.resolve();
            }

        }, 'html').fail(function() {
            mine_favorites(index, deferred);
        });
    }

    mine_favorites(0, deferred);

});



