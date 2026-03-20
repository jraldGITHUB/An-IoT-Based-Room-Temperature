document.addEventListener("DOMContentLoaded", loadLogs);

function loadLogs(){

fetch("get_logs.php")
.then(res => res.json())
.then(data => {

const table = document.getElementById("logTable");
table.innerHTML = "";

data.forEach(log => {

let row = document.createElement("tr");

row.innerHTML = `
<td>${log.date}</td>
<td>${log.time}</td>
<td><strong>${log.room}</strong></td>
<td>${log.roomTemp}°C</td>
<td>${log.exhaustTemp}°C</td>

<td>
<span class="badge ${log.aircon === "ON" ? "bg-danger" : "bg-success"}">
${log.aircon}
</span>
</td>

<td>
<span class="badge ${log.exhaustFan === "ON" ? "bg-warning" : "bg-secondary"}">
${log.exhaustFan}
</span>
</td>

<td>
<span class="badge ${log.runtime.includes("hrs") ? "bg-danger" : "bg-info"}">
${log.runtime}
</span>
</td>
`;

table.appendChild(row);

});

});

}









