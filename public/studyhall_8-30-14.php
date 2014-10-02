<?php 

$array = [
		 'one', 
		 'two', 
	     'three'
];

print_r($array);

echo $array[1] . PHP_EOL;

$assoc_array = [
                'key0' => 'one',
                'key1' => 'two',
                'key2' => 'three'
];

print_r($assoc_array);

echo $assoc_array['key1'] . PHP_EOL;

$multi_array = [
                'groceries' => [
		                       'item1'	=>	'apples', 
		                       'item2'	=>	'bananas',
		                       'store'  => 'HEB',
		                       'money'  => 13.00;
		                       'addtl'	=>	['pears', 'schnapps', 'pear-flavored schnapps']
],
	                                
	                                
];

// var_dump($multi_array);

echo $multi_array['store'] . PHP_EOL;
echo $multi_array['groceries']['item2'] . PHP_EOL;
echo $multi_array['groceries']['addtl'][2] . PHP_EOL;




?>