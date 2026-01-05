<?php
/**
 * Customizer Custom Controls
 *
 * @package Soplos
 */

if (!class_exists('WP_Customize_Control')) {
    return;
}

/**
 * Social Manager Control (Sort + Edit)
 */
class Soplos_Social_Manager_Control extends WP_Customize_Control {
    public $type = 'soplos-social-manager';

    public function enqueue() {
        wp_enqueue_script('jquery-ui-sortable');
        
        $script  = "jQuery(document).ready(function($) {";
        $script .= "  if ( typeof wp !== 'undefined' && wp.customize ) {";
        $script .= "    wp.customize.bind('ready', function() {";
        $script .= "      var control = wp.customize.control('soplos_social_order');";
        $script .= "      if ( !control ) return;";
        $script .= "      var list = control.container.find('.soplos-social-list');";
        $script .= "      list.sortable({";
        $script .= "        handle: '.soplos-drag-handle',";
        $script .= "        placeholder: 'soplos-sortable-placeholder',";
        $script .= "        helper: 'clone',"; 
        $script .= "        opacity: 0.8,";
        $script .= "        cursor: 'move',";
        $script .= "        forcePlaceholderSize: true,";
        $script .= "        update: function(event, ui) {";
        $script .= "          var order = [];";
        $script .= "          $(this).find('li').each(function() { order.push($(this).data('network')); });";
        $script .= "          $(this).next('input.social-order-input').val(order.join(',')).trigger('change');";
        $script .= "        }";
        $script .= "      });";
        $script .= "    });";
        $script .= "  }";
        $script .= "});";

        wp_add_inline_script('jquery-ui-sortable', $script);
        
        wp_add_inline_style('customize-controls', '
            .soplos-social-list { list-style: none; padding: 0; margin: 0; }
            .soplos-social-list li { 
                background: #fff; 
                border: 1px solid #e5e5e5; 
                margin-bottom: 6px; 
                display: flex; 
                align-items: stretch;
                border-radius: 4px;
                overflow: hidden;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
            
            .soplos-drag-handle {
                width: 32px;
                background-color: #f8f9fa;
                border-right: 1px solid #e5e5e5;
                cursor: move !important;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                user-select: none !important;
                -webkit-user-select: none !important;
                position: relative;
                z-index: 10;
            }
            .soplos-drag-handle:active {
                background-color: #f0f0f1;
            }

            /* Clean 3-line hamburger grip instead of dots */
            .soplos-drag-handle::before {
                content: "";
                display: block;
                width: 14px;
                height: 2px;
                background: #a7aaad;
                box-shadow: 0 4px 0 #a7aaad, 0 8px 0 #a7aaad;
                border-radius: 1px;
                margin-top: -4px; /* Center adjustment */
            }
            
            .soplos-social-content {
                flex-grow: 1;
                padding: 10px 12px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .soplos-social-label {
                font-weight: 600;
                font-size: 13px;
                color: #2c3338;
                margin-bottom: 4px;
                display: block;
                user-select: none;
            }
            
            .soplos-social-url {
                width: 100%;
                padding: 0 8px;
                height: 32px;
                line-height: normal;
                font-size: 13px;
                border: 1px solid #dcdcde;
                border-radius: 4px;
                box-shadow: none;
                transition: border-color 0.15s;
            }
            .soplos-social-url:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }

            .soplos-sortable-placeholder {
                border: 1px dashed #2271b1;
                background: rgba(34, 113, 177, 0.05);
                height: 60px; 
                margin-bottom: 6px;
                border-radius: 4px;
                visibility: visible !important;
            }
        ');
    }

    public function render_content() {
        if (empty($this->choices)) return;
        
        $values = explode(',', $this->value());
        $ordered = array();
        
        foreach ($values as $val) {
            if (array_key_exists($val, $this->choices)) $ordered[$val] = $this->choices[$val];
        }
        foreach ($this->choices as $key => $label) {
            if (!array_key_exists($key, $ordered)) $ordered[$key] = $label;
        }

        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            
            <ul class="soplos-social-list">
                <?php foreach ($ordered as $key => $label) : 
                    // Get current value
                    $current_url = $this->manager->get_setting("soplos_{$key}_url")->value();
                    ?>
                    <li data-network="<?php echo esc_attr($key); ?>">
                        <div class="soplos-drag-handle" title="<?php esc_attr_e('Drag to reorder', 'soplos'); ?>"></div>
                        <div class="soplos-social-content">
                            <span class="soplos-social-label"><?php echo esc_html($label); ?></span>
                            <!-- Use native linking: data-customize-setting-link -->
                            <input type="text" class="soplos-social-url" value="<?php echo esc_attr($current_url); ?>" placeholder="https://..." data-customize-setting-link="<?php echo esc_attr("soplos_{$key}_url"); ?>">
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <input type="hidden" class="social-order-input" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', array_keys($ordered))); ?>">
        </label>
        <?php
    }
}
