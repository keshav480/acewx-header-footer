<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

class Acewx_Site_Logo_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'site_logo';
    }

    public function get_title() {
        return __( 'Site Logo', 'acewx-header-footer' );
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'acewx-header-footer' ),
            ]
        );
        $default_logo = [];
        if ( has_custom_logo() ) {
            $logo_id = get_theme_mod( 'custom_logo' );
            $default_logo = [
                'id'  => $logo_id,
                'url' => wp_get_attachment_image_url( $logo_id, 'full' ),
            ];
        }

        // Elementor override logo
        $this->add_control(
            'logo_image',
            [
                'label' => __( 'Logo (Overrides Site Logo)', 'acewx-header-footer' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
                'default' => $default_logo,
            ]
        );

        // Width
        $this->add_control(
            'logo_width',
            [
                'label' => __( 'Logo Width (px)', 'acewx-header-footer' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 150,
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Frontend Render
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Elementor logo
        if ( ! empty( $settings['logo_image']['url'] ) ) {
            $logo_url = $settings['logo_image']['url'];
        }
        // Fallback to Site Logo
        elseif ( has_custom_logo() ) {
            $logo_id  = get_theme_mod( 'custom_logo' );
            $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        } else {
            return;
        }

        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-site-logo">';
        echo '<img 
                src="' . esc_url( $logo_url ) . '" 
                style="width:' . esc_attr( $settings['logo_width']['size'] ) . 'px;"
                alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"
              >';
        echo '</a>';
    }

    /**
     * Live Preview (Editor)
     */
    protected function content_template() {
        ?>
        <#
        var logoUrl = '';

        if ( settings.logo_image && settings.logo_image.url ) {
            logoUrl = settings.logo_image.url;
        } else if ( elementor.config.customLogo ) {
            logoUrl = elementor.config.customLogo.url;
        }

        if ( logoUrl ) { #>
            <a href="{{ elementor.config.home_url }}" class="custom-site-logo">
                <img 
                    src="{{ logoUrl }}"
                    style="width: {{ settings.logo_width.size }}px;"
                    alt="{{ elementor.config.siteTitle }}"
                >
            </a>
        <# } #>
        <?php
    }
}

// Register Widget
$widgets_manager->register( new Acewx_Site_Logo_Widget() );

$widgets_manager->register( new Acewx_Nav_Menu_Widget() );

class Acewx_Nav_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom_nav_menu';
    }

    public function get_title() {
        return __( 'Custom Nav Menu', 'acewx-header-footer' );
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();
        $options = [];

        foreach ( $menus as $menu ) {
            $options[ $menu->slug ] = $menu->name;
        }

        return $options;
    }

    protected function register_controls() {

    /* =======================
     * CONTENT TAB
     * ======================= */
    $this->start_controls_section(
        'section_content',
        [ 'label' => __( 'Menu Settings', 'acewx-header-footer' ) ]
    );

     $this->add_control(
            'nav_menu',
            [
                'label'   => __( 'Select Menu', 'acewx-header-footer' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_available_menus(),
            ]
        );
    $this->add_control(
        'menu_alignment',
        [
            'label' => __( 'Alignment', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left'   => [ 'title' => 'Left',   'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
            ],
            'default'      => 'left',
            'render_type'  => 'template',
            'prefix_class' => 'align-',
        ]
    );
   $this->add_control(
            'enable_hamburger',
            [
                'label'        => __( 'Mobile Hamburger', 'acewx-header-footer' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'render_type'  => 'template',
            ]
        );

    $this->end_controls_section();


    /* =======================
     * STYLE TAB – MENU
     * ======================= */
    $this->start_controls_section(
        'section_style_menu',
        [
            'label' => __( 'Menu Items', 'acewx-header-footer' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_responsive_control(
        'menu_item_icon',
        [
            'label' => __( 'Menu Icon', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::ICONS,
            'skin'  => 'inline',
            'render_type' => 'template',
        ]
    );
    
    $this->add_responsive_control(
        'menu_icon_size',
        [
            'label' => __( 'Icon Size', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => [ 'min' => 8, 'max' => 40 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .acewx-menu-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .acewx-menu-item-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->add_control(
    'menu_icon_color',
    [
        'label' => __( 'Icon Color', 'acewx-header-footer' ),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .acewx-menu-item-icon' => 'color: {{VALUE}};',
            '{{WRAPPER}} .acewx-menu-item-icon svg' => 'fill: {{VALUE}};',
        ],
    ]
);
    $this->add_control(
        'menu_icon_position',
        [
            'label'   => __( 'Icon Position', 'acewx-header-footer' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'before' => [
                    'title' => __( 'Before', 'acewx-header-footer' ),
                    'icon'  => 'eicon-h-align-left',
                ],
                'after' => [
                    'title' => __( 'After', 'acewx-header-footer' ),
                    'icon'  => 'eicon-h-align-right',
                ],
            ],
            'default' => 'before',
            'toggle'  => false,
            'render_type' => 'template',
        ]
    );
    
        
    $this->add_control(
        'menu_icon_align',
        [
            'label' => __( 'Icon Align', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __( 'Left', 'acewx-header-footer' ),
                    'icon'  => 'eicon-h-align-left',
                ],
                'right' => [
                    'title' => __( 'Right', 'acewx-header-footer' ),
                    'icon'  => 'eicon-h-align-right',
                ],
            ],
            'default' => 'left',
            'render_type' => 'template',
        ]
    );


    $this->add_responsive_control(
    'menu_item_padding_vertical',
        [
            'label' => __( 'Vertical Padding', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [ 'min' => 0, 'max' => 60 ],
                'em' => [ 'min' => 0, 'max' => 5 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' =>
                    'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]
    ); 

    $this->add_responsive_control(
        'menu_item_padding_horizontal',
        [
            'label' => __( 'Horizontal Padding', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [ 'min' => 0, 'max' => 100 ],
                'em' => [ 'min' => 0, 'max' => 8 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' =>
                    'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
        ]
    );
    $this->add_control(
                'menu_gap',
                [
                    'label' => __( 'Menu Item Gap', 'acewx-header-footer' ),
                    'type'  => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 5,
                        ],
                    ],
                    'default' => [
                        'size' => 20,
                        'unit' => 'px',
                    ],
                    'render_type' => 'template',
                    'selectors' => [
                        '{{WRAPPER}} .custom-nav-menu ul.menu' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            

    // Text Color
    $this->add_control(
        'menu_text_color',
        [
            'label' => __( 'Text Color', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' => 'color: {{VALUE}};',
            ],
        ]
    );

    // Hover Color
    $this->add_control(
        'menu_text_hover_color',
        [
            'label' => __( 'Hover Color', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li > a:hover' => 'color: {{VALUE}};',
            ],
        ]
    );


    // Typography
    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name'     => 'menu_typography',
            'selector' => '{{WRAPPER}} .custom-nav-menu ul.menu > li > a',
        ]
    );

    // Padding
    $this->add_responsive_control(
        'menu_item_padding',
        [
            'label' => __( 'Padding', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' =>
                    'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->add_control(
            'border_heading',
            [
                'label' => __( 'Border Settings', 'acewx-header-footer' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
    'menu_item_border_type',
    [
        'label' => __( 'Border Type', 'acewx-header-footer' ),
        'type'  => \Elementor\Controls_Manager::SELECT,
        'options' => [
            'none'   => __( 'None', 'acewx-header-footer' ),
            'solid'  => __( 'Solid', 'acewx-header-footer' ),
            'dashed' => __( 'Dashed', 'acewx-header-footer' ),
            'dotted' => __( 'Dotted', 'acewx-header-footer' ),
            'double' => __( 'Double', 'acewx-header-footer' ),
        ],
        'default' => 'none',
        'selectors' => [
            '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' => 'border-style: {{VALUE}};',
        ],
    ]
);
$this->add_control(
    'menu_item_border_color',
    [
        'label' => __( 'Border Color', 'acewx-header-footer' ),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' => 'border-color: {{VALUE}};',
        ],
    ]
);
$this->add_responsive_control(
    'menu_item_border_width',
    [
        'label' => __( 'Border Width', 'acewx-header-footer' ),
        'type'  => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px' ],
        'selectors' => [
            '{{WRAPPER}} .custom-nav-menu ul.menu > li > a' =>
                'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);
    $this->end_controls_section();


    /* =======================
     * STYLE TAB – SUB MENU
     * ======================= */
    $this->start_controls_section(
        'section_style_submenu',
        [
            'label' => __( 'Sub Menu', 'acewx-header-footer' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    // Submenu background
    $this->add_control(
        'submenu_bg_color',
        [
            'label' => __( 'Background Color', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.sub-menu' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    // Submenu item color
    $this->add_control(
        'submenu_text_color',
        [
            'label' => __( 'Text Color', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.sub-menu li a' => 'color: {{VALUE}};',
            ],
        ]
    );
    $this->add_control(
            'submenu_text_hover_color',
            [
                'label' => __( 'Hover Color', 'acewx-header-footer' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-nav-menu ul.sub-menu > li > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
          $this->add_control(
            'border_heading',
            [
                'label' => __( 'Border Settings', 'acewx-header-footer' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
    $this->add_control(
        'submenu_item_border_type',
        [
            'label' => __( 'Border Type', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none'   => __( 'None', 'acewx-header-footer' ),
                'solid'  => __( 'Solid', 'acewx-header-footer' ),
                'dashed' => __( 'Dashed', 'acewx-header-footer' ),
                'dotted' => __( 'Dotted', 'acewx-header-footer' ),
                'double' => __( 'Double', 'acewx-header-footer' ),
            ],
            'default' => 'none',
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.sub-menu > li > a' => 'border-style: {{VALUE}};',
            ],
        ]
);
 $this->add_responsive_control(
    'submenu_menu_item_padding_vertical',
        [
            'label' => __( 'Vertical Padding', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [ 'min' => 0, 'max' => 60 ],
                'em' => [ 'min' => 0, 'max' => 5 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.menu > li .sub-menu > li > a' =>
                'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]
    ); 
    $this->add_control(
        'submenu_item_border',
        [
            'label' => __( 'Border Color', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.sub-menu > li > a' => 'border-color: {{VALUE}};',
            ],
        ]
    );
    $this->add_responsive_control(
        'submenu_item_border_width',
        [
            'label' => __( 'Border Width', 'acewx-header-footer' ),
            'type'  => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .custom-nav-menu ul.sub-menu > li > a' =>
                    'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

        // Submenu typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'submenu_typography',
                'selector' => '{{WRAPPER}} .custom-nav-menu ul.sub-menu li a',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['nav_menu'] ) ) return;

        // ---------- Desktop Icon HTML ----------
        $desktop_icon_html = '';
        if ( ! empty( $settings['menu_item_icon']['value'] ) ) {
            $desktop_icon_html = '<span class="acewx-menu-item-icon icon-desktop">';
            ob_start();
            \Elementor\Icons_Manager::render_icon( $settings['menu_item_icon'], [ 'aria-hidden' => 'true' ] );
            $desktop_icon_html .= ob_get_clean();
            $desktop_icon_html .= '</span>';
        }

        // ---------- Mobile Icon HTML ----------
        $mobile_icon_html = '';
        if ( ! empty( $settings['menu_item_icon_mobile']['value'] ) ) {
            $mobile_icon_html = '<span class="acewx-menu-item-icon icon-mobile">';
            ob_start();
            \Elementor\Icons_Manager::render_icon( $settings['menu_item_icon_mobile'], [ 'aria-hidden' => 'true' ] );
            $mobile_icon_html .= ob_get_clean();
            $mobile_icon_html .= '</span>';
        } else {
            // fallback to desktop icon
            $mobile_icon_html = $desktop_icon_html;
        }

        // Walkers
        $desktop_walker = new Acewx_Menu_Icon_Walker( $desktop_icon_html, $settings['menu_icon_position'] );
        $mobile_walker  = new Acewx_Menu_Icon_Walker( $mobile_icon_html, $settings['menu_icon_position'] );
        ?>
        <!-- Desktop Nav -->
        <nav class="custom-nav-menu desktop align-<?php echo esc_attr($settings['menu_alignment']); ?> layout-<?php echo esc_attr($settings['menu_layout']); ?> icon-<?php echo esc_attr($settings['menu_icon_align']); ?>">
            <?php if ( $settings['enable_hamburger'] === 'yes' ) : ?>
                <button class="acewx-hf-menu-toggle" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            <?php endif; ?>
            <div class="menu-wrapper <?php echo esc_attr($settings['enable_hamburger']) === 'yes' ? 'humburger' : ''; ?>">
                <?php
                wp_nav_menu([
                    'menu'        => $settings['nav_menu'],
                    'container'   => false,
                    'menu_class'  => 'menu',
                    'walker'      => $desktop_walker,
                ]);
                ?>
            </div>
        </nav>
        <!-- Mobile Sidebar -->
        <div id="custom-nav-menu-sidebar">
            <a href="javascript:void(0)" id="sidebar-close-menu-sidebar" class="sidebar-close-menu-sidebar">&times; Close</a>
            <?php
            wp_nav_menu([
                'menu'        => $settings['nav_menu'],
                'container'   => false,
                'menu_class'  => 'menu',
                'walker'      => $mobile_walker,
            ]);
            ?>
        </div>
        <?php
    }
}
class Acewx_Menu_Icon_Walker extends Walker_Nav_Menu {

    private $icon_html;
    private $icon_position;

    public function __construct( $icon_html, $icon_position ) {
        $this->icon_html     = $icon_html;
        $this->icon_position = $icon_position;
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $class_names = implode( ' ', $classes );

        $has_children = in_array( 'menu-item-has-children', $classes );

        $output .= '<li class="' . esc_attr( $class_names ) . '">';

        $title = esc_html( $item->title );

        // ONLY add icon if submenu exists
        if ( $has_children && $this->icon_html ) {
            if ( $this->icon_position === 'before' ) {
                $title = $this->icon_html . $title;
            } else {
                $title = $title . $this->icon_html;
            }
        }

        $atts = ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';

        $output .= '<a' . $atts . '>' . $title . '</a>';
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}