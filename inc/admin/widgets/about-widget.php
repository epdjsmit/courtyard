<?php
/**
 * About Widget section.
 */
class Courtyard_About_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array( 'classname' => 'pt-about-section', 'description' => esc_html__( 'Show a single page.', 'courtyard' ) );
        $control_ops = array( 'width' => 200, 'height' =>250 );
        parent::__construct( false, $name = esc_html__( 'PT: About Me', 'courtyard' ), $widget_ops, $control_ops);
    }

    function form( $instance ) {
        $instance = wp_parse_args(
            (array) $instance, array(
                'title'             => '',
                'sub_title'         => '',
                'page_id'           => '',
            )
        );
        ?>

        <div class="pt-about">

            <div class="pt-admin-input-wrap">

                <div class="pt-admin-input-label">
                    <label
                    for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'courtyard'); ?></label>
                </div><!-- .pt-admin-input-label -->

                <div class="pt-admin-input-holder">
                    <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>"
                       value="<?php echo esc_attr($instance['title']); ?>"
                       placeholder="<?php esc_attr_e('Title', 'courtyard'); ?>">
                </div><!-- .pt-admin-input-holder -->

                <div class="clear"></div>
 
            </div><!-- .pt-admin-input-wrap -->

            <div class="pt-admin-input-wrap">

                <div class="pt-admin-input-label">
                    <label
                    for="<?php echo $this->get_field_id('sub_title'); ?>"><?php esc_html_e('Sub Title', 'courtyard'); ?></label>
                </div><!-- .pt-admin-input-label -->

                <div class="pt-admin-input-holder">
                    <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('sub_title'); ?>"
                        name="<?php echo $this->get_field_name('sub_title'); ?>"
                        placeholder="<?php esc_attr_e('Short description', 'courtyard'); ?>"><?php echo esc_textarea($instance['sub_title']); ?></textarea>
                </div><!-- .pt-admin-input-holder -->

                <div class="clear"></div>
 
            </div><!-- .pt-admin-input-wrap -->

            <div class="pt-admin-input-wrap">

                <div class="pt-admin-input-label">
                    <label
                    for="<?php echo $this->get_field_id('page_id'); ?>"><?php esc_html_e('Page', 'courtyard'); ?></label>
                </div><!-- .pt-admin-input-label -->

                <div class="pt-admin-input-holder">
                    <?php wp_dropdown_pages( array(
                        'show_option_none'  => '',
                        'name'              => $this->get_field_name( 'page_id' ),
                        'selected'          => absint( $instance['page_id'] )
                    ) );
                    ?>
                </div><!-- .pt-admin-input-holder -->

                <div class="clear"></div>
 
            </div><!-- .pt-admin-input-wrap -->

        </div><!-- .pt-about -->
    <?php }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']             = sanitize_text_field( $new_instance['title'] );
        $instance['page_id']           = absint( $new_instance['page_id'] );

        if ( current_user_can( 'unfiltered_html' ) )
            $instance['sub_title'] = $new_instance['sub_title'];
        else
            $instance['sub_title'] = wp_kses( trim( wp_unslash( $new_instance['sub_title'] ) ), wp_kses_allowed_html( 'post' ) );
        return $instance;
    }

    function widget( $args, $instance ) {
        ob_start();
        extract($args);

        global $post;
        $title              = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '');
        $sub_title          = isset( $instance['sub_title'] ) ? $instance['sub_title'] : '';
        $pt_page_id         = isset( $instance['page_id'] ) ? $instance['page_id'] : '';

        $get_featured_pages = new WP_Query( array(
            'post_status'           => 'publish',
            'post_type'             =>  array( 'page' ),
            'page_id'               => $pt_page_id,
        ) );

        echo $args['before_widget'] = str_replace('<section', '<section', $args['before_widget']); ?>

        <div class="pt-about-sec">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!empty($title)) : ?>
                            <header>
                                <h2 class="widget-title"><?php echo esc_html($title); ?></h2>

                                <?php if (!empty($sub_title)) : ?>
                                    <h4><?php echo wp_kses_post($sub_title); ?></h4>
                                <?php endif; ?>
                            </header>
                        <?php endif; ?>
                    </div><!-- .col-md-12 -->

                    <div class="col-md-12">

                        <?php if ( $get_featured_pages->have_posts() && !empty( $pt_page_id ) ) : ?>

                            <?php while ($get_featured_pages->have_posts()) : $get_featured_pages->the_post();
                                $image_id = get_post_thumbnail_id();
                                $image_path = wp_get_attachment_image_src( $image_id, 'courtyard-600x450', true );
                                $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                                $alt = !empty( $image_alt ) ? $image_alt : the_title_attribute( 'echo=0' ) ;
                                ?>
                                <div class="pt-about-col">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <figure>
                                            <img title="<?php the_title_attribute(); ?>" src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
                                        </figure>
                                    <?php endif; ?>
                                    <article class="pt-about-cont">
                                        <h3><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>

                                        <p><?php the_content(); ?></p>
                                    </article><!-- .pt-about-cont -->
                                </div><!-- .pt-about-col -->

                            <?php endwhile;

                            // Reset Post Data
                            wp_reset_postdata(); ?>

                        <?php endif; ?>

                    </div><!-- .col-md-12 -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .pt-about-sec -->

        <?php echo $args['after_widget'];
        ob_end_flush();
    }
}