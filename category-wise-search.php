<?php
/*
Plugin Name: Maldita Category Wise Search Widget
Description: Category Wise Search Widget plugin.You have option search specific category content
Version: 2.1
Original Author: Shambhu Prasad Patnaik
Original Author URI: http://socialcms.wordpress.com/

Current Author: David Fernández for Maldita.es
*/

class Category_Wise_Search_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {

        parent::__construct(
            'category_wise_search', // Base ID
            'Category Wise Search', // Name
            array('classname' => 'widget_search', 'description' => __('A search form for your site with category', 'text_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $category_id = $instance['category_id'];
        ?>
        <?php echo $before_widget; ?>
        <?php if ($title) echo $before_title . $title . $after_title; ?>
        <div class="widget-content">
            <?php
            $form = '<form role="search" method="get" id="searchform" action="' . esc_url(home_url('/')) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label> 
	<input type="hidden" name="cat" id="cat" value="' . $category_id . '" /> 
	<input type="text" class="fullwidth" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" class="fullwidth margin-content" id="searchsubmit" value="' . esc_attr__('Search') . '" />
	</div>
	</form>';
            echo apply_filters('get_search_form', $form);
            ?>


            <?php echo $after_widget; ?>
        </div>
        <?php
        // Reset the global $the_post as this query will have stomped on it
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['category_id'] = strip_tags($new_instance['category_id']);
        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title = $instance['title'];
        $category_id = isset($instance['category_id']) ? strip_tags($instance['category_id']) : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category_id'); ?>"><?php _e('Categoría de búsqueda :'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('category_id'); ?>"
                   name="<?php echo $this->get_field_name('category_id'); ?>" type="text"
                   value="<?php echo $category_id; ?>"/>
            <br>Introduce el ID de la categoría a buscar.
        </p>
        <?php
    }
} // class Category_Wise_Search

// register Category_Wise_Search_Widget widget
add_action('widgets_init', create_function('', 'register_widget( "Category_Wise_Search_Widget" );'));
register_deactivation_hook(__FILE__, 'shambhu_plugin_deactivate');

function shambhu_plugin_deactivate()
{
    unregister_widget('Category_Wise_Search_Widget');
}

?>