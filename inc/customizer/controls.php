<?php
/**
 * Courtyard Theme Customizer Controls.
 *
 * @package Courtyard
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

    // Custom Checkbox Control Class
    class WP_Customize_Checkbox_Control extends WP_Customize_Control
    {
        public $type = 'checkbox';

        public function render_content()
        {
            ?>

            <label>
                <span class="pt-checkbox-label"><?php echo esc_html($this->label); ?></span>

                <span class="pt-on-off-switch">
                    <input class="pt-on-off-switch-checkbox" type="checkbox"
                           value="<?php echo esc_attr($this->value()); ?>" <?php $this->link();
                    checked($this->value()); ?> />
                    <span class="pt-on-off-switch-label"></span>
                </span>

                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>
            <?php
        }
    }

    // Custom Font Size Control Class
    class WP_Customize_Font_Control extends WP_Customize_Control
    {

        public function render_content()
        {
            ?>

            <label class="pt-customizer-font">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <input type="range" min="0" max="100"
                       value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
                <input type="number" min="0" max="100"
                       value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
            </label>

            <?php
        }
    }

    // Image radio control
    class WP_Customizer_Image_Radio_Control extends WP_Customize_Control
    {

        public function render_content()
        {

            if (empty($this->choices))
                return;

            $name = '_customize-radio-' . $this->id;

            ?>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <ul class="controls" id='pt-img-container'>

                <?php foreach ($this->choices as $value => $label) :

                    $class = ($this->value() == $value) ? 'pt-radio-img-selected pt-radio-img-img' : 'pt-radio-img-img';

                    ?>

                    <li style="display: inline;">

                        <label>

                            <input <?php $this->link(); ?>style='display:none' type="radio"
                                   value="<?php echo esc_attr($value); ?>"
                                   name="<?php echo esc_attr($name); ?>" <?php $this->link();
                            checked($this->value(), $value); ?> />

                            <img src='<?php echo esc_url($label); ?>' class='<?php echo esc_attr($class); ?>'/>

                        </label>

                    </li>

                <?php endforeach; ?>

            </ul>

            <?php
        }
    }

    // Theme Color
    class courtyard_theme_color_picker extends WP_Customize_Control
    {

        /**
         * Render the content on the theme customizer page
         */
        public function render_content()
        {

            if (empty($this->choices))
                return;

            $name = $this->id;

            ?>

            <h3 class="courtyard-layout-title"><?php echo esc_html($this->label); ?></h3>

            <?php foreach ($this->choices as $value => $label) : ?>

            <input type="radio" id="<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($value); ?>"
                   name="<?php echo esc_attr($name); ?>" <?php $this->link();
            checked($this->value(), $value); ?> />

            <label for="<?php echo esc_attr($value); ?>">
                <?php echo esc_html($label); ?>
                <span class="courtyard-radio-color">
                        <span class="courtyard-color-checked"></span>
                    </span>
            </label>

            <?php

        endforeach;
        }
    }

    //Repeatable Controls
    class Courtyard_Customize_Repeatable_Control extends WP_Customize_Control {

        /**
         * The type of customize control being rendered.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $type = 'repeatable';

        // public $fields = array();

        public $fields = array();
        public $live_title_id = null;
        public $title_format = null;


        public function __construct( $manager, $id, $args = array() )
        {
            parent::__construct( $manager, $id, $args);
            if ( empty( $args['fields'] ) || ! is_array( $args['fields'] ) ) {
                $args['fields'] = array();
            }

            foreach ( $args['fields'] as $key => $op ) {
                $args['fields'][ $key ]['id'] = $key;
                if( ! isset( $op['value'] ) ) {
                    if( isset( $op['default'] ) ) {
                        $args['fields'][ $key ]['value'] = $op['default'];
                    } else {
                        $args['fields'][ $key ]['value'] = '';
                    }
                }
            }

            $this->fields = $args['fields'];
            $this->live_title_id = isset( $args['live_title_id'] ) ? $args['live_title_id'] : false;
            if ( isset( $args['title_format'] ) && $args['title_format'] != '' ) {
                $this->title_format = $args['title_format'];
            } else {
                $this->title_format = '';
            }

        }

        public function to_json() {
            parent::to_json();
            $this->json['live_title_id'] = $this->live_title_id;
            $this->json['title_format']  = $this->title_format;
            $this->json['value']         = $this->value();
            $this->json['fields']        = $this->fields;

        }

        public function render_content() {
            ?>

            <label>
                <?php if ( ! empty( $this->label ) ) : ?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php endif; ?>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
            </label>

            <input data-hidden-value type="hidden" <?php $this->input_attrs(); ?> value="" <?php $this->link(); ?> />

            <div class="form-data">
                <ul class="list-repeatable">
                </ul>
            </div>

            <div class="repeatable-actions">
                <span class="button-secondary add-new-repeat-item"><?php esc_html_e( 'Add a Item', 'courtyard' ); ?></span>
            </div>

            <script type="text/html" class="repeatable-js-template">
                <?php $this->js_item(); ?>
            </script>
            <?php
        }


        public function js_item( ){

            ?>
            <li class="repeatable-customize-control">
                <div class="widget">
                    <div class="widget-top">
                        <div class="widget-title-action">
                            <a class="widget-action" href="#"></a>
                        </div>
                        <div class="widget-title">
                            <h4 class="live-title"><?php esc_html_e( '[Untitled]', 'courtyard' ); ?></h4>
                        </div>
                    </div>

                    <div class="widget-inside">

                        <div class="form">
                            <div class="widget-content">

                                <# for ( i in data ) { #>
                                    <# if ( ! data.hasOwnProperty( i ) ) continue; #>
                                        <# field = data[i]; #>
                                            <# if ( ! field.type ) continue; #>


                                                <# if ( field.type ){ #>

                                                    <div class="item item-{{{ field.type }}} item-{{{ field.id }}}">

                                                        <# if ( field.type !== 'checkbox' ) { #>
                                                            <# if ( field.title ) { #>
                                                                <label class="field-label">{{ field.title }}</label>
                                                                <# } #>

                                                                    <# if ( field.desc ) { #>
                                                                        <p class="field-desc description">{{ field.desc }}</p>
                                                                        <# } #>
                                                                            <# } #>


                                                                                <# if ( field.type === 'text' ) { #>

                                                                                    <input data-live-id="{{{ field.id }}}" type="text" value="{{ field.value }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="">

                                                                                    <# } else if ( field.type === 'checkbox' ) { #>

                                                                                        <# if ( field.title ) { #>
                                                                                            <label class="checkbox-label">
                                                                                                <input type="checkbox" <# if ( field.value ) { #> checked="checked" <# } #> value="1" data-repeat-name="_items[__i__][{{ field.id }}]" class="">
                                                                                                        {{ field.title }}</label>
                                                                                            <# } #>

                                                                                                <# if ( field.desc ) { #>
                                                                                                    <p class="field-desc description">{{ field.desc }}</p>
                                                                                                    <# } #>


                                                                                                        <# } else if ( field.type === 'select' ) { #>

                                                                                                            <# if ( field.multiple ) { #>
                                                                                                                <select multiple="multiple" data-repeat-name="_items[__i__][{{ field.id }}][]">
                                                                                                                    <# } else  { #>
                                                                                                                        <select data-repeat-name="_items[__i__][{{ field.id }}]">
                                                                                                                            <# } #>

                                                                                                                                <# for ( k in field.options ) { #>

                                                                                                                                    <# if ( _.isArray( field.value ) ) { #>
                                                                                                                                        <option <# if ( _.contains( field.value , k ) ) { #> selected="selected" <# } #>  value="{{ k }}">{{ field.options[k] }}</option>
                                                                                                                                                <# } else { #>
                                                                                                                                                    <option <# if ( field.value == k ) { #> selected="selected" <# } #>  value="{{ k }}">{{ field.options[k] }}</option>
                                                                                                                                                            <# } #>

                                                                                                                                                                <# } #>

                                                                                                                        </select>

                                                                                                                        <# } else if ( field.type === 'radio' ) { #>

                                                                                                                            <# for ( k in field.options ) { #>

                                                                                                                                <# if ( field.options.hasOwnProperty( k ) ) { #>

                                                                                                                                    <label>
                                                                                                                                        <input type="radio" <# if ( field.value == k ) { #> checked="checked" <# } #> value="{{ k }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="widefat">
                                                                                                                                                {{ field.options[k] }}
                                                                                                                                    </label>

                                                                                                                                    <# } #>
                                                                                                                                        <# } #>

                                                                                                                                            <# } else if ( field.type == 'color' ) { #>

                                                                                                                                                <input type="text" value="{{ field.value }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="color-field">

                                                                                                                                                <# } else if ( field.type == 'media' ) { #>

                                                                                                                                                    <input type="hidden" value="{{ field.value.url }}" data-repeat-name="_items[__i__][{{ field.id }}][url]" class="image_url widefat">
                                                                                                                                                    <input type="hidden" value="{{ field.value.id }}" data-repeat-name="_items[__i__][{{ field.id }}][id]" class="image_id widefat">

                                                                                                                                                    <div class="current <# if ( field.value.url !== '' ){ #> show <# } #>">
                                                                                                                                                        <div class="container">
                                                                                                                                                            <div class="attachment-media-view attachment-media-view-image landscape">
                                                                                                                                                                <div class="thumbnail thumbnail-image">
                                                                                                                                                                    <# if ( field.value.url !== '' ){ #>
                                                                                                                                                                        <img src="{{ field.value.url }}" alt="">
                                                                                                                                                                        <# } #>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>

                                                                                                                                                    <div class="actions">
                                                                                                                                                        <button class="button remove-button " <# if ( field.value.url == '' ){ #> style="display:none"; <# } #> type="button"><?php esc_html_e( 'Remove','courtyard' ) ?></button>

                                                                                                                                                                <button class="button upload-button" data-add-txt="<?php esc_attr_e( 'Add Image', 'courtyard' ); ?>" data-change-txt="<?php esc_attr_e( 'Change Image', 'courtyard' ); ?>" type="button"><# if ( field.value.url == '' ){ #> <?php esc_html_e( 'Add Image', 'courtyard' ); ?> <# } else { #> <?php esc_html_e( 'Change Image', 'courtyard' ); ?> <# } #> </button>
                                                                                                                                                                <div style="clear:both"></div>
                                                                                                                                                    </div>


                                                                                                                                                    <# } else if ( field.type == 'textarea' ) { #>

                                                                                                                                                        <textarea data-live-id="{{{ field.id }}}" data-repeat-name="_items[__i__][{{ field.id }}]">{{ field.value }}</textarea>

                                                                                                                                                        <# } #>

                                                    </div>


                                                    <# } #>
                                                        <# } #>


                                                            <div class="widget-control-actions">
                                                                <div class="alignleft">
                                                                    <a href="#" class="repeat-control-remove" title=""><?php esc_html_e( 'Remove', 'courtyard' ); ?></a> |
                                                                    <a href="#" class="repeat-control-close"><?php esc_html_e( 'Close', 'courtyard' ); ?></a>
                                                                </div>
                                                                <br class="clear">
                                                            </div>

                            </div>
                        </div><!-- .form -->

                    </div>

                </div>
            </li>
            <?php

        }

    }

}
