<?php

namespace App;

use Carbon\Carbon;

/**
 * Создает выпадающее меню.
 * Создает элемент формы select с аттрибутом name = $name и набором значений option из ассоц. массива $options (['key'] => 'value')
 * и аттрибутом selected для значения, если $selected = значению ключа $options.
 * @param array $options
 * @param string $name
 * @param string $selected
 * @return string
 */
function makeDropDown(array $options, ?string $name, $selected = '', $attributes = ''): string
{
	if (empty($options)) {
		return false;
	}
    $output = '<select size="1" id="'.$name.'" name="'.$name.'" '.$attributes.'>'. PHP_EOL;
	foreach ($options as $key => $value){
    	$output.= '<option value="'.$key.'"'.(($selected == $key) ? ' selected' : '').'>'.$value.'</option>'. PHP_EOL;
    }   
	$output.= '</select>'.PHP_EOL ;
	return  $output;
}

/**
 * Enter description here...
 * @access public
 * @param string $return1
 * @param string $return2
 * @param int $every
 * @return string
 */
function cute_that($odd = 'class="enabled"', $even = 'class="disabled"', $every = 2): string
{
	static $i = 0;
	$i++;
	return ($i%$every == 0) ? $odd : $even;
}

/**
 * 
 */

function Carbon($date): Carbon
{
	return Carbon::parse($date);
}

// $words = ['комментарий', 'комментария', 'комментариев'];
// spNumber( $count, ['комментарий', 'комментария', 'комментариев'] )
function spNumber( int $n, array $words, $cases = [2, 0, 1, 1, 1, 2] ) 
{
	return $words[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

// $url = 'https://youtu.be/AIr32fer35ete';
// $vId = str($url)->after('youtu.be/');
// AIr32fer35ete
