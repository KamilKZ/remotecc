os.loadAPI("remotecc")

local cc = {}
cc.host = "http://my.host/remoteComputerCraft/whatever/cc.php"
cc.id 	= 11
cc.name = "A name"

function toboolean(v)
    return (type(v) == "string" and v == "true") or (type(v) == "string" and tonumber(v) and tonumber(v)~=0) or (type(v) == "number" and v ~= 0) or (type(v) == "boolean" and v)
end

os.setComputerLabel(cc.name)
local connection = remotecc.connect(cc.host,cc.id,cc.name) --Establish connection with host
print("Connecting as "..os.getComputerLabel().." with id "..cc.id)

while true do
	local data = connection:request() 	--Poll data from the host, 'data' table is the JSON that was on the host, it is converted into lua tables already
											--The JSON would look like this: {"on":1}
	if toboolean(data.on) then 			--If the on field is set to 1/"true"/"1" then
		rs.setOutput("left",true)		--Set left rs to on
		
		local ret = {}
		ret.onOut = tonumber(rs.getOutput("right")) --Poll input
		connection:putTable(ret) 		--Send back input
		
		--NOTE: Sending any data back is not required
		connection:put("{\"also\":\"you can send direct json\"")
	else --If off
		rs.setOutput("left",false)		--Set left rs to off
	end
	sleep(1)	--Do this every second.
end