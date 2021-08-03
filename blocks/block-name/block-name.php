<?php

if ( ! isset( $attributes ) ) {
	return;
}

$attributes = filter_var_array(
	$attributes,
	array(
		'attr' => FILTER_DEFAULT,
		'className' => FILTER_DEFAULT,
	)
);

?>

<div class="<?php echo $attributes['className'] ?>">

</div>
