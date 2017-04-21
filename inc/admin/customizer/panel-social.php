<?php
/**
 * Courtyard Customizer Social Panel
 *
 * @package Courtyard
 */
$wp_customize->add_panel( 'courtyard_social_panel', array(
    'priority'              => 110,
    'title'                 => esc_html__( 'Social', 'courtyard' ),
) );
// Social icon
$wp_customize->add_section( 'courtyard_pro_social_icon_section', array(
    'priority'              => '1',
    'title'                 => esc_html__( 'Social Profiles', 'courtyard' ),
    'panel'                 => 'courtyard_social_panel',
) );

// Repeatable Social Icons
$wp_customize->add_setting( 'courtyard_repeatable_social_icons', array(
    'default' => json_encode(
        array(
            array(
                'pt_social_label' => esc_html__('Facebook','courtyard'),
                'pt_social_url'   => 'https://www.facebook.com/PreciseThemes/',
                'pt_social_icon'  => 'fa-facebook',
            ),
        )
    ),
    'sanitize_callback' => 'courtyard_sanitize_repeatable_data_field',
) );

$wp_customize->add_control( new Courtyard_Customize_Repeatable_Control( $wp_customize, 'courtyard_repeatable_social_icons', array(
        'label'         => esc_html__('Scoial', 'courtyard'),
        'description'   => esc_html__('Add your social profiles.', 'courtyard'),
        'section'       => 'courtyard_pro_social_icon_section',
        'live_title_id' => 'pt_social_label', // apply for unput text and textarea only
        'title_format'  => '[live_title]', // [live_title]

        'fields'    => array(
            'pt_social_label' => array(
                'title'=>esc_html__('Name', 'courtyard'),
                'type'=>'text',
            ),
            'pt_social_url' => array(
                'title'=>esc_html__('URL', 'courtyard'),
                'type'=>'text',
            ),
            'pt_social_icon' => array(
                'title'=>esc_html__('Icon', 'courtyard'),
                'type'=>'text',
                'desc' =>esc_html__( 'Info:- Enter font awesome icon class here. For example: fa-facebook.', 'courtyard' ),
            ),
        ),

    ) )
);
