# Description
Custom Template Parser for Codeigniter v2.x

This is old code I used when develop some apps using CodeIgniter.
I should upload this earlier. But however maybe someone still use v2.x

For anyone don't know what template parser, [here](https://codeigniter.com/userguide2/libraries/parser.html)

This is only extended CodeIgniter parser class, add some features below.
- Add numbering for foreach list
- Support multidimensional array
- Leave empty for not found parsing

# installation
- Copy `application/libraries/MY_Parser.php` to your CodeIgniter structure

# usage
## numbering
Just use `{num}` in foreach
- Example:
You have this array data:
```php
$data['fruits'] = array(
	array(
		'name'=> 'Apple',
		'total'=> 20,
	),
	array(
                'name'=> 'Orange',
                'total'=> 5,
        ),
	array(
                'name'=> 'Melon',
                'total'=> 13,
        );
);
$this->parser->parse('some_view', $data);
```

So, in your view just add `{num}`.
```html
{fruits}
<tr>
	<td>{num}</td>
	<td>{name}</td>
	<td>{total}</td>
</tr>
{/fruits}
```
Ofcourse it works with database result `$query->result_array()`

## multidimensional array
No special, just use like normal array data

```php

$buses = array(
	array(
		'name' => 'Trans Jakarta',
		'year' => '2011'
	),
	array(
                'name' => 'Kopaja',
                'year' => '1998'
        ),
	array(
                'name' => 'Metromini',
                'year' => '1980'
        ),
	'total' => 20 //non-array data
);

$trains = array();

$data['transportations'] = array(
	'cars' => $buses, //array of buses
	'trains' => $trains, //array of trains
);
```


```html
{transportations}
	{cars}
		{bus}
			{name}
		{/bus}
		<!--if it's not array inside multidimensional -->
        	{bus.total}
	{/cars}
	...	
{/transportations}
```


# license
MIT
