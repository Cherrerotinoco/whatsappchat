/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
jQuery(document).ready(function(){

    loadHtmlPrev();
    setDefaultValues();
    bindValues();

});

function loadHtmlPrev() {
    let html = 
    `
    <div class="panel frame-preview">

    <span class="text-preview">Preview</span>

    <div id="whatsapp-custom-button">

    <div id="body-whatsapp">
        <span></span>
    </div>

    <form id="whatsapp-form">
        <input type="text" name="whatsapp" placeholder="">
        <button disabled type="submit">
        
        </button>
    </form>

    <span class="close-button"></span>

    </div>
    </div>

    `
    ;

    jQuery('#module_form').append(html);
}
function setDefaultValues() {
    jQuery("#body-whatsapp span").text(jQuery("#call_to_action").attr('value'));
    jQuery("#whatsapp-form input").attr('placeholder', jQuery("#input_placeholder").attr('value'));
    jQuery("#whatsapp-form button").text(jQuery("#submit_text").attr('value'));
    
    jQuery('.radio input').each(function(){
        if (jQuery(this).prop('checked')) {
            let positionCSS = jQuery(this).attr('value');
            let top = 'auto';
            let right = 'auto';
            let bottom = 'auto';
            let left = 'auto';

            if (positionCSS === 'top-left') {
                top = '-25px' ;
                left = '-25px' ; 
            } else if (positionCSS === 'top-right') {
                top = '-25px' ;
                right = '-25px' ; 
            } else if (positionCSS === 'bottom-right') {
                bottom = '-25px' ;
                right = '-25px' ; 
            } else if (positionCSS === 'bottom-left') {
                bottom = '-25px' ;
                left = '-25px' ; 
            }

            jQuery('#whatsapp-custom-button').animate(
                {
                    "top": top,
                    "right": right,
                    "bottom": bottom,
                    "left": left,
                }, 500
            );
        }
    });
}
function bindValues() {
    jQuery("#call_to_action").keyup(function(){
        jQuery("#body-whatsapp span").text(this.value);
    });
    
    jQuery("#input_placeholder").keyup(function(){
        jQuery("#whatsapp-form input").attr('placeholder', this.value);
    });

    jQuery("#submit_text").keyup(function(){
        jQuery("#whatsapp-form button").text(this.value);
    });
    jQuery('.radio input').each(function(){
        jQuery(this).on('click', function(){
            console.log(jQuery(this).attr('value'));
            let positionCSS = jQuery(this).attr('value');
            let top = 'auto';
            let right = 'auto';
            let bottom = 'auto';
            let left = 'auto';

            if (positionCSS === 'top-left') {
                top = '-25px' ;
                left = '-25px' ; 
            } else if (positionCSS === 'top-right') {
                top = '-25px' ;
                right = '-25px' ; 
            } else if (positionCSS === 'bottom-right') {
                bottom = '-25px' ;
                right = '-25px' ; 
            } else if (positionCSS === 'bottom-left') {
                bottom = '-25px' ;
                left = '-25px' ; 
            }

            jQuery('#whatsapp-custom-button').animate(
                {
                    "top": top,
                    "right": right,
                    "bottom": bottom,
                    "left": left,
                }, 500
            );
        });
    });
}