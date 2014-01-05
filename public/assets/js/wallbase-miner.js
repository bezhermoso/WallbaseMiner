/**
 * @author Bezalel Hermoso
 *
 */

/**
 *
 * @param favoriteId
 * @constructor
 */
var WallbaseMiner = function (favoriteId) {

    this.favoriteId = favoriteId;

    this.deferred = $.Deferred();

    this.data = [];

    this.minerUrl = 'http://bez.im/wallbase-miner';

    this.setMinerUrl = function (url) {
        this.minerUrl = url;
    };

    this.mine = function () {

        var miner = this;

        miner.deferred.progress(function (data) {
            miner.data = miner.data.concat(data);
        }).done(function () {
            $.ajax({
                url: miner.minerUrl + '/api/job-request',
                data: JSON.stringify(miner.data),
                type: 'POST',
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                }
            });
        });
        this._doRequest(0);
    };

    this._doRequest = function (index)
    {
        var miner = this;
        $.get("http://wallbase.cc/favorites/" + miner.favoriteId + "/" + index, function (html) {

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

                miner.deferred.notify(data);
                miner._doRequest(index + 32);

            } else {
                miner.deferred.resolve();
            }

        }, 'html').fail(function() {
                miner._doRequest(index);
        });
    }
};