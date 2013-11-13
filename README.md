remotecc
========

ComputerCraft - PHP JSON Interface

The main purpose of this is to allow ComputerCraft to communicate with PHP
This can be used, for example, to create a web control panel - sending controls (stop or start) from PHP and info and status from ComputerCraft


Installation
------------
First of all, download the remotecc script
You can chose how to do it:
	1. Download this whole package and move the script to the computer save folder
	2. Use https://raw.github.com/KamilKZ/remotecc/master/lua/remotecc and copy it in
	3. Use https://github.com/seriallos/computercraft and the command "github get KamilKZ/remotecc/master/lua/remotecc remotecc"

Then you need to upload "cc.php", to a webhost that supports PHP, duh.

"http" must be enabled in ComputerCraft

Usage/API
---------

You should look at the included examples too, they're in the php and lua folders respectively

Lua:

	`Connection remotecc:connect( string hostphp, integer id, string name ) `
		hostphp is the url to the cc.php(with "cc.php" included)
		id is the id of the computer, this will overwrite the same ids
		name just gets used to set the "name" field in the json file, it's kind of useless, unless you want to have two computers sending data between them with a check to see if it's been modified
		
	`nil/table remotecc:request()`
		queries the server to return data
		returns a table representation of the json data
		
	`nil remotecc:put(string json)`
		queries the server to save data
		
	`nil remotecc:putTable(table data)`
		wrapped remotecc.put for use with tables
		
PHP:

	`include('cc.php');`
		Includes the required file
	
	From then on, you can use these:
		
	`boolean CC::alive( integer connection_id )`
		Checks if there was activity within the past 30 seconds
		
	`array CC::open( integer connection_id )`
		Opens the file containing data for the connection and returns an array of the decoded JSON contents.
		If not file is present, an emtpy array is returned.
		
	`nil CC::save( integer connection_id, array data )`
		Saves the array encoded as JSON into the file of the connection
		
	`nil CC::receive( integer connection_id, string sender_name, jsonstring data )`
		This is used by the Interface for CC, but can also be used, as in 'example.php'
		It also writes the request time in the file, therefore CC::alive will work
		
	`array/false CC::request( integer connection_id )`
		Basically the same as CC::open but it will return false instead if there is no connection established ( no data present ).
		It also writes the request time in the file, therefore CC::alive will work
		
example.php
	You can use www.host.com/example.php?on (notice '?on'), to set the "on" field in the json to 1/on, and with '?off', it will set "on" to 0/off
	
	If accessed without any CC GET parameters, it will display the value of "on"
	
example.lua
	Connects to the host and attempts to retrieve the "on" field, upon doing so it will set the right redstone output to "on"'s value
	It also polls the redstone input on the left side and returns this to the host as the "onOut" field.