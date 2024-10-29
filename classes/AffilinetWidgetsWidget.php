<?php

class AffilinetWidgetsWidget extends \WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => __NAMESPACE__ . '\\' . __CLASS__,
            'description' => 'affilinet Widgets'
        );
        parent::__construct('AffilinetWidgetsWidget', 'affilinet Widgets', $widget_ops);

    }

    /**
     * Display the widget edit form
     *
     * @param array $instance
     *
     * @return void
     */
    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance);
        $widget_id = $instance['widget_id'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('widget_id'); ?>"><?php _e('widgets.selectWidget', 'affilinet-product-widgets'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('widget_id'); ?>"
                    name="<?php echo $this->get_field_name('widget_id'); ?>">
                <?php
                    foreach (\AffilinetWidgetsApi::getMyWidgets() as $widget) {
                        ?>
                        <option
                            value="<?php echo $widget['id']; ?>"
                            <?php selected($widget_id, $widget['id']); ?>><?php echo $widget['widgetName'] .  ' (' . count($widget['products']) . ' ' . __('widgets.products', 'affilinet-product-widgets').')'; ?></option>

                        <?php
                    }


                ?>
            </select>
            <button type="button" onclick="openInWidgetsPageInBrowserExtension(jQuery('#<?php echo $this->get_field_id('widget_id'); ?>').val())" class="button-primary affilinet-browser-extension-show-v2"><?php _e('admin.editInExtension', 'affilinet-product-widgets');?></button>
        </p>
        <?php
    }




    /**
     * Handle widget update process
     *
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['widget_id'] = $new_instance['widget_id'];

        return $instance;

    }

    /**
     * Display the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        extract($args);
        /** @var String $before_widget */
        echo $before_widget;

        echo self::getAdCode($instance['widget_id']);

        /** @var String $after_widget */
        echo $after_widget;
    }



    public function getAdCode($widgetId)
    {
	    return '<div id="affilinet-product-widget-'
                . $widgetId
                . '" class="affilinet-product-widget" data-affilinet-widget-id="'
                . $widgetId
                . '"><link rel="stylesheet" type="text/css" href="https://productwidget.com/style-1.0.0.css">'
                . '<script type="text/javascript">!function(d){var e,i = \'affilinet-product-widget-script\';'
                . 'if(!d.getElementById(i)){e = d.createElement(\'script\');e.id = i;e.src = \'https://productwidget.com/affilinet-product-widget-1.0.0-min.js\''
                .';d.body.appendChild(e);}if (typeof window.__affilinetWidget===\'object\')if (d.readyState===\'complete\'){window.__affilinetWidget.init();}}(document);</script></div>';
    }
}
