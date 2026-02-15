var e107 = e107 || { settings: {}, behaviors: {} };

(function ($)
{
    e107.behaviors.userFriendActions = {
        attach: function (context, settings)
        {
            var script = e107.settings.userfriendScript || '';
            var page   = e107.settings.userfriendPage || '';

            if (!script) {
                return;
            }

            $('button[data-userfriend-user], button[data-userfriend-id]', context)
                .one('userfriend-binding')
                .each(function ()
                {
                    $(this).on('click', function (e)
                    {
                        e.preventDefault();

                        var $btn       = $(this);
                        $btn.prop('disabled', true);
                        var userId     = $btn.attr('data-userfriend-user') || '';
                        var friendsId  = $btn.attr('data-userfriend-id') || '';
                        var action     = $btn.attr('data-userfriend-action');

                        if (!action) {
                            return;
                        }
    var cfg = e107.settings;

    // 👇👇👇 AQUI 👇👇👇
    if (action === 'add'        && cfg.allowAdd === false)      return;
    if (action === 'remove_req' && cfg.allowUnsend === false)   return;
    if (action === 'remove_fr'  && cfg.allowUnfriend === false) return;
    if (action === 'accept'     && cfg.allowAccept === false)   return;
    if (action === 'decline'    && cfg.allowDecline === false)  return;
                        $.ajax({
                            type: 'POST',
                            url: script,
                            dataType: 'json',
                            data: {
                                fr_action : action,
                                user_id   : userId,
                                friends_id: friendsId,
                                act_page  : page
                            },
                            success: function (d)
                            {
                                if (d.msg && e107.settings.userfriendShowAlert) {
                                    alert(d.msg);
                                }

if (d.html) {
    var $container = $btn.closest('[data-userfriend-container]');
    if ($container.length) {
        var tag = $container.prop('tagName').toLowerCase();

        var $feedback;
        if (tag === 'tr') {
            // Descobre o colspan total real da linha
            var colspanTotal = 0;
            $container.children('td').each(function() {
                colspanTotal += parseInt($(this).attr('colspan') || 1, 10);
            });
            $feedback = $('<tr data-userfriend-container=""><td colspan="' + colspanTotal + '">' + d.html + '</td></tr>');
        } else if (tag === 'li') {
            $feedback = $('<li data-userfriend-container="">' + d.html + '</li>');
        } else {
            $feedback = $('<div data-userfriend-container="">' + d.html + '</div>');
        }

        $container.replaceWith($feedback);

        // Botão de fechar remove totalmente o container
        $feedback.find('.s-message .btn-close').off('click').on('click', function() {
            $feedback.remove();
        });

    } else {
        // fallback antigo
        if (userId) {
            $('button[data-userfriend-user="' + userId + '"]').replaceWith(d.html);
        } else if (friendsId) {
            if (d.mode === 'msg') {
                $('[data-userfriend-controls]').prepend(d.html);
            } else {
                $('button[data-userfriend-id="' + friendsId + '"]')
                    .closest('[data-userfriend-controls]')
                    .html(d.html);
            }
        }
    }

    e107.attachBehaviors();
}

                                // AUTO-RESET opcional (controlado pelo backend)
                                if (d.reset) {
                                    setTimeout(function ()
                                    {
                                        var $controls = $('[data-userfriend-controls]');
                                        if (!$controls.length) {
                                            return;
                                        }

                                        $controls.load(
                                            location.href + ' [data-userfriend-controls] > *',
                                            function () {
                                                e107.attachBehaviors();
                                            }
                                        );
                                    }, d.reset);
                                }
                            },
                            error: function (xhr)
                            {
                                console.error('user_friends AJAX error:', xhr.responseText);
                            }
                        });
                    });
                });
        }
    };
})(jQuery);
