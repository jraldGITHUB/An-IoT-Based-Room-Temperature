document.getElementById("refreshBtn").addEventListener("click", updateStatus);

const ctx = document.getElementById("tempChart").getContext("2d");

let chartLabels = [];
let chartData = [];

const tempChart = new Chart(ctx,{
type:"line",
data:{
labels:chartLabels,
datasets:[{
label:"Room Temperature °C",
data:chartData,
borderWidth:3,
tension:0.3,
fill:true
}]
}
});

let rooms = {};
let selectedRoom = null;
let map;

// LOAD ROOMS
fetch("get_rooms.php")
.then(res => res.json())
.then(data => {

data.forEach(room => {

let key = "room"+room.id;

rooms[key] = {
name: room.room_name,
lat: parseFloat(room.latitude),
lng: parseFloat(room.longitude),
sensor: room.sensor_status === "OFF" ? "INACTIVE" : "ACTIVE",
temp:0,
temps:[],
circle:null,
acStart:null
};

});

initMap();
});

// MAP
function initMap(){

map = L.map('map').setView([8.359634,124.869002],20);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

for(let key in rooms){

let r = rooms[key];

let color = r.sensor === "ACTIVE" ? "yellow" : "gray";

r.circle = L.circle([r.lat,r.lng],{
color:color,
fillColor:color,
fillOpacity:0.3,
radius:15
}).addTo(map);

r.circle.bindPopup(`
<b>${r.name}</b><br>
Sensor: ${r.sensor}
`);

r.circle.on("click",function(){
selectedRoom = key;
document.getElementById("roomName").textContent = r.name;
});
}

selectedRoom = Object.keys(rooms)[0];
}

// UPDATE STATUS
function updateStatus(){

if(!selectedRoom) return;

let room = rooms[selectedRoom];

const tempEl=document.getElementById("temp");
const acEl=document.getElementById("acStatus");
const fanEl=document.getElementById("fanStatus");
const avgEl=document.getElementById("avgTemp");
const minEl=document.getElementById("minTemp");
const maxEl=document.getElementById("maxTemp");
const runtimeEl=document.getElementById("acRuntime");

// SENSOR OFF
if(room.sensor === "INACTIVE"){
tempEl.textContent="--";
avgEl.textContent="--";
minEl.textContent="--";
maxEl.textContent="--";
acEl.textContent="OFF";
fanEl.textContent="OFF";
runtimeEl.textContent="Sensor OFF";
return;
}

// SIMULATION
const roomTemp = Math.floor(Math.random()*8)+23;
const exhaustTemp = roomTemp - Math.floor(Math.random()*5);

room.temps.push(roomTemp);
if(room.temps.length>20) room.temps.shift();

// STATS
let avgTemp = (room.temps.reduce((a,b)=>a+b,0)/room.temps.length).toFixed(1);
let minTemp = Math.min(...room.temps);
let maxTemp = Math.max(...room.temps);

const fanStatus = roomTemp>=26 ? "ON" : "OFF";
const acStatus = exhaustTemp<=24 ? "ON" : "OFF";

// RUNTIME
if(acStatus==="ON" && !room.acStart){
room.acStart=new Date();
}
if(acStatus==="OFF"){
room.acStart=null;
}

let runtime="0 min";
if(room.acStart){
let diff=(new Date()-room.acStart)/60000;
runtime = diff<60 ? diff.toFixed(1)+" min" : (diff/60).toFixed(2)+" hrs";
}

runtimeEl.textContent=runtime;

// UI
tempEl.textContent=roomTemp+" °C";
avgEl.textContent=avgTemp+" °C";
minEl.textContent=minTemp+" °C";
maxEl.textContent=maxTemp+" °C";

acEl.textContent=acStatus;
fanEl.textContent=fanStatus;

// CHART
const time=new Date().toLocaleTimeString();
chartLabels.push(time);
chartData.push(roomTemp);

if(chartLabels.length>10){
chartLabels.shift();
chartData.shift();
}

tempChart.update();

// MAP COLOR
let zoneColor="yellow";
if(roomTemp<=23) zoneColor="blue";
else if(roomTemp>=28) zoneColor="red";

room.circle.setStyle({
color:zoneColor,
fillColor:zoneColor
});

// POPUP UPDATE
room.circle.bindPopup(`
<b>${room.name}</b><br>
Sensor: ${room.sensor}<br>
Temp: ${roomTemp}°C<br>
AC: ${acStatus}<br>
Fan: ${fanStatus}<br>
Runtime: ${runtime}
`);

document.getElementById("lastUpdate").innerText = new Date().toLocaleTimeString();

// SAVE LOGS (OPTIONAL: REMOVE IF USER SHOULD NOT SAVE)
fetch("logs_page/save_logs.php",{
method:"POST",
headers:{"Content-Type":"application/json"},
body:JSON.stringify({
date:new Date().toLocaleDateString(),
time:new Date().toLocaleTimeString(),
room:room.name,
roomTemp:roomTemp,
exhaustTemp:exhaustTemp,
aircon:acStatus,
exhaustFan:fanStatus,
runtime:runtime
})
});

}

// AUTO UPDATE
setInterval(updateStatus,30000);