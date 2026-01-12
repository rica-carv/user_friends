var e107 = e107 || {settings: {}, behaviors: {}};

(function ($)
{
	e107.behaviors.userFriendActions = {
		attach: function (context, settings)
		{
			$('button[data-userfriend-user]', context)
				.one('data-userfriend-user')
				.each(function ()
				{
					$(this).on('click', function (e)
					{
						e.preventDefault();

						var $this  = $(this);
						var userId = $this.attr('data-userfriend-user');
						var action = $this.attr('data-userfriend-action');
						var script = e107.settings.userfriendScript; // usa settings agora
						var bclass = $this.attr('class');

						if (!script || !userId) {
							console.error('UserFriends: missing data attributes');
							return;
						}

						$.ajax({
							type: 'POST',
							url: script,
							dataType: 'json',
							data: {
                				btn_class: bclass,
								user_id: userId,
								fr_action: action
							},
							success: function (d)
							{
								if (d.msg && e107.settings.userfriendShowAlert)
								{
									alert(d.msg);
								}

								if (d.html)
								{
									$('button[data-userfriend-user="'+userId+'"]').replaceWith(d.html);
                                    e107.attachBehaviors();
								}
							}
						});
					});
				});
		}
	};

})(jQuery);
