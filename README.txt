1. Upload "cc.php" to a webhost that supports PHP (duh) - Tested with PHP4, should work with 5 :D
2. The ComputerCraft part of this relies on the "http" API in CC, you have to enable it - Google it if you don't know
3. Copy json and remotecc onto a computer you want to use this with.
4. Make use of it.

The example provides a hopefully understandable example.
If not here is a API breakdown/doc:
Lua:

	Connection remotecc.connect( string hostphp, integer id, string name ) 
		hostphp is the url to the cc.php(with "cc.php" included)
		id is the id of the computer, this will overwrite the same ids
		name is kind of useless, unless you want to have two computers sending data between them with a check to see if it's been modified
		
	nil/table remotecc.request()
		queries the server to return data
		if error or not connecting then returns nil
		otherwise will return a table representation of the json data
		
	nil remotecc.put(string json)
		queries the server to save data
		error handling doesn't really work
		
	nil remotecc.putTable(table data)
		wrapped remotecc.put for use with tables
		
PHP:

	include('cc.php');
		Includes the required file
	
	From then on, you can use these:
		
	boolean CC::alive( integer connection_id )
		Checks if there was activity within the past 30 seconds
		
	array CC::open( integer connection_id )
		Opens the file containing data for the connection and returns an array of the decoded JSON contents.
		If not file is present, an emtpy array is returned.
		
	nil CC::save( integer connection_id, array data )
		Saves the array encoded as JSON into the file of the connection
		
	nil CC::receive( integer connection_id, string sender_name, jsonstring data )
		This is used by the Interface for CC, but can also be used, as in 'example.php'
		It also writes the request time in the file, therefore CC::alive will work
		
	array/false CC::request( integer connection_id )
		Basically the same as CC::open but it will return false instead if there is no connection established ( no data present ).
		It also writes the request time in the file, therefore CC::alive will work
		
example.php
	You can use www.host.com/example.php?on (notice '?on'), to set the "on" field in the json to 1/on, and with '?off', it will set "on" to 0/off
	
	If accessed without any CC GET parameters, it will display the value of "on"
	
example.lua
	Connects to the host and attempts to retrieve the "on" field, upon doing so it will set the right redstone output to "on"'s value
	It also polls the redstone input on the left side and returns this to the host as the "onOut" field.
		
		
		