const chatData = document.getElementById('chatData');

function renderValue(value) {
if (Array.isArray(value)) {
    const list = document.createElement('ul');
    value.forEach(item => {
        const entry = document.createElement('li');
        entry.appendChild(renderValue(item));
        list.appendChild(entry);
    });
    return list;
}

if (value && typeof value === 'object') {
    const table = document.createElement('table');
    Object.entries(value).forEach(([key, item]) => {
        const row = table.insertRow();
        const heading = row.insertCell();
        const content = row.insertCell();
        heading.textContent = key;
        content.appendChild(renderValue(item));
    });
    return table;
}

const text = document.createElement('span');
text.textContent = value ?? '';
return text;
}

fetch('./kalyanData.json')
.then(response => {
    if (!response.ok) throw new Error(`Request failed: ${response.status}`);
    return response.json();
})
.then(data => {
    console.log('Fetched data:', data, 'items');
    
    const sortedData = data.map(item => {
        const dateValue = item.result_date ?? item.Date ?? '';
        // JavaScript does not reliably parse dates in DD/MM/YYYY format.
        const match = String(dateValue).match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        const date = match
            ? new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]))
            : new Date(dateValue);
        const dayName = Number.isNaN(date.getTime())
            ? ''
            : date.toLocaleDateString('en-US', { weekday: 'long' });
        return { item, timestamp: date.getTime(), dayName };
    }).sort((a, b) => {
        const aInvalid = Number.isNaN(a.timestamp);
        const bInvalid = Number.isNaN(b.timestamp);
        if (aInvalid || bInvalid) return aInvalid === bInvalid ? 0 : aInvalid ? 1 : -1;
        return a.timestamp - b.timestamp;
    }).map(({ item, dayName }) => ({ ...item, dayName }));

    console.log('Sorted data:', sortedData);
   // return false; // Return false to indicate that the data has been processed

    const chunks = [];
    let currentChunk = [];
    sortedData.forEach(item => {
        currentChunk.push(item);
        if (String(item.dayName).toLowerCase() === 'saturday') {
            chunks.push(currentChunk);
            currentChunk = [];
        }
    });
    if (currentChunk.length > 0) chunks.push(currentChunk);
    const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    chunks.forEach((chunk, index) => {
        chunk.unshift({
            fromdate: chunk[0].result_date ?? chunk[0].Date ?? '',
            todate: chunk[chunk.length - 1].result_date ?? chunk[chunk.length - 1].Date ?? '',
        });
        daysOfWeek.forEach(day => {
            if (!chunk.some(item => String(item.dayName).toLowerCase() === day.toLowerCase())) {
                const missingItem = {
                    name: "KALYAN",
                    open: "***",
                    jodi: "**",
                    close: "***",
                    result: "***-**-***",
                    open_time: "**",
                    close_time: "**",
                    result_date: "**",
                    fetch_datetime: null,
                    dayName: day
                };

                const dayIndex = daysOfWeek.indexOf(day);
                const insertIndex = chunk.findIndex(item => {
                    const itemDayIndex = daysOfWeek.indexOf(item.dayName);
                    return itemDayIndex !== -1 && itemDayIndex > dayIndex;
                });
                chunk.splice(
                    insertIndex === -1 ? chunk.length : insertIndex,
                    0,
                    missingItem
                );
            }
        });
        
    });
    

    console.log('Fetched data chunks:', chunks);
    const table = document.getElementById("chatTableBody");
    chunks.forEach((chunk, index) => {
        const chunkContainer = document.createElement('tr');
        chunk.forEach(item => {
            //console.log('Rendering item:', item);
            const cell = document.createElement('td');
            if(item.fromdate && item.todate) {
                cell.innerHTML = `${item.fromdate} <br><br> ${item.todate}`;
            }else{
                cell.innerHTML = `<div class="item-container"><div class="open-class">${item.open}</div><div style="display: flex; align-items: center;"><div class="red-circle-badge" title="${item.dayName} and ${item.result_date}"><b> ${item.jodi} </b></div></div> <div class="open-class">${item.close}</div></div>`;
            }
            
            chunkContainer.appendChild(cell);
        });
        table.appendChild(chunkContainer);
    });
})
.catch(error => {
    chatData.textContent = `Unable to load chat data: ${error.message}`;
});