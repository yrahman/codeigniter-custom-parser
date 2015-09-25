# codeigniter-custom-parser
Custom Parser for Codeigniter v2

This is old code I used when develop some apps using CodeIgniter.
I should upload this earlier. But however maybe this works with CI v3 too.
Never try v3

This is only extended CodeIgniter parser class, add some features below.
- Add numbering for foreach list
- Support multidimensional array
- Leave empty for not found parsing

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
//data
$data['transportations'] = array(
	'cars' => $buses, // array of buses
	'trains' => $trains, //array of trains
	'total' => 20 //non-array data
);
```


```html
{transportations}
	{cars}
		{bus}
			{name}
		{/bus}
	{/cars}
	...
	<!--if it's not array inside multidimensional -->
	{cars.total}
	
{/transportations}
```


# license
MIT
