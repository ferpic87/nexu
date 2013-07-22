<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('hj.starrating.base');

hj.starrating.base.init = function() {

    $('.hj-ajaxed-starrating')
    .each(function() {
		$(this).closest('div.hj-starrating-container').removeClass('hidden');
        var id = $(this).attr('id');
        if ($(this).attr('rel') != 'stars') {
            var container = $('#' + id);
            container.children().not("select.hj-starrating-default-select").hide();

            var caption = container.siblings('div.hj-starrating-caption');

            container.stars({
                inputType: "select",
                oneVoteOnly: true,
                callback: function(ui, type, value) {
                    elgg.system_message(elgg.echo('hj:starrating:saving'));

                    elgg.action(container.attr('action'), {
                        data : {
                            starrating : value
                        },
                        success : function(output) {

                            var data = output.output,
                            average_value = data.average_value,
                            stats = data.stats;

                            ui.select(average_value);

                            caption.text(stats);
                        }
                    });
                }
            });
            $(this).attr('rel', 'stars');
        }
    });
}
elgg.register_hook_handler('init', 'system', hj.starrating.base.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', hj.starrating.base.init, 500);

<?php if (FALSE) : ?></script><?php endif; ?>